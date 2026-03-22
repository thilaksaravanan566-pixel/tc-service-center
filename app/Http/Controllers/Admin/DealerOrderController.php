<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerOrder;
use App\Models\DealerInventory;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

use App\Models\Invoice;
use App\Models\InvoiceItem;

class DealerOrderController extends Controller
{
    public function index()
    {
        $orders = DealerOrder::with('dealer')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(DealerOrder $order)
    {
        $order->load(['dealer', 'items.product', 'shipment']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, DealerOrder $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,packed,shipped,delivered,rejected',
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;

        DB::transaction(function () use ($order, $oldStatus) {
            $order->save();

            // When APPROVED, generate B2B Invoice
            if ($order->status === 'approved' && $oldStatus === 'pending') {
                $invoice = Invoice::create([
                    'invoice_number' => 'B2B-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'dealer_id' => $order->dealer_id,
                    'customer_name' => $order->dealer->business_name,
                    'phone' => $order->dealer->user->mobile ?? '--',
                    'email' => $order->dealer->user->email ?? '--',
                    'address' => $order->dealer->address ?? '--',
                    'total' => $order->total_amount,
                    'status' => 'pending',
                    'dealer_order_id' => $order->id,
                ]);

                foreach ($order->items as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price_per_unit,
                        'total' => $item->subtotal,
                    ]);
                }
            }

            // When DELIVERED, add stock
            if ($order->status === 'delivered' && $oldStatus !== 'delivered') {
                $order->update(['payment_status' => 'paid']);
                
                foreach ($order->items as $item) {
                    $inventory = DealerInventory::firstOrCreate(
                        ['dealer_id' => $order->dealer_id, 'product_id' => $item->product_id],
                        ['stock_quantity' => 0]
                    );

                    $previousStock = $inventory->stock_quantity;
                    $inventory->increment('stock_quantity', $item->quantity);

                    InventoryLog::create([
                        'dealer_id' => $order->dealer_id,
                        'product_id' => $item->product_id,
                        'type' => 'IN',
                        'quantity' => $item->quantity,
                        'reference_type' => 'dealer_order',
                        'reference_id' => $order->id,
                        'previous_stock' => $previousStock,
                        'new_stock' => $inventory->stock_quantity,
                        'description' => 'Stock added via Dealer Order #' . $order->order_number,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
