@extends('layouts.dealer')

@section('title', 'Service Tracking Details')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <a href="{{ route('dealer.services.index') }}" class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2 mb-2 hover:text-white transition">
            <i class="fas fa-arrow-left"></i> Return to Manifest
        </a>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic leading-none">Job <span class="text-indigo-400">#{{ $order->tc_job_id }}</span></h1>
        <p class="text-gray-400 mt-2 font-medium tracking-tight">Active technical surveillance for {{ $order->device->brand }} {{ $order->device->model }}.</p>
    </div>
    <div class="flex gap-4">
        @if($order->status === 'completed' && $order->is_paid)
             <a href="{{ route('dealer.invoices.show', $order->invoices->first()->id ?? 0) }}" class="btn btn-primary bg-emerald-600 px-8 py-4 rounded-2xl shadow-xl shadow-emerald-600/30 font-black text-white flex items-center gap-3 cursor-pointer hover:scale-105 active:scale-95 transition-all">
                <i class="fas fa-file-invoice text-lg"></i>
                WATCH INVOICE
             </a>
        @endif
        @if(in_array($order->status, ['shipping', 'out_for_delivery', 'packing']))
             <a href="{{ route('dealer.orders.track', $order->id) }}?type=service" class="btn btn-primary bg-blue-600 px-8 py-4 rounded-2xl shadow-xl shadow-blue-600/30 font-black text-white flex items-center gap-3 cursor-pointer hover:scale-105 active:scale-95 transition-all w-max whitespace-nowrap">
                <i class="fas fa-map-marked-alt text-lg"></i>
                LIVE VECTOR MAP
             </a>
        @endif
        <div id="status-badge" class="px-6 py-4 bg-indigo-600/20 border border-indigo-500/30 rounded-2xl text-indigo-400 font-black text-xs uppercase tracking-widest shadow-2xl">
            {{ strtoupper($order->status) }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 space-y-10">
        <!-- Hardware Blueprint -->
        <div class="card p-10 border-0 bg-white shadow-2xl rounded-[3rem] relative overflow-hidden group">
             <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
             <div class="relative">
                <h3 class="text-xs uppercase font-extrabold text-blue-600 tracking-widest mb-10 border-b border-blue-50/50 pb-5 flex justify-between items-center">
                    Technical Specifications 
                    <span class="text-[10px] text-gray-400 tracking-normal font-bold">Serial: {{ $order->device->serial_number ?: '--' }}</span>
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10 leading-none">
                    <div class="p-5 bg-gray-50 rounded-3xl border border-gray-100/50">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Processor</p>
                        <p class="text-sm font-black text-gray-900 tracking-tighter italic">{{ $order->device->processor ?: 'STOCK CORE' }}</p>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-3xl border border-gray-100/50">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">RAM Memory</p>
                        <p class="text-sm font-black text-gray-900 tracking-tighter italic">{{ $order->device->ram ?: '4GB BASE' }}</p>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-3xl border border-gray-100/50">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Primary / SSD</p>
                        <p class="text-sm font-black text-gray-900 tracking-tighter italic">{{ $order->device->ssd ?: 'NOT APPL' }}</p>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-3xl border border-gray-100/50">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Secondary / HDD</p>
                        <p class="text-sm font-black text-gray-900 tracking-tighter italic">{{ $order->device->hdd ?: 'NOT APPL' }}</p>
                    </div>
                </div>
                
                <h3 class="text-xs uppercase font-extrabold text-red-600 tracking-widest mb-6 leading-none">Reported Fault Logs</h3>
                <div class="p-6 bg-red-50/50 rounded-3xl border border-red-50 text-gray-700 italic font-medium leading-relaxed shadow-sm">
                    "{{ $order->fault_details }}"
                </div>
             </div>
        </div>

        <!-- Inventory Consumption -->
        <div class="card p-10 border-0 bg-white shadow-2xl rounded-[3rem] relative overflow-hidden group">
             <div class="relative">
                <h3 class="text-xs uppercase font-extrabold text-indigo-600 tracking-widest mb-8 border-b border-indigo-50/50 pb-5 leading-none">Inventory Consumption Manifest (Parts Used)</h3>
                
                <div class="space-y-4">
                    @forelse($order->parts_used ?? [] as $part)
                    <div class="flex items-center justify-between p-6 bg-gray-50/50 border border-gray-100 rounded-3xl group/item">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-500 text-lg">
                                <i class="fas fa-microchip group-hover/item:scale-110 transition duration-500"></i>
                            </div>
                            <div>
                                <p class="text-base font-black text-gray-900 italic tracking-tighter leading-none mb-2">{{ $part['name'] }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $part['quantity'] }} Units Deployed @ ₹{{ number_format($part['price'], 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Part Value</p>
                             <p class="text-lg font-black text-indigo-600 italic tracking-tighter">₹{{ number_format($part['price'] * $part['quantity'], 2) }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-16 text-center border-2 border-dashed border-gray-100 rounded-[2.5rem] opacity-40">
                        <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest italic">Inventory consumption not yet logged by technician</p>
                    </div>
                    @endforelse
                </div>

                @if($order->parts_used)
                <div class="mt-10 pt-8 border-t border-gray-100 flex justify-between items-center px-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1 shadow-none">Module Cost Aggregate</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-tight italic">Inventory Deducted Automatically</p>
                    </div>
                    <p class="text-3xl font-black text-gray-900 tracking-tighter italic">₹{{ number_format(collect($order->parts_used)->sum(fn($p) => $p['price'] * $p['quantity']), 2) }}</p>
                </div>
                @endif
             </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-10">
        <!-- Logistics Info -->
        <div class="card p-10 border-0 bg-gray-900 text-white shadow-2xl rounded-[3rem] relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <h3 class="text-xs uppercase font-extrabold text-indigo-400 tracking-widest mb-8 border-b border-white/5 pb-4 leading-none">Logistic Intelligence</h3>
                <div class="space-y-8">
                    <div>
                        <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-3 italic">Technical Lead</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-indigo-400 font-black">
                                {{ substr($order->technician->name ?? 'T', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-black text-white italic tracking-tighter mb-1">{{ $order->technician->name ?? 'Awaiting Allocation' }}</p>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">TC CENTER TECHNICIAN</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-3 italic">Movement Protocol</p>
                        <p class="text-sm font-black text-white italic tracking-tighter mb-1">{{ strtoupper($order->delivery_type ?: 'PICKUP') }}</p>
                        <p class="text-[11px] text-gray-500 font-medium italic">Partner Transit Active</p>
                    </div>

                    @if($order->estimated_cost > 0)
                    <div class="mt-10 p-6 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-600/30">
                        <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-1 italic">Service Valuation</p>
                        <p class="text-4xl font-black text-white italic tracking-tighter mb-2">₹{{ number_format($order->estimated_cost, 2) }}</p>
                        <div class="flex items-center gap-2">
                             @if($order->is_paid)
                             <span class="w-2 h-2 bg-emerald-400 rounded-full shadow-lg shadow-emerald-400/50"></span>
                             <span class="text-[10px] font-black uppercase text-indigo-100 italic">Financial Settlement Complete</span>
                             @else
                             <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                             <span class="text-[10px] font-black uppercase text-indigo-100 italic">Awaiting Credit / Clearance</span>
                             @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Intelligence Activity -->
        <div class="card p-10 border-0 bg-white shadow-2xl rounded-[3rem] relative overflow-hidden group">
             <h3 class="text-xs uppercase font-extrabold text-blue-600 tracking-widest mb-8 leading-none">Activity Stream</h3>
             <ul class="space-y-6 relative ml-4">
                <li class="relative pl-6 pb-6 border-l border-gray-100 before:absolute before:-left-[5.5px] before:top-1 before:w-[11px] before:h-[11px] before:bg-indigo-600 before:rounded-full before:border-2 before:border-white">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 leading-none">{{ $order->created_at->format('d M, Y') }}</p>
                    <p class="text-xs font-black text-gray-900 italic tracking-tighter">Job provisioned in system</p>
                </li>
                @if($order->engineer_comment)
                <li class="relative pl-6 border-l border-gray-100 before:absolute before:-left-[5.5px] before:top-1 before:w-[11px] before:h-[11px] before:bg-blue-400 before:rounded-full before:border-2 before:border-white last:pb-0">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 leading-none italic">Tech Update</p>
                    <p class="text-xs font-medium text-gray-700 leading-relaxed italic">"{{ $order->engineer_comment }}"</p>
                </li>
                @endif
             </ul>
        </div>
    </div>
</div>
@endsection
