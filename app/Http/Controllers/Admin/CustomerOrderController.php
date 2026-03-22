<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $orders = ProductOrder::with(['customer', 'sparePart', 'deliveryPartner'])->latest()->get();
        // Fetch users with the delivery_partner role
        $deliveryPartners = \App\Models\User::where('role', 'delivery_partner')->orWhere('role', 'delivery')->get(); 
        return view('admin.orders.index', compact('orders', 'deliveryPartners'));
    }

    public function update(Request $request, $id)
    {
        $order = ProductOrder::findOrFail($id);

        $request->validate([
            'status' => 'nullable|string',
            'is_paid' => 'nullable|boolean',
            'delivery_partner_id' => 'nullable|exists:users,id',
        ]);

        $order->update($request->only('status', 'is_paid', 'delivery_partner_id'));
        return back()->with('success', 'Order updated successfully!');
    }
}
