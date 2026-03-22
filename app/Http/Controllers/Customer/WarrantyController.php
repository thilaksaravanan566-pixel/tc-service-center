<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WarrantyCertificate;
use App\Models\WarrantyClaim;

class WarrantyController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    /**
     * List all warranties for the authenticated customer.
     */
    public function index()
    {
        $warranties = WarrantyCertificate::with(['sparePart', 'serviceOrder', 'claims'])
            ->where('customer_id', $this->customer()->id)
            ->latest()
            ->get();

        return view('customer.warranty.index', compact('warranties'));
    }

    /**
     * Show a single warranty certificate and its claim history.
     */
    public function show($id)
    {
        $warranty = WarrantyCertificate::with(['sparePart', 'serviceOrder', 'claims.handler'])
            ->where('customer_id', $this->customer()->id)
            ->findOrFail($id);

        $existingClaim = $warranty->claims()
            ->whereIn('status', ['pending', 'reviewing'])
            ->exists();

        return view('customer.warranty.show', compact('warranty', 'existingClaim'));
    }

    /**
     * Submit a new warranty claim.
     */
    public function claim(Request $request, $warrantyId)
    {
        $request->validate([
            'description'   => 'required|string|min:20|max:2000',
            'evidence'      => 'nullable|array',
            'evidence.*'    => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $warranty = WarrantyCertificate::where('customer_id', $this->customer()->id)
            ->findOrFail($warrantyId);

        if (!$warranty->is_active) {
            return back()->with('error', 'This warranty is expired or already fully claimed.');
        }

        // Check for pending claims
        $activeClaim = $warranty->claims()
            ->whereIn('status', ['pending', 'reviewing'])
            ->exists();

        if ($activeClaim) {
            return back()->with('error', 'You already have a pending claim for this warranty.');
        }

        // Upload evidence photos
        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $photo) {
                $evidencePaths[] = $photo->store('warranty_evidence', 'public');
            }
        }

        WarrantyClaim::create([
            'warranty_certificate_id' => $warranty->id,
            'customer_id'             => $this->customer()->id,
            'description'             => $request->description,
            'evidence_photos'         => $evidencePaths,
            'status'                  => 'pending',
        ]);

        // Notify customer
        \App\Models\CustomerNotification::create([
            'customer_id' => $this->customer()->id,
            'type'        => 'warranty',
            'title'       => '🛡️ Warranty Claim Submitted',
            'message'     => 'Your warranty claim has been received. We will review it shortly.',
            'action_url'  => route('customer.warranty.show', $warranty->id),
            'icon'        => '🛡️',
        ]);

        return redirect()->route('customer.warranty.show', $warranty->id)
            ->with('success', 'Warranty claim submitted successfully! We will review it within 24-48 hours.');
    }
}
