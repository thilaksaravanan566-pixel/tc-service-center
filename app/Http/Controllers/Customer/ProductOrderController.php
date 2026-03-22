<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SparePart;
use App\Models\ProductOrder;

class ProductOrderController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    /**
     * List all product orders for the customer.
     */
    public function index()
    {
        $orders = ProductOrder::with('sparePart')
            ->where('customer_id', $this->customer()->id)
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Amazon-style order tracking timeline.
     */
    public function track($id)
    {
        $order = ProductOrder::with(['sparePart', 'deliveryPartner'])
            ->where('customer_id', $this->customer()->id)
            ->findOrFail($id);

        // Build timeline
        $allStatuses = ['pending', 'confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($order->status, $allStatuses);

        $timeline = array_map(function ($status, $index) use ($currentIndex) {
            return [
                'status'    => $status,
                'label'     => ucwords(str_replace('_', ' ', $status)),
                'done'      => $index <= $currentIndex,
                'active'    => $index === $currentIndex,
                'icon'      => match($status) {
                    'pending'          => '🕐',
                    'confirmed'        => '✅',
                    'packed'           => '📦',
                    'shipped'          => '🚚',
                    'out_for_delivery' => '🛵',
                    'delivered'        => '🎉',
                    default            => '⭕',
                },
            ];
        }, $allStatuses, array_keys($allStatuses));

        return view('customer.orders.track', compact('order', 'timeline'));
    }

    /**
     * Quick-buy single item (legacy — from shop show page).
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'quantity'       => 'required|integer|min:1',
            'payment_method' => 'required|in:cod,upi',
            'delivery_type'  => 'required|in:delivery,take_away',
        ]);

        $part     = SparePart::findOrFail($id);
        $customer = $this->customer();

        if ($part->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $totalPrice = $part->price * $request->quantity;

        /** @var ProductOrder $order */
        $order = ProductOrder::create([
            'customer_id'    => $customer->id,
            'spare_part_id'  => $part->id,
            'quantity'       => $request->quantity,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
            'payment_method' => $request->payment_method,
            'is_paid'        => false,
            'delivery_type'  => $request->delivery_type,
        ]);

        // Deduct inventory stock
        $part->decrement('stock', $request->quantity);

        // Notify
        \App\Models\CustomerNotification::create([
            'customer_id' => $customer->id,
            'type'        => 'order_update',
            'title'       => '🛒 Order Placed!',
            'message'     => "Your order for '{$part->name}' has been received. We will confirm it shortly.",
            'action_url'  => route('customer.orders.track', $order->id),
            'icon'        => '🛒',
        ]);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Order placed successfully! You can track it from your dashboard.');
    }
}
