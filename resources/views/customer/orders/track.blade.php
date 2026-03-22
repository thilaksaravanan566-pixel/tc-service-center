@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-7xl mx-auto pb-24">
    
    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 px-4">
        <div class="flex items-center gap-8">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Logistics Timeline</h1>
                <p class="text-slate-500 font-mono text-xs mt-2 uppercase tracking-[0.2em] font-black">
                    Registry Index: <span class="text-indigo-400">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span> · {{ $order->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
        <a href="{{ route('customer.orders.index') }}" class="group flex items-center gap-4 px-10 py-5 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 transition-all font-black text-[10px] uppercase tracking-[0.3em] self-start md:self-center shadow-xl">
            <svg class="w-4 h-4 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Historical Registry
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <!-- Tracking Timeline Matrix -->
        <div class="lg:col-span-8">
            <div class="super-card p-12 relative overflow-hidden group">
                {{-- Decorative background text --}}
                <div class="absolute -right-12 -top-12 text-[10rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">STATUS</div>
                
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.02] transition-colors"></div>

                <h2 class="text-[10px] font-black text-white mb-16 uppercase tracking-[0.4em] flex items-center gap-6 relative z-10">
                    <span class="w-12 h-px bg-indigo-500/30"></span>
                    Live Logistics Stream
                </h2>

                <!-- Timeline Protocol -->
                <div class="relative z-10 ml-6">
                    @foreach($timeline as $step)
                    <div class="flex items-start gap-10 pb-16 last:pb-0 relative animate-slide-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                        <!-- Logistics Connector -->
                        @if(!$loop->last)
                        <div class="absolute left-[27px] top-14 w-0.5 h-[calc(100%-3.5rem)]
                            {{ $step['done'] ? 'bg-gradient-to-b from-indigo-500 to-indigo-500/20' : 'bg-white/5' }}
                            transition-all duration-1000"></div>
                        @if($step['active'])
                            <div class="absolute left-[27px] top-14 w-0.5 h-[calc(100%-3.5rem)] bg-indigo-500/30 blur-sm animate-pulse"></div>
                        @endif
                        @endif

                        <!-- Logistics Node -->
                        <div class="relative flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center text-2xl transition-all duration-700
                            {{ $step['active'] ? 'bg-indigo-500 text-white shadow-[0_0_50px_rgba(99,102,241,0.5)] scale-110 z-10 border-2 border-indigo-400 rotate-3' : ($step['done'] ? 'bg-indigo-500/10 border border-indigo-500/30 text-indigo-400' : 'bg-slate-950 border border-white/5 text-slate-700') }}">
                            <div class="absolute inset-0 rounded-2xl bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            {{ $step['icon'] }}
                        </div>

                        <!-- Logistics Metadata -->
                        <div class="pt-2 flex-grow">
                            <p class="font-black text-2xl {{ $step['active'] ? 'text-white' : ($step['done'] ? 'text-slate-200' : 'text-slate-700') }} tracking-tight transition-colors">
                                {{ $step['label'] }}
                            </p>
                            @if($step['active'])
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="px-3 py-1 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] flex items-center gap-2 shadow-xl">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                                        Active Terminal
                                    </span>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Processing Payload...</span>
                                </div>
                            @elseif($step['done'])
                                <p class="text-emerald-500/60 text-[11px] font-black uppercase tracking-[0.2em] mt-3 flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Registry Verified
                                </p>
                            @else
                                <p class="text-slate-800 text-[11px] font-black uppercase tracking-[0.2em] mt-3">Scheduled Protocol</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Diagnostics -->
        <div class="lg:col-span-4 space-y-12">
            <!-- Entity Summary -->
            <div class="super-card p-10 bg-slate-950/40 backdrop-blur-3xl border-white/5">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-10 flex items-center gap-4">
                    <span class="w-3 h-1 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Payload Identity
                </h3>
                <div class="flex items-center gap-6 mb-10 px-2">
                    <div class="w-24 h-24 rounded-3xl bg-slate-950 border border-white/5 overflow-hidden flex-shrink-0 shadow-inner group p-3 relative">
                        <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        @if($order->sparePart?->image_path)
                            <img src="{{ app('filesystem')->url($order->sparePart->image_path) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl opacity-10">⚙️</div>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-2">{{ $order->sparePart?->category ?? 'Hardware' }} Unit</p>
                        <p class="text-white font-black text-xl tracking-tight leading-tight">{{ $order->sparePart?->name }}</p>
                        <p class="text-slate-500 text-xs font-black uppercase tracking-widest mt-2 flex items-center gap-2">
                            <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                            {{ $order->quantity }} Modules
                        </p>
                    </div>
                </div>
                
                <div class="space-y-6 pt-10 border-t border-white/5">
                    <div class="flex justify-between items-center px-2">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Protocol Value</span>
                        <span class="text-white font-black text-xl tracking-tighter">₹{{ number_format($order->total_price, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center px-2">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Fund Strategy</span>
                        <span class="text-slate-200 font-black uppercase tracking-[0.15em] text-[10px] bg-white/5 px-3 py-1 rounded-lg border border-white/5">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between items-center px-2">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Auth Status</span>
                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->is_paid ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500 animate-pulse' }} shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                            <span class="{{ $order->is_paid ? 'text-emerald-400' : 'text-amber-400' }} font-black uppercase tracking-[0.2em] text-[10px]">
                                {{ $order->is_paid ? 'Verified' : 'Pending' }}
                            </span>
                        </span>
                    </div>
                    @if($order->tracking_number)
                    <div class="flex flex-col gap-3 mt-6 px-2">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Terminal Carrier Index</span>
                        <span class="text-indigo-400 font-black font-mono bg-indigo-500/10 py-4 px-6 rounded-2xl border border-indigo-500/20 shadow-2xl text-center tracking-[0.3em] text-sm">{{ $order->tracking_number }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Logistics Parameters -->
            @if($order->delivery_type === 'delivery')
            <div class="super-card p-10 bg-indigo-600/[0.03] border-indigo-500/20 group">
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.4em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-px bg-indigo-500/40"></span>
                    Logistics Matrix
                </h3>
                <div class="space-y-8">
                    <div class="space-y-3">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">Destination Hub</p>
                        <p class="text-slate-200 text-sm font-bold leading-relaxed tracking-tight">{{ $order->delivery_address }}</p>
                    </div>
                    @if($order->delivery_mobile)
                    <div class="space-y-3">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">Comms Identifier</p>
                        <p class="text-indigo-400 text-sm font-black tracking-[0.2em] bg-indigo-500/5 px-4 py-2 rounded-xl border border-indigo-500/10 w-fit">{{ $order->delivery_mobile }}</p>
                    </div>
                    @endif
                    @if($order->deliveryPartner)
                    <div class="p-6 rounded-3xl bg-slate-950/80 border border-white/5 flex items-center gap-5 group-hover:border-indigo-500/30 transition-all shadow-inner">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-2xl shadow-xl border border-indigo-500/20">👤</div>
                        <div>
                            <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">Fleet Agent</p>
                            <p class="text-white font-black text-sm uppercase tracking-[0.1em] mt-1">{{ $order->deliveryPartner->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @if($order->delivery_location_url)
                <a href="{{ $order->delivery_location_url }}" target="_blank" class="mt-10 flex items-center justify-center gap-4 w-full py-5 bg-indigo-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] shadow-[0_15px_40px_rgba(99,102,241,0.3)] hover:bg-indigo-400 transition-all group scale-100 hover:scale-[1.02]">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Nodal Interface Maps
                </a>
                @endif
            </div>
            @else
            <div class="super-card p-10 border-emerald-500/10 bg-emerald-500/[0.02] group">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-10 flex items-center gap-4">
                    <span class="w-10 h-px bg-emerald-500/30"></span>
                    Pickup Sequence
                </h3>
                <div class="flex items-center gap-6 p-6 rounded-3xl bg-slate-950/80 border border-emerald-500/20 shadow-inner group-hover:scale-[1.02] transition-all">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-3xl shadow-xl border border-emerald-500/20">🏪</div>
                    <div>
                        <p class="text-white font-black uppercase tracking-[0.2em] text-xs">Direct Hub Handshake</p>
                        <p class="text-emerald-500/70 text-[10px] font-black uppercase tracking-[0.2em] mt-2">Visit Local Extraction Node</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
