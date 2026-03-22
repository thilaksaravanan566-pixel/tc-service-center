<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('serviceOrder.customer')->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function process(Request $request, $orderId)
    {
        $order = ServiceOrder::findOrFail($orderId);
        
        // Logic to record TC Service payment
        $order->update(['status' => 'delivered']);
        
        return back()->with('success', 'Payment processed successfully.');
    }
}