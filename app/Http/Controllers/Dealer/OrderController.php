<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DealerOrder;
use App\Models\DealerOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')->paginate(12);
        return view('dealer.orders.create', compact('products'));
    }

    public function history()
    {
        $orders = DealerOrder::where('dealer_id', Auth::user()->dealer->id)->latest()->paginate(10);
        return view('dealer.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $dealer = Auth::user()->dealer;

        return DB::transaction(function () use ($request, $dealer) {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['id']);
                $subtotal = $product->dealer_price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price_per_unit' => $product->dealer_price,
                    'subtotal' => $subtotal,
                ];
            }

            $order = DealerOrder::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'dealer_id' => $dealer->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_date' => now(),
            ]);

            foreach ($itemsToCreate as $item) {
                $item['dealer_order_id'] = $order->id;
                DealerOrderItem::create($item);
            }

            return redirect()->route('dealer.orders.history')->with('success', 'Order placed successfully! Job ID: ' . $order->order_number);
        });
    }

    public function show(DealerOrder $order)
    {
        if ($order->dealer_id !== Auth::user()->dealer->id) {
            abort(403);
        }
        $order->load(['items.product', 'shipment']);
        return view('dealer.orders.show', compact('order'));
    }
}
