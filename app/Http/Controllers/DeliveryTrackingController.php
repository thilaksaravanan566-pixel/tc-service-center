<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryTrackingController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // CUSTOMER: Save their delivery address + coordinates
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /customer/location/save
     * Saves customer-chosen delivery location for an order.
     */
    public function saveCustomerLocation(Request $request): JsonResponse
    {
        $request->validate([
            'order_type' => 'required|in:service,product',
            'order_id'   => 'required|integer',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'address'    => 'nullable|string|max:500',
        ]);

        $loc = DeliveryLocation::forOrder($request->order_type, $request->order_id);
        $loc->update([
            'customer_id'      => Auth::guard('customer')->id(),
            'customer_lat'     => $request->lat,
            'customer_lng'     => $request->lng,
            'customer_address' => $request->address,
        ]);

        return response()->json(['success' => true, 'message' => 'Location saved successfully.']);
    }

    // ─────────────────────────────────────────────────────────────
    // CUSTOMER: Live tracking page for their delivery
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /customer/orders/{id}/live-track
     */
    public function customerTrackPage(Request $request, int $orderId)
    {
        $orderType = $request->query('type', 'product');
        
        $authorized = false;
        if ($orderType === 'product') {
            $order = \App\Models\ProductOrder::find($orderId);
            if ($order && $order->customer_id === Auth::guard('customer')->id()) $authorized = true;
        } elseif ($orderType === 'service') {
            $order = \App\Models\ServiceOrder::find($orderId);
            if ($order && $order->customer_id === Auth::guard('customer')->id()) $authorized = true;
        }

        if (!$authorized) abort(403, 'Unauthorized to view tracking for this manifest.');

        $location  = DeliveryLocation::where('order_type', $orderType)
                                     ->where('order_id', $orderId)
                                     ->with('deliveryPartner')
                                     ->first();

        return view('customer.tracking.live', compact('location', 'orderId', 'orderType'));
    }

    // ─────────────────────────────────────────────────────────────
    // DEALER: Live tracking page for their delivery
    // ─────────────────────────────────────────────────────────────

    public function dealerTrackPage(Request $request, int $orderId)
    {
        $orderType = $request->query('type', 'product');
        
        $authorized = false;
        $user = Auth::user();
        if ($orderType === 'product') {
            $order = \App\Models\DealerOrder::find($orderId);
            if ($order && $order->dealer_id === $user->id) $authorized = true;
        } elseif ($orderType === 'service') {
            $order = \App\Models\ServiceOrder::find($orderId);
            if ($order && $order->dealer_id === $user->id) $authorized = true;
        }

        if (!$authorized) abort(403, 'Unauthorized to view tracking for this manifest.');

        $location  = DeliveryLocation::where('order_type', $orderType)
                                     ->where('order_id', $orderId)
                                     ->with('deliveryPartner')
                                     ->first();

        return view('dealer.tracking.live', compact('location', 'orderId', 'orderType'));
    }

    // ─────────────────────────────────────────────────────────────
    // POLLING API: Customer polls for partner location
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/tracking/{orderId}/status?type=product
     */
    public function getDeliveryStatus(int $orderId, Request $request): JsonResponse
    {
        $orderType = $request->query('type', 'product');
        /** @var \App\Models\DeliveryLocation|null $location */
        $location  = DeliveryLocation::query()->where('order_type', $orderType)
                                     ->where('order_id', $orderId)
                                     ->first();

        if (!$location) {
            return response()->json(['found' => false]);
        }

        // --- AUTHORIZATION CHECK ---
        $authorized = false;
        
        if (Auth::guard('customer')->check()) {
            if ($orderType === 'product') {
                $order = \App\Models\ProductOrder::find($orderId);
                if ($order && $order->customer_id === Auth::guard('customer')->id()) $authorized = true;
            } elseif ($orderType === 'service') {
                $order = \App\Models\ServiceOrder::find($orderId);
                if ($order && $order->customer_id === Auth::guard('customer')->id()) $authorized = true;
            }
        } elseif (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin' || $user->role === 'delivery_partner') {
                $authorized = true;
            } elseif ($user->role === 'dealer') {
                if ($orderType === 'product') {
                    $order = \App\Models\DealerOrder::find($orderId);
                    if ($order && $order->dealer_id === $user->id) $authorized = true;
                } elseif ($orderType === 'service') {
                    $order = \App\Models\ServiceOrder::find($orderId);
                    if ($order && $order->dealer_id === $user->id) $authorized = true;
                }
            }
        }

        if (!$authorized) {
            return response()->json(['found' => false, 'error' => 'Unauthorized tracing request.']);
        }
        // --- END AUTHORIZATION ---

        return response()->json([
            'found'          => true,
            'delivery_status'=> $location->delivery_status,
            'partner_lat'    => $location->partner_lat,
            'partner_lng'    => $location->partner_lng,
            'customer_lat'   => $location->customer_lat,
            'customer_lng'   => $location->customer_lng,
            'customer_address'=> $location->customer_address,
            'last_updated'   => $location->partner_location_updated_at
                                    ? $location->partner_location_updated_at->diffForHumans()
                                    : null,
            'partner_name'   => optional($location->deliveryPartner)->name,
            'partner_phone'  => optional($location->deliveryPartner)->mobile,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // DELIVERY PARTNER: Push their live location
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /api/delivery/location/update
     */
    public function updatePartnerLocation(Request $request): JsonResponse
    {
        $request->validate([
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
            'order_id' => 'nullable|integer',
            'order_type'=> 'nullable|in:service,product',
        ]);

        $partnerId = Auth::id() ?? $request->user()?->id;

        // Update the user's own lat/lng for general tracking
        if ($partnerId) {
            User::where('id', $partnerId)->update([
                'current_lat'        => $request->lat,
                'current_lng'        => $request->lng,
                'location_updated_at'=> now(),
                'is_online'          => true,
            ]);
        }

        // Also update the specific delivery record if order context given
        if ($request->order_id && $request->order_type) {
            $loc = DeliveryLocation::forOrder($request->order_type, $request->order_id);
            $loc->updatePartnerLocation($request->lat, $request->lng);
            if ($partnerId) {
                $loc->update(['delivery_partner_id' => $partnerId, 'delivery_status' => 'in_transit']);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/delivery/location/offline
     * Called when delivery partner goes offline.
     */
    public function setOffline(Request $request): JsonResponse
    {
        $partnerId = Auth::id() ?? $request->user()?->id;
        if ($partnerId) {
            User::where('id', $partnerId)->update(['is_online' => false]);
        }
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // DELIVERY PARTNER DASHBOARD
    // ─────────────────────────────────────────────────────────────

    public function deliveryDashboard()
    {
        $partner   = Auth::user();
        $locations = DeliveryLocation::where('delivery_partner_id', optional($partner)->id)
                                     ->whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
                                     ->get();

        // Active deliveries with customer coordinates
        $activeDeliveries = $locations->filter(fn($l) => $l->customer_lat && $l->customer_lng);

        return view('delivery.dashboard', compact('partner', 'locations', 'activeDeliveries'));
    }

    // ─────────────────────────────────────────────────────────────
    // ADMIN: All active deliveries on a map
    // ─────────────────────────────────────────────────────────────

    public function adminDeliveryMap()
    {
        $activeDeliveries = DeliveryLocation::whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
                                            ->with('deliveryPartner', 'customer')
                                            ->get();

        $onlinePartners = User::where('role', 'delivery_partner')
                              ->where('is_online', true)
                              ->select('id', 'name', 'mobile', 'current_lat', 'current_lng', 'location_updated_at')
                              ->get();

        return view('admin.delivery.live_map', compact('activeDeliveries', 'onlinePartners'));
    }

    /**
     * GET /api/admin/delivery/map-data
     * JSON feed for admin map auto-refresh
     */
    public function adminMapData(): JsonResponse
    {
        $deliveries = DeliveryLocation::whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
            ->with('deliveryPartner:id,name,mobile,current_lat,current_lng')
            ->get()
            ->map(fn($l) => [
                'id'             => $l->id,
                'order_id'       => $l->order_id,
                'order_type'     => $l->order_type,
                'status'         => $l->delivery_status,
                'partner_lat'    => $l->partner_lat,
                'partner_lng'    => $l->partner_lng,
                'customer_lat'   => $l->customer_lat,
                'customer_lng'   => $l->customer_lng,
                'customer_address' => $l->customer_address,
                'partner_name'   => optional($l->deliveryPartner)->name,
                'last_updated'   => $l->partner_location_updated_at?->diffForHumans(),
            ]);

        $partners = User::where('role', 'delivery_partner')
            ->where('is_online', true)
            ->select('id', 'name', 'current_lat', 'current_lng', 'location_updated_at')
            ->get();

        return response()->json(['deliveries' => $deliveries, 'partners' => $partners]);
    }
}
