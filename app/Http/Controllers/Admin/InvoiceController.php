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
    public function index()
    {
        $invoices = Invoice::with(['customer', 'serviceOrder', 'dealerOrder'])->latest()->paginate(15);
        
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

        $nextNumber = 'TC-' . date('Y') . '-' . str_pad((Invoice::whereYear('created_at', date('Y'))->count() + 1), 4, '0', STR_PAD_LEFT);

        return view('admin.invoices.create', compact('order', 'nextNumber'));
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
                'payment_status', 'payment_method', 'notes'
            ]);

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
        $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update($request->only([
                'customer_name', 'phone', 'email', 'address',
                'device_name', 'technician', 'subtotal', 'tax', 'discount', 'total'
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
}