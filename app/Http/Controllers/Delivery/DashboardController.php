<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryLocation;
use App\Models\DeliveryUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Delivery Partner Dashboard Controller
 *
 * Handles the /delivery/* portal for assigned delivery partners.
 * Routes are registered under the delivery.thambucomputers.com subdomain
 * AND the /delivery prefix on the main domain.
 */
class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $partner */
        $partner = Auth::user();

        $locations = DeliveryLocation::query()
            ->where('delivery_partner_id', $partner->id)
            ->whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
            ->with(['customer'])
            ->latest()
            ->get();

        $completedToday = DeliveryLocation::query()
            ->where('delivery_partner_id', $partner->id)
            ->where('delivery_status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();

        $totalAssigned = DeliveryLocation::query()
            ->where('delivery_partner_id', $partner->id)
            ->count();

        $activeDeliveries = $locations->filter(
            fn($l) => $l->customer_lat && $l->customer_lng
        );

        return view('delivery.dashboard', compact(
            'partner', 'locations', 'activeDeliveries', 'completedToday', 'totalAssigned'
        ));
    }

    /**
     * Show a single delivery detail with history timeline.
     */
    public function show(int $locationId)
    {
        /** @var User $partner */
        $partner  = Auth::user();
        $location = DeliveryLocation::query()
            ->where('id', $locationId)
            ->where('delivery_partner_id', $partner->id)
            ->with(['customer'])
            ->firstOrFail();

        $history = DeliveryUpdate::query()
            ->where('delivery_location_id', $location->id)
            ->with('updatedBy:id,name')
            ->latest()
            ->get();

        return view('delivery.show', compact('location', 'history'));
    }

    /**
     * POST — Update delivery status with optional proof photo.
     */
    public function updateStatus(Request $request, int $locationId)
    {
        $request->validate([
            'status'      => 'required|in:picked_up,in_transit,delivered,failed',
            'note'        => 'nullable|string|max:500',
            'proof_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        /** @var User $partner */
        $partner  = Auth::user();
        $location = DeliveryLocation::query()
            ->where('id', $locationId)
            ->where('delivery_partner_id', $partner->id)
            ->firstOrFail();

        $proofPath = null;
        if ($request->hasFile('proof_photo')) {
            $proofPath = $request->file('proof_photo')->store(
                'delivery_proofs/' . $partner->id, 'public'
            );
        }

        $previous = $location->delivery_status;

        $location->update(['delivery_status' => $request->status]);

        DeliveryUpdate::create([
            'delivery_location_id' => $location->id,
            'updated_by'           => $partner->id,
            'status'               => $request->status,
            'previous_status'      => $previous,
            'note'                 => $request->note,
            'proof_photo'          => $proofPath,
            'lat'                  => $request->lat,
            'lng'                  => $request->lng,
        ]);

        // WhatsApp notification on delivered
        if ($request->status === 'delivered' && $location->customer?->mobile) {
            try {
                app(\App\Services\WhatsAppService::class)->sendMessage(
                    $location->customer->mobile,
                    "Your order has been delivered! Thank you for choosing Thambu Computers. 🎉"
                );
            } catch (\Throwable $e) {
                // Non-fatal — log and continue
                logger()->warning('WhatsApp delivery notification failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Delivery status updated to ' . ucwords(str_replace('_', ' ', $request->status)) . '.');
    }

    /**
     * POST — Send OTP to customer's mobile for delivery confirmation.
     */
    public function sendOtp(Request $request, int $locationId)
    {
        $location = DeliveryLocation::query()
            ->where('id', $locationId)
            ->where('delivery_partner_id', Auth::id())
            ->with('customer')
            ->firstOrFail();

        $phone = $location->customer?->mobile;
        if (!$phone) {
            return back()->with('error', 'Customer phone number not available.');
        }

        $otp = rand(100000, 999999);

        // Store OTP (15-min expiry)
        \DB::table('delivery_otps')->updateOrInsert(
            ['delivery_location_id' => $location->id],
            [
                'phone'      => $phone,
                'otp'        => $otp,
                'used'       => false,
                'expires_at' => now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Send via WhatsApp
        try {
            app(\App\Services\WhatsAppService::class)->sendMessage(
                $phone,
                "Thambu Computers Delivery OTP: {$otp}\nShare this with the delivery partner to confirm receipt. Valid 15 mins."
            );
        } catch (\Throwable $e) {
            logger()->warning('OTP WhatsApp send failed: ' . $e->getMessage());
        }

        return back()->with('success', "OTP sent to customer's WhatsApp ({$phone}).");
    }

    /**
     * POST — Verify OTP entered by delivery partner.
     */
    public function verifyOtp(Request $request, int $locationId)
    {
        $request->validate(['otp' => 'required|digits_between:4,6']);

        $location = DeliveryLocation::query()
            ->where('id', $locationId)
            ->where('delivery_partner_id', Auth::id())
            ->firstOrFail();

        $record = \DB::table('delivery_otps')
            ->where('delivery_location_id', $location->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record || (string)$record->otp !== $request->otp) {
            return back()->with('error', 'Invalid or expired OTP. Please request a new one.');
        }

        \DB::table('delivery_otps')
            ->where('id', $record->id)
            ->update(['used' => true, 'updated_at' => now()]);

        $previous = $location->delivery_status;
        $location->update(['delivery_status' => 'delivered']);

        DeliveryUpdate::create([
            'delivery_location_id' => $location->id,
            'updated_by'           => Auth::id(),
            'status'               => 'delivered',
            'previous_status'      => $previous,
            'note'                 => 'OTP verified delivery confirmation',
            'otp_code'             => $request->otp,
            'otp_verified'         => true,
            'otp_verified_at'      => now(),
        ]);

        return back()->with('success', '✅ OTP verified! Delivery confirmed as completed.');
    }
}
