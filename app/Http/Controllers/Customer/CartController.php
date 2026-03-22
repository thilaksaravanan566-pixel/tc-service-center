<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\SparePart;
use App\Models\ProductOrder;

class CartController extends Controller
{
    /** @return \App\Models\Customer */
    private function customer()
    {
        /** @var \App\Models\Customer $user */
        $user = Auth::guard('customer')->user();
        return $user;
    }

    /** @return \App\Models\Cart */
    private function getCart()
    {
        /** @var \App\Models\Cart $cart */
        $cart = \App\Models\Cart::firstOrCreate(['customer_id' => $this->customer()->id]);
        return $cart;
    }

    /**
     * Show the shopping cart page.
     */
    public function index()
    {
        $cart = $this->getCart();
        $items = CartItem::with('sparePart')
            ->where('cart_id', $cart->id)
            ->get();

        $subtotal = $items->sum(fn($item) => $item->quantity * ($item->sparePart?->price ?? 0));

        return view('customer.cart.index', compact('items', 'subtotal'));
    }

    /**
     * Add a spare part to cart (or increment quantity).
     */
    public function add(Request $request, $id)
    {
        $request->validate(['quantity' => 'sometimes|integer|min:1|max:99']);

        $part = SparePart::findOrFail($id);

        if ($part->stock <= 0) {
            return back()->with('error', 'This product is currently out of stock.');
        }

        $qty = $request->input('quantity', 1);
        $cart = $this->getCart();

        /** @var \App\Models\CartItem|null $item */
        $item = CartItem::where('cart_id', $cart->id)
            ->where('spare_part_id', $part->id)
            ->first();

        if ($item) {
            $newQty = min($item->quantity + $qty, $part->stock);
            $item->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'cart_id'       => $cart->id,
                'spare_part_id' => $part->id,
                'quantity'      => min($qty, $part->stock),
            ]);
        }

        return back()->with('success', "'{$part->name}' added to cart!");
    }

    /**
     * Update a cart item's quantity via AJAX or form.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cart = $this->getCart();
        $item = CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->firstOrFail();

        $maxQty = $item->sparePart->stock;

        $item->update(['quantity' => min($request->quantity, $maxQty)]);

        if ($request->ajax()) {
            $subtotal = CartItem::with('sparePart')
                ->where('cart_id', $cart->id)
                ->get()
                ->sum(fn($i) => $i->quantity * ($i->sparePart?->price ?? 0));

            return response()->json([
                'success'  => true,
                'subtotal' => '₹' . number_format($subtotal, 2),
                'itemTotal' => '₹' . number_format($item->quantity * $item->sparePart->price, 2),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove an item from cart.
     */
    public function remove($id)
    {
        $cart = $this->getCart();
        CartItem::where('id', $id)
            ->where('cart_id', $cart->id)
            ->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Show the checkout page.
     */
    public function checkout()
    {
        $cart = $this->getCart();
        $items = CartItem::with('sparePart')
            ->where('cart_id', $cart->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty. Add items before checking out.');
        }

        $subtotal  = $items->sum(fn($item) => $item->quantity * ($item->sparePart?->price ?? 0));
        $shipping  = 0; // free shipping
        $total     = $subtotal + $shipping;

        // Fetch user addresses
        $addresses = \App\Models\Address::where('customer_id', $this->customer()->id)->get();

        return view('customer.cart.checkout', compact('items', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    /**
     * Place the order from cart items.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method'         => 'required|in:cod,upi',
            'delivery_type'          => 'required|in:delivery,take_away',
            'address_id'             => 'required_if:delivery_type,delivery|nullable|exists:addresses,id',
            'delivery_mobile'        => 'required_if:delivery_type,delivery|nullable|string',
            'delivery_location_url'  => 'nullable|string',
            'new_address_line'       => 'required_if:address_id,new|nullable|string',
            'new_city'               => 'required_if:address_id,new|nullable|string',
            'new_state'              => 'required_if:address_id,new|nullable|string',
            'new_postal_code'        => 'required_if:address_id,new|nullable|string',
        ]);

        $customer = $this->customer();
        $cart = $this->getCart();

        $items = CartItem::with('sparePart')
            ->where('cart_id', $cart->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        foreach ($items as $item) {
            if (!$item->sparePart || $item->sparePart->stock < $item->quantity) {
                return back()->with('error', "'{$item->sparePart?->name}' does not have enough stock.");
            }
        }

        // Deal with address
        $delivery_address_str = null;
        if ($request->delivery_type === 'delivery') {
            if ($request->address_id === 'new') {
                /** @var \App\Models\Address $address */
                $address = \App\Models\Address::create([
                    'customer_id'  => $customer->id,
                    'name'         => $customer->name,
                    'phone'        => $request->delivery_mobile,
                    'address_line' => $request->new_address_line,
                    'city'         => $request->new_city,
                    'state'        => $request->new_state,
                    'postal_code'  => $request->new_postal_code,
                    'is_default'   => true,
                ]);
                $delivery_address_str = "{$address->address_line}, {$address->city}, {$address->state} - {$address->postal_code}";
            } else {
                /** @var \App\Models\Address $address */
                $address = \App\Models\Address::findOrFail($request->address_id);
                $delivery_address_str = "{$address->address_line}, {$address->city}, {$address->state} - {$address->postal_code}";
            }
        }

        // Create one ProductOrder per item
        $orders = [];
        foreach ($items as $item) {
            /** @var ProductOrder $order */
            $order = ProductOrder::create([
                'customer_id'           => $customer->id,
                'spare_part_id'         => $item->spare_part_id,
                'quantity'              => $item->quantity,
                'total_price'           => $item->quantity * $item->sparePart->price,
                'status'                => 'pending',
                'payment_method'        => $request->payment_method,
                'is_paid'               => false,
                'delivery_type'         => $request->delivery_type,
                'delivery_address'      => $delivery_address_str,
                'delivery_mobile'       => $request->delivery_mobile,
                'delivery_location_url' => $request->delivery_location_url,
            ]);

            $item->sparePart->decrement('stock', $item->quantity);
            $orders[] = $order;
        }

        CartItem::where('cart_id', $cart->id)->delete();

        \App\Models\CustomerNotification::create([
            'customer_id' => $customer->id,
            'type'        => 'order_update',
            'title'       => '🛒 Order Placed Successfully!',
            'message'     => count($orders) . ' item(s) ordered. We will confirm your order shortly.',
            'action_url'  => route('customer.orders.index'),
            'icon'        => '🛒',
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', '✅ Order placed successfully! ' . count($orders) . ' item(s) are being prepared.');
    }
}
