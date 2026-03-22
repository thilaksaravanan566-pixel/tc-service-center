<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\ProductOrder;
use App\Models\WarrantyCertificate;
use App\Models\CartItem;
use App\Models\CustomerNotification;
use App\Models\Offer;

class DashboardController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    public function index()
    {
        $customer = $this->customer();

        // Service orders
        $serviceOrders = ServiceOrder::with('device')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        // Backward compat alias
        $orders = $serviceOrders;

        // Product orders
        $productOrders = ProductOrder::with('sparePart')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        // Warranties
        $warranties = WarrantyCertificate::with('sparePart')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        // Cart count
        $cartCount = $customer->cart ? $customer->cart->items()->sum('quantity') : 0;

        // Offers
        $offers = Offer::where('is_active', true)->latest()->get();

        // Recent notifications (last 10)
        $notifications = CustomerNotification::where('customer_id', $customer->id)
            ->latest()
            ->take(10)
            ->get();

        // Recent orders (last 5)
        $recentOrders = $orders->take(5);

        return view('customer.dashboard', compact(
            'customer',
            'orders',
            'recentOrders',
            'serviceOrders',
            'productOrders',
            'warranties',
            'cartCount',
            'offers',
            'notifications'
        ));
    }

    /**
     * Mark all notifications as read (AJAX).
     */
    public function markNotificationsRead(Request $request)
    {
        CustomerNotification::where('customer_id', $this->customer()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Download invoice for a service order.
     */
    public function downloadInvoice($serviceOrderId)
    {
        $customer = $this->customer();
        $order = ServiceOrder::with(['customer', 'device'])
            ->where('customer_id', $customer->id)
            ->findOrFail($serviceOrderId);

        // check if a formal invoice exists
        $invoice = \App\Models\Invoice::where('service_order_id', $order->id)->latest()->first();

        if ($invoice) {
            // If a formal invoice exists, use the professional customer invoice view
            $html = view('customer.invoice', [
                'order' => $order,
                'invoice' => $invoice
            ])->render();
            $filename = "Invoice-{$invoice->invoice_number}.html";
        } else {
            // Fallback for orders without formal invoices
            $html = view('customer.invoice', compact('order'))->render();
            $filename = "Order-Details-{$order->tc_job_id}.html";
        }

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}
