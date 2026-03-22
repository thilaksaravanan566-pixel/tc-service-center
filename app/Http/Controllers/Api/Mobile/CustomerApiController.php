<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SparePart;
use App\Models\CartItem;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use App\Models\WarrantyCertificate;
use App\Models\WarrantyClaim;
use App\Models\CustomerNotification;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class CustomerApiController extends Controller
{
    // ─── Auth ───

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var \App\Models\Customer|null $customer */
        $customer = Customer::query()->where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $customer->createToken('customer-mobile')->plainTextToken;

        return response()->json([
            'token'    => $token,
            'customer' => $customer->only(['id', 'name', 'email', 'mobile']),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:customers,email',
            'mobile'   => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        $token = $customer->createToken('customer-mobile')->plainTextToken;

        return response()->json([
            'token'    => $token,
            'customer' => $customer->only(['id', 'name', 'email', 'mobile']),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'mobile'   => 'sometimes|required|string|max:20',
            'address'  => 'sometimes|nullable|string|max:500',
        ]);

        $customer = $request->user();
        if ($request->has('name')) $customer->name = $request->name;
        if ($request->has('mobile')) $customer->mobile = $request->mobile;
        if ($request->has('address')) $customer->address = $request->address;

        $customer->save();

        return response()->json(['message' => 'Profile updated successfully.', 'customer' => $customer]);
    }

    // ─── Products ───

    public function products(Request $request)
    {
        $query = SparePart::where('is_active', true)->where('stock', '>', 0);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }

        return response()->json($query->orderBy('name')->paginate(20));
    }

    public function productShow($id)
    {
        $product = SparePart::where('is_active', true)->findOrFail($id);
        return response()->json($product);
    }

    // ─── Cart ───

    public function cart(Request $request)
    {
        $customer = $request->user();
        /** @var \App\Models\Cart $cart */
        $cart = \App\Models\Cart::firstOrCreate(['customer_id' => $customer->id]);
        $items    = CartItem::with('sparePart')->where('cart_id', $cart->id)->get();
        $total    = $items->sum(fn($i) => $i->quantity * $i->sparePart->price);
        return response()->json(['items' => $items, 'total' => $total]);
    }

    public function cartAdd(Request $request, $id)
    {
        $request->validate(['quantity' => 'nullable|integer|min:1']);
        $part = SparePart::findOrFail($id);

        /** @var \App\Models\Cart $cart */
        $cart = \App\Models\Cart::firstOrCreate(['customer_id' => $request->user()->id]);

        /** @var CartItem $item */
        $item = CartItem::firstOrNew([
            'cart_id'  => $cart->id,
            'spare_part_id'=> $id,
        ]);
        
        $newQuantity = ($item->quantity ?? 0) + ($request->quantity ?? 1);
        
        if ($part->stock < $newQuantity) {
            return response()->json(['message' => "Insufficient stock. Only {$part->stock} available."], 422);
        }

        $item->quantity = $newQuantity;
        $item->save();

        return response()->json(['message' => 'Added to cart.', 'item' => $item]);
    }

    public function cartRemove(Request $request, $id)
    {
        /** @var \App\Models\Cart $cart */
        $cart = \App\Models\Cart::firstOrCreate(['customer_id' => $request->user()->id]);
        CartItem::where('cart_id', $cart->id)->where('id', $id)->delete();
        return response()->json(['message' => 'Removed from cart.']);
    }

    // ─── Orders ───

    public function orders(Request $request)
    {
        $orders = ProductOrder::with('sparePart')
            ->where('customer_id', $request->user()->id)
            ->latest()->get();
        return response()->json($orders);
    }

    public function orderShow(Request $request, $id)
    {
        $order = ProductOrder::with('sparePart')
            ->where('customer_id', $request->user()->id)
            ->findOrFail($id);
        return response()->json($order);
    }

    public function checkout(Request $request)
    {
        $request->validate(['payment_method' => 'required|in:cod,upi,bank_transfer,card']);

        /** @var \App\Models\Cart $cart */
        $cart = \App\Models\Cart::firstOrCreate(['customer_id' => $request->user()->id]);
        $items = CartItem::with('sparePart')->where('cart_id', $cart->id)->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        $orders = [];
        foreach ($items as $item) {
            $part = $item->sparePart;
            if ($part->stock < $item->quantity) {
                return response()->json(['message' => "Insufficient stock for {$part->name}."], 422);
            }

            /** @var ProductOrder $order */
            $order = ProductOrder::create([
                'customer_id'    => $request->user()->id,
                'spare_part_id'  => $item->spare_part_id,
                'quantity'       => $item->quantity,
                'total_price'    => $item->quantity * $part->price,
                'payment_method' => $request->payment_method,
                'delivery_address' => $request->address,
                'status'         => 'pending',
            ]);
            $part->decrement('stock', $item->quantity);
            $orders[] = $order;
        }

        CartItem::where('cart_id', $cart->id)->delete();

        return response()->json(['message' => 'Order placed successfully!', 'orders' => $orders], 201);
    }

    // ─── Services ───

    public function services(Request $request)
    {
        $services = ServiceOrder::where('customer_id', $request->user()->id)
            ->with('device', 'technician')->latest()->get();
        return response()->json($services);
    }

    public function serviceShow(Request $request, $id)
    {
        $service = ServiceOrder::where('customer_id', $request->user()->id)
            ->with(['device', 'technician', 'billings'])->findOrFail($id);
        return response()->json($service);
    }

    public function bookService(Request $request)
    {
        $request->validate([
            'device_type'    => 'required|string',
            'brand'          => 'nullable|string',
            'model'          => 'nullable|string',
            'fault_details'  => 'required|string|min:10',
        ]);

        /** @var \App\Models\Device $device */
        $device = \App\Models\Device::create([
            'customer_id' => $request->user()->id,
            'type'        => $request->device_type,
            'brand'       => $request->brand ?? 'Unknown',
            'model'       => $request->model ?? 'N/A',
        ]);

        /** @var ServiceOrder $service */
        $service = ServiceOrder::create([
            'customer_id'   => $request->user()->id,
            'device_id'     => $device->id,
            'order_type'    => 'online',
            'fault_details' => $request->fault_details,
            'status'        => 'received',
            'tc_job_id'     => 'TC-' . strtoupper(uniqid()),
        ]);

        return response()->json(['message' => 'Service request submitted.', 'job_id' => $service->tc_job_id, 'service' => $service], 201);
    }

    // ─── Warranty ───

    public function warranty(Request $request)
    {
        $warranties = WarrantyCertificate::where('customer_id', $request->user()->id)
            ->with(['sparePart', 'serviceOrder'])->get();
        return response()->json($warranties);
    }

    public function submitClaim(Request $request, $id)
    {
        $request->validate(['issue_description' => 'required|string|min:10']);

        $warranty = WarrantyCertificate::where('customer_id', $request->user()->id)->findOrFail($id);

        if (now()->gt($warranty->expires_at)) {
            return response()->json(['message' => 'Warranty has expired.'], 422);
        }

        $claim = WarrantyClaim::create([
            'warranty_certificate_id' => $warranty->id,
            'customer_id'             => $request->user()->id,
            'issue_description'       => $request->issue_description,
            'status'                  => 'pending',
        ]);

        return response()->json(['message' => 'Warranty claim submitted.', 'claim' => $claim], 201);
    }

    // ─── Notifications ───

    public function notifications(Request $request)
    {
        $notifications = CustomerNotification::where('customer_id', $request->user()->id)
            ->latest()->take(30)->get();
        return response()->json($notifications);
    }

    public function markRead(Request $request)
    {
        CustomerNotification::where('customer_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json(['message' => 'All notifications marked as read.']);
    }

    // ─── Support / Chat ───

    public function supportMessages(Request $request)
    {
        $messages = SupportMessage::where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(50);
        return response()->json($messages);
    }

    public function sendSupportMessage(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $message = SupportMessage::create([
            'customer_id' => $request->user()->id,
            'sender_type' => 'customer',
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json(['message' => 'Message sent.', 'data' => $message], 201);
    }
}
