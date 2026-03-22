<div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl p-8 border border-slate-100">
    <h2 class="text-2xl font-bold text-slate-800 mb-6">Service Summary</h2>
    
    <div class="bg-slate-50 p-4 rounded-xl mb-6">
        <p class="text-xs text-slate-500 uppercase">Device Configuration</p>
        <p class="text-sm font-bold">{{ $order->device->processor }} | {{ $order->device->ram_old }}</p>
    </div>

    <div class="flex justify-between mb-4">
        <span>Service Fee</span>
        <span class="font-bold">₹{{ number_format($order->total_amount, 2) }}</span>
    </div>

    <form action="{{ route('admin.payments.process', $order->id) }}" method="POST">
        @csrf
        <input type="hidden" name="amount" value="{{ $order->total_amount }}">
        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-gold-600 transition-colors">
            COMPLETE PAYMENT
        </button>
    </form>
</div>