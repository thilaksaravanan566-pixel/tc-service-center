<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->with(['customer', 'serviceOrder', 'dealerOrder'])->latest();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('customer_gst', 'like', "%{$search}%");
            });
        }

        $invoices = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total_revenue' => Invoice::where('payment_status', 'paid')->sum('total'),
            'pending_payment' => Invoice::where('payment_status', '!=', 'paid')->sum('total'),
            'dealer_revenue' => Invoice::where('billing_type', 'dealer')->where('payment_status', 'paid')->sum('total'),
            'retail_revenue' => Invoice::where('billing_type', '!=', 'dealer')->where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function create(Request $request)
    {
        $serviceOrderId = $request->query('service_order_id');
        $order = null;
        
        if ($serviceOrderId) {
            $order = ServiceOrder::with(['customer', 'device', 'technician'])->findOrFail($serviceOrderId);
            
            // Check if invoice already exists
            $existingInvoice = Invoice::where('service_order_id', $serviceOrderId)->first();
            if ($existingInvoice) {
                return redirect()->route('admin.invoices.edit', $existingInvoice->id);
            }
        }

        $estimateCount = Invoice::where('bill_type', 'estimation')->whereYear('created_at', date('Y'))->count();
        $gstCount = Invoice::where('bill_type', 'gst')->whereYear('created_at', date('Y'))->count();

        $nextEstimateNumber = 'EST-' . str_pad($estimateCount + 1, 3, '0', STR_PAD_LEFT);
        $nextGstNumber = 'INV-' . str_pad($gstCount + 1, 3, '0', STR_PAD_LEFT);
        
        $company = \App\Models\CompanyProfile::first();

        return view('admin.invoices.create', compact('order', 'nextEstimateNumber', 'nextGstNumber', 'company'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number'   => 'required|unique:invoices,invoice_number',
            'customer_id'      => 'nullable|exists:customers,id',
            'customer_name'    => 'required|string',
            'phone'            => 'required|string',
            'subtotal'         => 'required|numeric',
            'gst_percentage'   => 'required|numeric',
            'gst_amount'       => 'required|numeric',
            'total'            => 'required|numeric',
            'bill_type'        => 'required|in:estimation,gst',
            'valid_until'      => 'nullable|date',
            'customer_gst'     => ['nullable', 'string', 'size:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i'],
            'billing_type'     => 'required|in:dealer,online,walkin',
            'payment_status'   => 'required|in:unpaid,paid,partial',
            'items'            => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity'  => 'required|numeric|min:0.01',
            'items.*.price'     => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoiceData = $request->only([
                'invoice_number', 'customer_id', 'customer_name', 'phone', 'email', 'address',
                'service_order_id', 'device_name', 'technician', 'billing_type', 
                'subtotal', 'gst_percentage', 'gst_amount', 'discount', 'total', 
                'payment_status', 'payment_method', 'notes', 'bill_type', 'valid_until', 'customer_gst'
            ]);

            $company = \App\Models\CompanyProfile::first();
            $companyState = $company && $company->gst_number ? substr($company->gst_number, 0, 2) : '33'; // Default TN
            
            if ($request->bill_type === 'gst' && $request->gst_amount > 0) {
                if ($request->customer_gst) {
                    $invoiceData['state_code'] = substr($request->customer_gst, 0, 2);
                    $isLocal = $invoiceData['state_code'] === $companyState;
                } else {
                    $isLocal = true; // B2C local default
                }

                if ($isLocal) {
                    $invoiceData['cgst_amount'] = $request->gst_amount / 2;
                    $invoiceData['sgst_amount'] = $request->gst_amount / 2;
                    $invoiceData['igst_amount'] = 0;
                } else {
                    $invoiceData['cgst_amount'] = 0;
                    $invoiceData['sgst_amount'] = 0;
                    $invoiceData['igst_amount'] = $request->gst_amount;
                }
            } else {
                $invoiceData['cgst_amount'] = 0;
                $invoiceData['sgst_amount'] = 0;
                $invoiceData['igst_amount'] = 0;
            }

            if ($request->customer_gst && $request->customer_id) {
                \App\Models\Customer::where('id', $request->customer_id)->update(['gst_number' => $request->customer_gst]);
            }

            // If it's a dealer order, ensure we link the dealer
            if ($request->service_order_id) {
                $order = ServiceOrder::find($request->service_order_id);
                if ($order && $order->dealer_id) {
                    $invoiceData['dealer_id'] = $order->dealer_id;
                }
            }

            $invoice = Invoice::create($invoiceData);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            // Update service order status or is_paid if needed
            if ($invoice->service_order_id) {
                $order = ServiceOrder::find($invoice->service_order_id);
                $order->update(['is_paid' => true]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice generated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->bill_type === 'gst') {
            return back()->with('error', 'Tax invoices are locked and cannot be edited.');
        }

        $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'bill_type' => 'required|in:estimation,gst',
            'valid_until' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update($request->only([
                'customer_name', 'phone', 'email', 'address',
                'device_name', 'technician', 'subtotal', 'tax', 'discount', 'total',
                'bill_type', 'valid_until'
            ]));

            $invoice->items()->delete();
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    public function download(Invoice $invoice)
    {
        $invoice->load(['items', 'customer']);
        
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('Invoice_' . $invoice->invoice_number . '.pdf');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'serviceOrder']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function print(Request $request, Invoice $invoice)
    {
        $invoice->load(['items', 'customer', 'serviceOrder']);
        $settings = \App\Models\InvoiceSetting::first() ?? new \App\Models\InvoiceSetting();
        $company = \App\Models\CompanyProfile::first() ?? new \App\Models\CompanyProfile();
        
        $template = $invoice->bill_type === 'estimation' ? 'estimation' : 'gst_invoice';
        
        // Ensure the view exists, fallback to standard
        if (!view()->exists("admin.invoices.print.{$template}")) {
            $template = 'standard';
        }

        return view("admin.invoices.print.{$template}", compact('invoice', 'settings', 'company'));
    }

    public function convert(Invoice $invoice)
    {
        if ($invoice->bill_type !== 'estimation') {
            return back()->with('error', 'Only estimations can be converted to tax invoices.');
        }

        $gstCount = Invoice::where('bill_type', 'gst')->whereYear('created_at', date('Y'))->count();
        $nextGstNumber = 'INV-' . str_pad($gstCount + 1, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $newInvoice = $invoice->replicate();
            $newInvoice->invoice_number = $nextGstNumber;
            $newInvoice->bill_type = 'gst';
            $newInvoice->parent_estimate_id = $invoice->id;
            $newInvoice->payment_status = 'unpaid';
            $newInvoice->created_at = now();
            $newInvoice->updated_at = now();
            $newInvoice->save();

            foreach ($invoice->items as $item) {
                $newItem = $item->replicate();
                $newItem->invoice_id = $newInvoice->id;
                $newItem->save();
            }

            // Lock old estimation conceptually by updating notes/status or leave as is since we check parent_estimate_id
            $invoice->update(['payment_status' => 'converted']); 
            
            DB::commit();
            return redirect()->route('admin.invoices.show', $newInvoice->id)->with('success', 'Estimation successfully converted to GST Invoice.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to convert estimation: ' . $e->getMessage());
        }
    }
}