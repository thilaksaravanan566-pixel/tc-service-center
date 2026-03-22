@extends('layouts.admin')

@section('title', 'Logistics Management')

@section('content')
<div class="mb-8 flex items-end justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Logistic Dispatch Log</h1>
        <p class="text-gray-500 mt-2 font-medium">Tracking physical movement of hardware between centers and partner networks.</p>
    </div>
    <div class="flex gap-4">
        <div class="px-6 py-3 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-center justify-between gap-6 shadow-sm">
             <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none mb-1">In Transit</p>
                <p class="text-xl font-extrabold text-blue-900 italic tracking-tighter">{{ $shipments->where('status', 'in_transit')->count() }} SHIPMENTS</p>
             </div>
             <i class="fas fa-shipping-fast text-2xl text-blue-300 transition group-hover:scale-110"></i>
        </div>
    </div>
</div>

<div class="card overflow-hidden rounded-[2.5rem] shadow-sm border-gray-100">
    <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
        <p class="text-xs uppercase font-extrabold text-gray-400 tracking-widest">Global Shipments Monitor</p>
        <div class="flex gap-4">
            <a href="{{ route('admin.logistics.visits') }}" class="btn btn-secondary border border-gray-200 bg-white shadow-sm font-bold text-xs uppercase tracking-widest px-6 py-3 rounded-xl">
               <i class="fas fa-map-marked-alt mr-2"></i> Field Visits Monitor
            </a>
        </div>
    </div>
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Routing Control</th>
                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Logistic Method</th>
                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Consignee Partner</th>
                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Time Logs</th>
                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Progress</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100/50">
            @forelse($shipments as $shipment)
            <tr class="hover:bg-blue-50/20 transition group">
                <td class="px-8 py-6">
                    <p class="text-sm font-extrabold text-gray-900 tracking-tighter italic mb-1">{{ $shipment->dealerOrder->order_number ?? 'DL-MANIFEST' }}</p>
                    <p class="text-[10px] font-mono text-indigo-500 font-black tracking-widest uppercase">LOGS-{{ $shipment->id }}</p>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-3">
                         <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition duration-500 shadow-sm">
                            <i class="fas {{ $shipment->method === 'courier' ? 'fa-shipping-fast' : 'fa-bus' }} text-xs"></i>
                         </div>
                         <div>
                            <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">{{ strtoupper($shipment->method) }}</p>
                            <p class="text-[10px] text-gray-400 font-bold font-mono">{{ $shipment->tracking_number ?: $shipment->lr_number }}</p>
                         </div>
                    </div>
                </td>
                <td class="px-8 py-6">
                    <p class="text-sm font-black text-gray-900 leading-none mb-1 italic tracking-tighter">{{ $shipment->dealerOrder->dealer->business_name ?? 'Center Pickup' }}</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">{{ $shipment->dealerOrder->dealer->user->name ?? '--' }}</p>
                </td>
                <td class="px-8 py-6 leading-none">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">Dispatch: {{ $shipment->dispatch_at->format('d M, Y') }}</p>
                    </div>
                    @if($shipment->delivery_eta)
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">ETA/Del: {{ $shipment->delivery_eta->format('d M') }}</p>
                    </div>
                    @endif
                </td>
                <td class="px-8 py-6 text-right">
                    <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border shadow-sm
                        @if($shipment->status === 'delivered') bg-emerald-100 text-emerald-700 border-emerald-100 @elseif($shipment->status === 'in_transit') bg-blue-100 text-blue-700 border-blue-100 @else bg-gray-100 text-gray-700 border-gray-100 @endif">
                        {{ strtoupper($shipment->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-widest italic opacity-40">No Dispatch Records Initiated</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-8 py-5 bg-gray-50 border-t border-gray-100">
        {{ $shipments->links() }}
    </div>
</div>
@endsection
