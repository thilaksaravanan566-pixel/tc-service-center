@extends('layouts.admin')

@section('title', 'Review Dealer Order')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Purchase Request Inspections</h1>
        <p class="text-gray-500 mt-2 font-medium">Order Reference: <span class="text-indigo-600 font-mono">{{ $order->order_number }}</span></p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary border border-gray-200">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        @if($order->status === 'pending')
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="btn btn-primary bg-indigo-600 shadow-lg shadow-indigo-600/20">
                <i class="fas fa-check-circle mr-2"></i> Approve Purchase
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-0 overflow-hidden shadow-sm">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xs uppercase font-bold text-gray-500 tracking-widest">Inventory Allocation Breakdown</h3>
                <span class="text-[10px] px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full font-bold">ITEMIZED LIST</span>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Product Catalog Item</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dealer Price</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Qty Request</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Sub-Valuation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-800">{{ $item->product->name }}</p>
                            <p class="text-[10px] text-gray-400 font-mono italic">{{ $item->product->sku }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">₹{{ number_format($item->price_per_unit, 2) }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-center bg-gray-50/30">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-sm font-extrabold text-blue-900 text-right">₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-6 bg-gray-900 text-white flex justify-between items-center shadow-inner">
                <p class="text-xs uppercase font-bold text-gray-400 border-l border-white/10 pl-4">Total Contract Valuation</p>
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 font-medium">Incl. Dealer Partner Discs.</p>
                    <p class="text-3xl font-extrabold tracking-tighter text-blue-400">₹{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
        </div>

        @if($order->shipment)
        <div class="card p-6 border-l-4 border-indigo-500 bg-indigo-50/30 shadow-sm relative overflow-hidden group transition hover:shadow-md cursor-default">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition duration-700"></div>
            <div class="relative">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 shrink-0">
                        <i class="fas fa-truck-loading text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900 tracking-tight">Active Shipment Log</h3>
                        <p class="text-xs text-indigo-700/60 font-medium italic">Method: {{ strtoupper($order->shipment->method) }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @if($order->shipment->method === 'courier')
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Carrier Agency</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->shipment->courier_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Ref Number</p>
                        <p class="text-sm font-bold text-indigo-600 font-mono tracking-tighter">{{ $order->shipment->tracking_number }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Transit Bus</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->shipment->bus_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">LR Tracking</p>
                        <p class="text-sm font-bold text-indigo-600 font-mono tracking-tighter">{{ $order->shipment->lr_number }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Stage</p>
                        <span class="badge badge-indigo text-[9px] px-2 py-0.5 uppercase">{{ $order->shipment->status }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400 mb-1">Dispatched</p>
                        <p class="text-sm font-bold text-gray-900">{{ $order->shipment->dispatch_at->format('d M') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6 shadow-sm border border-gray-100">
            <h3 class="text-xs uppercase font-bold text-gray-500 tracking-widest border-b border-gray-100 pb-4 mb-4">Partner Entity Profiles</h3>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-black shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition cursor-default">
                    {{ substr($order->dealer->business_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900 leading-none">{{ $order->dealer->business_name }}</h4>
                    <p class="text-sm font-medium text-indigo-600 mt-2">{{ $order->dealer->user->name }}</p>
                    <p class="text-xs text-gray-400 mt-1 font-mono tracking-tighter">{{ $order->dealer->user->email }}</p>
                </div>
            </div>
            
            <div class="space-y-4 pt-4 border-t border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 shrink-0"><i class="fas fa-phone-alt text-xs"></i></div>
                    <p class="text-xs font-bold text-gray-700 tracking-tight">{{ $order->dealer->phone }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 shrink-0"><i class="fas fa-map-marker-alt text-xs"></i></div>
                    <p class="text-xs font-medium text-gray-500 leading-relaxed">{{ $order->dealer->address }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0"><i class="fas fa-file-invoice-dollar text-xs"></i></div>
                    <p class="text-xs font-bold text-emerald-600">GST: <span class="bg-white px-2 py-0.5 rounded border border-emerald-100 shadow-sm">{{ $order->dealer->gst_number ?? 'UNREGISTERED' }}</span></p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-indigo-900 to-gray-900 text-white border-0 shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-150 transition duration-1000"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></span>
                    <h3 class="text-xs uppercase font-bold tracking-widest text-indigo-300">Operational Summary</h3>
                </div>
                <div class="space-y-5">
                    <div class="flex justify-between items-center pb-3 border-b border-indigo-800/50">
                        <span class="text-xs font-bold text-indigo-400 uppercase">Process Phase</span>
                        <span class="text-sm font-black uppercase text-indigo-100 italic tracking-widest">{{ $order->status }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-indigo-800/50">
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-tighter">Financial Settlement</span>
                        <span class="text-sm font-black uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-400' : 'text-red-400' }} tracking-widest bg-black/40 px-3 py-1 rounded-lg border border-white/5">{{ $order->payment_status }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-indigo-400 uppercase">Ledger Date</span>
                        <span class="text-sm font-black text-indigo-100">{{ $order->order_date->format('d M Y') }}</span>
                    </div>
                </div>
                
                @if($order->status === 'delivered')
                <div class="mt-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 shadow-inner">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-warehouse text-sm"></i>
                    </div>
                    <p class="text-[10px] font-bold text-emerald-400 leading-relaxed uppercase tracking-widest">Partner Inventory Auto-Provisioned Successfully</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
