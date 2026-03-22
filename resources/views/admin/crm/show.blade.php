@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.crm.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $customer->name }}</h1>
            <p class="text-gray-400 text-sm">Customer 360° Profile · #{{ $customer->id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Customer Info --}}
        <div class="space-y-4">
            <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=ca8a04&color=fff&size=80" class="w-20 h-20 rounded-full mx-auto mb-3">
                <h2 class="text-white font-bold text-xl">{{ $customer->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $customer->mobile }}</p>
                <p class="text-gray-500 text-xs">{{ $customer->email }}</p>
                @if($customer->address)
                <p class="text-gray-500 text-xs mt-2">📍 {{ $customer->address }}</p>
                @endif
            </div>

            <div class="bg-white/5 border border-white/10 rounded-xl p-5">
                <h3 class="text-white font-semibold mb-3">Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm">Total Repairs</span>
                        <span class="text-white font-semibold">{{ $totalRepairs }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm">Total Spent</span>
                        <span class="text-emerald-400 font-semibold">₹{{ number_format($totalSpent, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm">Warranties</span>
                        <span class="text-yellow-400 font-semibold">{{ $customer->warranties->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm">Member Since</span>
                        <span class="text-gray-300 text-sm">{{ $customer->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Add Follow-up --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5">
                <h3 class="text-white font-semibold mb-3">Add Follow-up</h3>
                <form action="{{ route('admin.crm.followup', $customer->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <select name="type" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                        <option value="call">📞 Phone Call</option>
                        <option value="email">📧 Email</option>
                        <option value="visit">🏢 Visit</option>
                        <option value="sms">💬 SMS</option>
                    </select>
                    <textarea name="notes" rows="3" placeholder="Follow-up notes..." class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm resize-none" required minlength="5"></textarea>
                    <input type="datetime-local" name="followup_at" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white text-sm py-2 rounded-lg transition-all font-medium">
                        Add Note
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: History --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Service Orders --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5">
                <h3 class="text-white font-semibold mb-3">🔧 Repair History ({{ $customer->serviceOrders->count() }})</h3>
                @if(!empty($customer->serviceOrders)) @foreach($customer->serviceOrders as $order)
                <div class="flex items-center justify-between py-2 border-b border-white/5">
                    <div>
                        <p class="text-white text-sm font-medium">{{ $order->tc_job_id }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->device?->brand }} {{ $order->device?->model }} · {{ $order->fault_details }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-0.5 rounded text-xs bg-blue-500/20 text-blue-400">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                        <p class="text-gray-500 text-xs mt-1">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                @endforeach @else
                <p class="text-gray-500 text-sm text-center py-4">No repair history.</p>
                @endif
            </div>

            {{-- Product Orders --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5">
                <h3 class="text-white font-semibold mb-3">🛒 Purchase History ({{ $customer->productOrders->count() }})</h3>
                @if(!empty($customer->productOrders)) @foreach($customer->productOrders as $order)
                <div class="flex items-center justify-between py-2 border-b border-white/5">
                    <div>
                        <p class="text-white text-sm font-medium">{{ $order->sparePart?->name }}</p>
                        <p class="text-gray-500 text-xs">Qty: {{ $order->quantity }} · {{ $order->payment_method }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-emerald-400 font-semibold text-sm">₹{{ number_format($order->total_price, 0) }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                @endforeach @else
                <p class="text-gray-500 text-sm text-center py-4">No purchases yet.</p>
                @endif
            </div>

            {{-- Follow-up History --}}
            <div class="bg-white/5 border border-white/10 rounded-xl p-5">
                <h3 class="text-white font-semibold mb-3">📝 Follow-up Log ({{ $followups->count() }})</h3>
                @if(!empty($followups)) @foreach($followups as $followup)
                <div class="flex gap-3 py-3 border-b border-white/5">
                    <span class="text-xl">{{ match($followup->type) { 'call' => '📞', 'email' => '📧', 'visit' => '🏢', default => '💬' } }}</span>
                    <div class="flex-1">
                        <p class="text-white text-sm">{{ $followup->notes }}</p>
                        <p class="text-gray-500 text-xs mt-1">By {{ $followup->creator?->name ?? 'Admin' }} · {{ $followup->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endforeach @else
                <p class="text-gray-500 text-sm text-center py-4">No follow-ups logged yet.</p>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
