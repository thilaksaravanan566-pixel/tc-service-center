<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeliveryApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var \App\Models\User|null $user */
        $user = User::query()->where('email', $request->email)
            ->where('role', 'delivery_partner')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials or not a delivery partner.'], 401);
        }

        $token = $user->createToken('delivery-mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user->only(['id', 'name', 'email', 'role']),
        ]);
    }

    public function assignedTasks(Request $request)
    {
        // 1. E-Commerce Product Orders
        $productOrders = ProductOrder::with(['customer', 'sparePart'])
            ->where('delivery_partner_id', $request->user()->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->latest()->get();

        // 2. Repair Service Orders (Logistics required for pickup or drop-off)
        $serviceOrders = ServiceOrder::with(['customer', 'device'])
            ->where('delivery_partner_id', $request->user()->id)
            ->whereNotIn('status', ['completed', 'cancelled', 'returned_to_customer'])
            ->latest()->get();

        return response()->json([
            'products' => $productOrders,
            'services' => $serviceOrders,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'type'   => 'required|in:product,service',
            'status' => 'required|in:pending,out_for_delivery,delivered,failed,picked_up,in_transit',
        ]);

        if ($request->type === 'product') {
            $order = ProductOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update(['status' => $request->status]);
        } else {
            $order = ServiceOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update(['status' => $request->status]);
        }

        return response()->json(['message' => 'Status updated.', 'status' => $request->status]);
    }

    public function markPickedUp(Request $request, $id)
    {
        $request->validate(['type' => 'required|in:product,service']);

        if ($request->type === 'product') {
            $order = ProductOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update(['status' => 'out_for_delivery', 'picked_up_at' => now()]);
        } else {
            $order = ServiceOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update(['status' => 'in_progress', 'picked_up_at' => now()]);
        }

        return response()->json(['message' => "Order #{$id} picked up."]);
    }

    public function markDelivered(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:product,service',
            'otp'  => 'required|string'
        ]);

        if ($request->type === 'product') {
            $order = ProductOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
                'is_paid'      => $order->payment_method === 'cod' ? true : $order->is_paid,
            ]);
        } else {
            $order = ServiceOrder::where('delivery_partner_id', $request->user()->id)->findOrFail($id);
            $order->update([
                'status'       => 'returned_to_customer', // Or delivered to customer post-repair
                'delivered_at' => now(), // Assumed custom field or generic flow
            ]);
        }
        
        return response()->json(['message' => 'Delivery confirmed successfully!']);
    }
}
