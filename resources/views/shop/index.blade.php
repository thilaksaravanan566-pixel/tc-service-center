@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-7xl mx-auto pb-24">

    <!-- Header & Filter Grid -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-10 mb-16 px-4">
        <div class="flex items-center gap-8">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Spare Matrix Store</h1>
                <p class="text-slate-500 font-medium mt-2 leading-relaxed max-w-md">Discover ultra-precise components for terminal repair and modular performance enhancement.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide shrink-0">
            {{-- Filter Elements --}}
            <button class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white hover:bg-white/10 transition-all flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Categorize
            </button>
            <button class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-indigo-400 uppercase tracking-widest hover:bg-indigo-500/10 transition-all flex items-center gap-3 shadow-xl">
                Priority Sync
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    </div>

    {{-- Product Nodal Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 px-4">
        @if(!empty($parts) && count($parts) > 0)
            @foreach($parts as $part)
                <div class="super-card p-6 group flex flex-col hover:border-indigo-500/40 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.03] transition-colors"></div>
                    
                    {{-- Visual Protocol --}}
                    <div class="aspect-square bg-slate-950/50 rounded-3xl mb-8 flex items-center justify-center p-8 relative overflow-hidden group-hover:bg-indigo-500/10 transition-all border border-white/5 shadow-inner">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        @if($part->image_path)
                            <img src="{{ app('filesystem')->url($part->image_path) }}" alt="{{ $part->name }}" class="object-contain w-full h-full transform group-hover:scale-110 transition-all duration-700 drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                        @else
                            <div class="text-7xl opacity-5 transform group-hover:rotate-12 transition-all duration-700 select-none">⚙️</div>
                        @endif
                        
                        <div class="absolute top-4 left-4">
                            @if($part->stock <= 5)
                            <div class="bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-xl border border-red-500/20 shadow-2xl backdrop-blur-md animate-pulse">
                                Critical Stock: {{ $part->stock }}
                            </div>
                            @else
                            <div class="bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-xl border border-emerald-500/20 shadow-2xl backdrop-blur-md">
                                In Registry
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Data Matrix --}}
                    <div class="flex-grow flex flex-col relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-[9px] font-black text-indigo-400/60 uppercase tracking-[0.2em] px-2 py-0.5 bg-indigo-500/5 rounded-md border border-indigo-500/10">{{ $part->category ?? 'Core' }}</span>
                            <span class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em]">{{ $part->brand ?? 'TC-GEN' }}</span>
                        </div>
                        
                        <a href="{{ route('shop.show', $part->id) }}" class="text-white font-black text-xl tracking-tight leading-tight mb-3 hover:text-indigo-400 transition-all line-clamp-2 decoration-indigo-500/30 decoration-2 underline-offset-4 hover:underline">
                            {{ $part->name }}
                        </a>
                        <p class="text-[11px] font-medium text-slate-500 line-clamp-2 mb-8 leading-relaxed">Genuine ultra-performance component engineered for high-resonant systems and mission-critical workloads.</p>

                        <div class="mt-auto mb-8 flex items-end justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-1">Acquisition Fee</span>
                                <span class="text-3xl font-black text-white tracking-tighter">₹{{ number_format($part->price) }}</span>
                            </div>
                            <div class="text-indigo-400/40 text-xs font-black tracking-widest group-hover:text-indigo-400 transition-colors uppercase">
                                Available
                            </div>
                        </div>

                        {{-- Interface Actions --}}
                        <div class="flex items-center gap-3">
                            <form action="{{ route('customer.cart.add', $part->id) }}" method="POST" class="flex-grow">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-indigo-500 text-white text-[10px] font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-400 hover:scale-[1.02] transform transition-all duration-300 flex items-center justify-center gap-3 uppercase tracking-[0.2em]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Inject to Cart
                                </button>
                            </form>
                            <a href="{{ route('shop.show', $part->id) }}" class="p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 hover:border-white/20 transition-all group/btn shadow-xl">
                                <svg class="w-5 h-5 text-slate-400 group-hover/btn:text-white group-hover/btn:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full py-40 text-center super-card relative overflow-hidden group">
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.02] transition-colors"></div>
                <div class="w-32 h-32 bg-slate-950 border border-white/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-2xl relative">
                    <div class="absolute inset-0 bg-indigo-500/5 rounded-[2.5rem] animate-pulse"></div>
                    <svg class="w-12 h-12 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="font-black text-white text-3xl tracking-tight mb-4">No Matrix Entities Found</h3>
                <p class="text-slate-500 font-medium max-w-sm mx-auto leading-relaxed">Adjust your tactical parameters or search terms to re-initialize shop discovery.</p>
                <button onclick="window.location.reload()" class="mt-12 px-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-black text-[10px] uppercase tracking-[0.3em] hover:bg-white/10 transition-all">Reset Handshake</button>
            </div>
        @endif
    </div>

</div>
@endsection
