<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::latest()->get();
        return view('admin.purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.purchase_orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'order_date' => 'nullable|date',
        ]);

        PurchaseOrder::create($request->all());

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase order created successfully.');
    }

    public function edit(PurchaseOrder $purchase_order)
    {
        return view('admin.purchase_orders.edit', compact('purchase_order'));
    }

    public function update(Request $request, PurchaseOrder $purchase_order)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'order_date' => 'nullable|date',
        ]);

        $purchase_order->update($request->all());

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchase_order)
    {
        $purchase_order->delete();
        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
