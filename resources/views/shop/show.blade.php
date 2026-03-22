@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-7xl mx-auto pb-24">
    
    {{-- Navigation/Breadcrumbs Matrix --}}
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
        <a href="{{ route('shop.index') }}" class="group flex items-center gap-4 px-6 py-4 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 transition-all font-black text-[10px] uppercase tracking-[0.3em] shadow-xl">
            <svg class="w-4 h-4 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            Return to Store Matrix
        </a>
        <div class="flex items-center gap-4 bg-slate-950/50 px-6 py-3 rounded-2xl border border-white/5 shadow-inner">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] animate-pulse"></span>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Node Verified & Operational</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start px-4">
        
        {{-- Product Visualizer --}}
        <div class="lg:col-span-5 sticky top-24">
            <div class="super-card p-6 group relative overflow-hidden rounded-[2.5rem] bg-slate-950/40 backdrop-blur-3xl border-white/5">
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.03] transition-colors"></div>
                
                @if($part->image_path)
                    <div class="aspect-square bg-slate-950/80 rounded-[2rem] flex items-center justify-center border border-white/5 shadow-2xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent"></div>
                        <img src="{{ app('filesystem')->url($part->image_path) }}" alt="{{ $part->name }}" class="max-w-[85%] h-auto object-contain transform group-hover:scale-110 transition-all duration-1000 drop-shadow-[0_30px_60px_rgba(0,0,0,0.5)]">
                    </div>
                @else
                    <div class="aspect-square bg-slate-950/80 rounded-[2rem] flex items-center justify-center border border-white/5 shadow-2xl">
                        <div class="text-center group-hover:scale-110 transition-transform duration-700">
                            <span class="text-8xl block mb-4 opacity-10 grayscale group-hover:grayscale-0 transition-all">📦</span>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-700">No Visual Metadata</span>
                        </div>
                    </div>
                @endif
                
                {{-- Decorative Pulse --}}
                <div class="absolute -top-12 -right-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none group-hover:bg-indigo-500/20 transition-all duration-1000"></div>
                <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-purple-500/10 rounded-full blur-[100px] pointer-events-none group-hover:bg-purple-500/20 transition-all duration-1000"></div>
            </div>

            <div class="mt-8 grid grid-cols-4 gap-6">
                @for($i=0; $i<4; $i++)
                <div class="aspect-square bg-white/5 border border-white/10 rounded-2xl cursor-pointer hover:border-indigo-500/50 hover:bg-white/10 transition-all flex items-center justify-center group/thumb overflow-hidden relative shadow-lg">
                    <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover/thumb:opacity-100 transition-opacity"></div>
                    <span class="text-lg opacity-20 group-hover/thumb:opacity-100 group-hover/thumb:scale-110 transition-all duration-500">📸</span>
                </div>
                @endfor
            </div>
        </div>

        {{-- Product Data & Logic --}}
        <div class="lg:col-span-4 space-y-12">
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <span class="px-4 py-1.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 shadow-xl">Essential Protocol</span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-2 py-1 bg-white/5 rounded-lg">{{ $part->category ?? 'Hardware' }} Node</span>
                </div>
                <h1 class="text-5xl font-black text-white leading-tight tracking-tighter mb-6">
                    {{ $part->name }}
                </h1>
                <div class="flex items-center gap-6">
                    <div class="flex gap-1.5">
                        @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 {{ $i < 4 ? 'text-indigo-400' : 'text-slate-800' }} fill-current shadow-[0_0_10px_rgba(99,102,241,0.3)]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 border-l border-white/10 pl-6">4.9 Node Precision Rating</span>
                </div>
            </div>

            <div class="p-10 rounded-[2.5rem] bg-indigo-500/[0.03] border border-indigo-500/20 shadow-2xl relative overflow-hidden group">
                <div class="flex items-baseline gap-4 mb-4">
                    <span class="text-5xl font-black text-white tracking-tighter">₹{{ number_format($part->price, 0) }}</span>
                    <span class="text-slate-600 line-through text-lg font-black tracking-tight">₹{{ number_format($part->price * 1.25, 0) }}</span>
                </div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] flex items-center gap-3">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Secure 25% Acquisition Discount
                </p>
                
                {{-- Decorative background text --}}
                <div class="absolute -right-8 -bottom-8 text-[9rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap group-hover:text-white/[0.04] transition-all duration-1000">VALUE</div>
            </div>

            <div class="space-y-8 px-2">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] flex items-center gap-4">
                    <span class="w-10 h-px bg-indigo-500/30"></span> 
                    Specifications & Node Metadata
                </h3>
                <div class="grid grid-cols-2 gap-10">
                    <div class="space-y-2 group/meta">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] group-hover/meta:text-indigo-400 transition-colors">Compatible Base</span>
                        <p class="text-base font-black text-slate-200 tracking-tight">Universal Architecture</p>
                    </div>
                    <div class="space-y-2 group/meta">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] group-hover/meta:text-indigo-400 transition-colors">Protection Matrix</span>
                        <p class="text-base font-black text-slate-200 tracking-tight">180 Day Warranty Range</p>
                    </div>
                    <div class="space-y-2 group/meta">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] group-hover/meta:text-indigo-400 transition-colors">Replenishment</span>
                        <p class="text-base font-black text-slate-200 tracking-tight">07 Day Rapid Interface</p>
                    </div>
                    <div class="space-y-2 group/meta">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] group-hover/meta:text-indigo-400 transition-colors">Entity Condition</span>
                        <p class="text-base font-black text-emerald-500 tracking-tight flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Factory Locked
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8 px-2">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] flex items-center gap-4">
                    <span class="w-10 h-px bg-purple-500/30"></span> 
                    Node description Protocol
                </h3>
                <ul class="space-y-6">
                    @php
                        $features = [
                            'High-fidelity component engineered for '.$part->category.' subsystems only.',
                            'Certified structural integrity and passive thermal resistance.',
                            'Optimized for zero-latency installation and long-term stability.',
                            'Systematically validated in extreme high-resonant environments.'
                        ];
                    @endphp
                    @foreach($features as $feature)
                    <li class="flex items-start gap-4 group/li">
                        <div class="w-6 h-6 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5 group-hover/li:bg-indigo-500 group-hover/li:border-indigo-400 transition-all duration-500 group-hover/li:rotate-3 shadow-lg">
                            <svg class="w-3.5 h-3.5 text-indigo-400 group-hover/li:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[13px] text-slate-400 leading-relaxed font-semibold transition-colors group-hover/li:text-slate-200">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Order Matrix --}}
        <div class="lg:col-span-3">
            <div class="super-card p-10 sticky top-24 border-t-8 border-indigo-500 rounded-[2.5rem] bg-slate-950/40 backdrop-blur-3xl shadow-2xl overflow-hidden group/ord">
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover/ord:bg-indigo-500/[0.03] transition-colors"></div>
                
                <div class="space-y-10 relative z-10">
                    <div>
                        <div class="flex justify-between items-baseline mb-4">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Acquisition Fee</span>
                            <span class="text-3xl font-black text-white tracking-tighter">₹{{ number_format($part->price, 0) }}</span>
                        </div>
                        <div class="text-[10px] font-black text-indigo-400/60 flex items-center gap-3 bg-indigo-500/5 px-4 py-2 rounded-xl border border-indigo-500/10 w-fit">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-ping"></span>
                            Priority Logistics Active
                        </div>
                    </div>

                    @if($part->stock > 0)
                        <div class="p-6 rounded-3xl bg-emerald-500/5 border border-emerald-500/20 shadow-inner group/stock">
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-2 flex items-center gap-3">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span> Terminal In-Stock
                            </p>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Available units: <span class="text-emerald-400 font-black text-sm">{{ $part->stock }}</span></p>
                        </div>

                        @auth('customer')
                            <form id="checkout-form" action="{{ route('customer.product.order', $part->id) }}" method="POST" class="space-y-8">
                                @csrf
                                
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Payload Quantity</label>
                                    <div class="relative">
                                        <select name="quantity" class="super-input w-full py-4 text-white font-black bg-slate-950/80 appearance-none pr-12 rounded-2xl border-white/5 shadow-inner">
                                            @for($i=1; $i<=min($part->stock, 10); $i++)
                                                <option value="{{ $i }}">{{ $i }} Unit{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-4 shadow-inner">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] block ml-1">Funding Interface</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <label class="relative cursor-pointer group/radio">
                                                <input type="radio" name="payment_method" value="cod" checked class="peer sr-only">
                                                <div class="px-4 py-3 rounded-2xl border border-white/5 bg-slate-950 flex flex-col items-center gap-1 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 peer-checked:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all">
                                                    <span class="text-[9px] font-black text-slate-600 peer-checked:text-indigo-400 uppercase tracking-[0.2em]">COD</span>
                                                    <span class="text-[8px] font-black text-slate-700 peer-checked:text-indigo-400/50 uppercase tracking-tighter opacity-0 peer-checked:opacity-100 transition-opacity">Manual</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer group/radio">
                                                <input type="radio" name="payment_method" value="upi" class="peer sr-only">
                                                <div class="px-4 py-3 rounded-2xl border border-white/5 bg-slate-950 flex flex-col items-center gap-1 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 peer-checked:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all">
                                                    <span class="text-[9px] font-black text-slate-600 peer-checked:text-indigo-400 uppercase tracking-[0.2em]">QUANTUM</span>
                                                    <span class="text-[8px] font-black text-slate-700 peer-checked:text-indigo-400/50 uppercase tracking-tighter opacity-0 peer-checked:opacity-100 transition-opacity">Digital</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-4 shadow-inner">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] block ml-1">Logistics Protocol</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <label class="relative cursor-pointer group/radio">
                                                <input type="radio" name="delivery_type" value="delivery" checked class="peer sr-only">
                                                <div class="px-4 py-3 rounded-2xl border border-white/5 bg-slate-950 flex flex-col items-center gap-1 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 peer-checked:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all">
                                                    <span class="text-[9px] font-black text-slate-600 peer-checked:text-indigo-400 uppercase tracking-[0.2em]">RAPID</span>
                                                    <span class="text-[8px] font-black text-slate-700 peer-checked:text-indigo-400/50 uppercase tracking-tighter opacity-0 peer-checked:opacity-100 transition-opacity">Delivery</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer group/radio">
                                                <input type="radio" name="delivery_type" value="take_away" class="peer sr-only">
                                                <div class="px-4 py-3 rounded-2xl border border-white/5 bg-slate-950 flex flex-col items-center gap-1 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 peer-checked:shadow-[0_0_20px_rgba(99,102,241,0.2)] transition-all">
                                                    <span class="text-[9px] font-black text-slate-600 peer-checked:text-indigo-400 uppercase tracking-[0.2em] whitespace-nowrap">EXTRACTION</span>
                                                    <span class="text-[8px] font-black text-slate-700 peer-checked:text-indigo-400/50 uppercase tracking-tighter opacity-0 peer-checked:opacity-100 transition-opacity">Pickup</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 pt-4">
                                    <button type="button" onclick="document.getElementById('checkout-form').action='{{ route('customer.cart.add', $part->id) }}'; document.getElementById('checkout-form').submit();"
                                        class="w-full py-5 rounded-[1.5rem] bg-white/5 border border-white/10 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 hover:bg-white/10 transition-all flex items-center justify-center gap-4 hover:scale-[1.02] shadow-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Sync to Cart
                                    </button>
                                    <button type="button" onclick="document.getElementById('checkout-form').action='{{ route('customer.product.order', $part->id) }}'; document.getElementById('checkout-form').submit();" 
                                        class="w-full py-5 rounded-[1.5rem] bg-indigo-500 text-white text-[10px] font-black uppercase tracking-[0.3em] hover:bg-indigo-400 transition-all shadow-[0_20px_50px_rgba(99,102,241,0.4)] flex items-center justify-center gap-4 hover:scale-[1.02] group/buy">
                                        <svg class="w-5 h-5 group-hover/buy:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Initialize Purchase
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="space-y-4 py-6">
                                <a href="{{ route('customer.login') }}" class="w-full py-5 bg-indigo-500 text-white rounded-2xl flex items-center justify-center font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl hover:bg-indigo-400 transition-all">Authorize Identity</a>
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-center text-slate-600 flex items-center justify-center gap-3">
                                    <span class="w-8 h-px bg-white/5"></span>
                                    Registry Required
                                    <span class="w-8 h-px bg-white/5"></span>
                                </p>
                            </div>
                        @endauth
                    @else
                        <div class="p-10 rounded-[2rem] bg-red-500/5 border border-red-500/10 text-center shadow-inner group/fail">
                            <div class="w-16 h-16 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover/fail:rotate-12 transition-transform duration-500">
                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-xs font-black text-red-400 uppercase tracking-[0.3em] mb-3">Resource Critical</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed font-bold uppercase tracking-widest">This node is currently offline or exhausted. Re-sync later.</p>
                        </div>
                    @endif

                    <div class="pt-10 border-t border-white/5 space-y-6">
                        <div class="flex items-center gap-5 group/icon">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-xl group-hover/icon:bg-indigo-500/10 group-hover/icon:border-indigo-500/20 transition-all shadow-lg">🚀</div>
                            <div class="flex-1">
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em]">Origin Hub</p>
                                <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.1em]">TC Central Diagnostics</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-5 group/icon">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-xl group-hover/icon:bg-indigo-500/10 group-hover/icon:border-indigo-500/20 transition-all shadow-lg">🔒</div>
                            <div class="flex-1">
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.3em]">Neural Security</p>
                                <p class="text-[10px] font-black text-slate-200 uppercase tracking-[0.1em]">Verified fulfillment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection