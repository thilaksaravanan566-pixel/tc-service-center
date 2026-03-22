<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarrantyCertificate;
use App\Models\WarrantyClaim;
use App\Models\Customer;
use App\Models\SparePart;
use App\Models\ServiceOrder;

class WarrantyTicketController extends Controller
{
    /**
     * List all warranty claims.
     */
    public function index()
    {
        $claims = WarrantyClaim::with(['customer', 'certificate.sparePart', 'handler'])
            ->latest()
            ->paginate(20);

        $stats = [
            'pending'   => WarrantyClaim::where('status', 'pending')->count(),
            'reviewing' => WarrantyClaim::where('status', 'reviewing')->count(),
            'approved'  => WarrantyClaim::where('status', 'approved')->count(),
            'rejected'  => WarrantyClaim::where('status', 'rejected')->count(),
        ];

        return view('admin.warranty.claims', compact('claims', 'stats'));
    }

    /**
     * Show a single warranty claim.
     */
    public function show($id)
    {
        $claim = WarrantyClaim::with(['customer', 'certificate.sparePart', 'certificate.serviceOrder', 'handler'])
            ->findOrFail($id);

        return view('admin.warranty.claim-show', compact('claim'));
    }

    /**
     * Update warranty claim status.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:reviewing,approved,rejected,resolved',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $claim = WarrantyClaim::findOrFail($id);
        $claim->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
            'handled_by'  => auth()->id(),
            'resolved_at' => in_array($request->status, ['approved', 'rejected', 'resolved']) ? now() : null,
        ]);

        // Notify customer
        $statusMsg = match($request->status) {
            'approved'  => '✅ Your warranty claim has been APPROVED.',
            'rejected'  => '❌ Your warranty claim has been rejected.',
            'resolved'  => '✅ Your warranty claim has been fully resolved.',
            'reviewing' => '🔍 Your warranty claim is now under review.',
            default     => "Your warranty claim status has been updated to: {$request->status}.",
        };

        \App\Models\CustomerNotification::create([
            'customer_id' => $claim->customer_id,
            'type'        => 'warranty',
            'title'       => '🛡️ Warranty Claim Update',
            'message'     => $statusMsg,
            'action_url'  => route('customer.warranty.show', $claim->warranty_certificate_id),
            'icon'        => '🛡️',
        ]);

        return redirect()->route('admin.warranty.claims')->with('success', 'Warranty claim updated and customer notified.');
    }

    /**
     * List all warranty certificates.
     */
    public function certificates()
    {
        $certificates = WarrantyCertificate::with(['customer', 'sparePart', 'serviceOrder', 'claims'])
            ->latest()
            ->paginate(25);

        $customers    = Customer::orderBy('name')->get(['id', 'name', 'email']);
        $spareParts   = SparePart::where('is_active', true)->orderBy('name')->get(['id', 'name', 'brand', 'warranty_months']);
        $serviceOrders = ServiceOrder::select('id', 'tc_job_id', 'customer_id')->latest()->take(200)->get();

        return view('admin.warranty.certificates', compact('certificates', 'customers', 'spareParts', 'serviceOrders'));
    }

    /**
     * Store a new warranty certificate (admin creates manually).
     */
    public function storeCertificate(Request $request)
    {
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'warranty_type'   => 'required|in:product,service',
            'spare_part_id'   => 'required_if:warranty_type,product|nullable|exists:spare_parts,id',
            'service_order_id'=> 'required_if:warranty_type,service|nullable|exists:service_orders,id',
            'serial_number'   => 'nullable|string|max:100',
            'purchase_date'   => 'required|date',
            'warranty_start'  => 'required|date',
            'warranty_end'    => 'required|date|after:warranty_start',
            'notes'           => 'nullable|string|max:1000',
        ]);

        /** @var WarrantyCertificate $certificate */
        $certificate = WarrantyCertificate::create($request->only([
            'customer_id', 'warranty_type', 'spare_part_id', 'service_order_id',
            'serial_number', 'purchase_date', 'warranty_start', 'warranty_end', 'notes'
        ]));

        // Notify customer
        \App\Models\CustomerNotification::create([
            'customer_id' => $certificate->customer_id,
            'type'        => 'warranty',
            'title'       => '🛡️ Warranty Certificate Issued',
            'message'     => 'A warranty certificate has been issued for your purchase. View details in your warranty panel.',
            'action_url'  => route('customer.warranty.show', $certificate->id),
            'icon'        => '🛡️',
        ]);

        return redirect()->route('admin.warranty.certificates')
            ->with('success', 'Warranty certificate created and customer notified.');
    }
}
