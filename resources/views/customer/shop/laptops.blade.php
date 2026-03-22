@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-7xl mx-auto pb-24">
    
    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-10 mb-16 px-4">
        <div class="flex items-center gap-8">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter">Refurbished Matrix</h1>
                <p class="text-slate-500 font-medium mt-2 leading-relaxed max-w-xl">Premium pre-owned computing nodes, rigorously certified and synchronized with active warranty protocols for long-term operational stability.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 shrink-0 overflow-x-auto pb-2 scrollbar-hide">
            <button class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-white hover:bg-white/10 transition-all flex items-center gap-3 shadow-2xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter Stream
            </button>
            <button class="px-8 py-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] hover:bg-indigo-500/20 transition-all flex items-center gap-3 shadow-2xl shadow-indigo-500/10">
                Sort: Resonance
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    </div>

    @if($laptops->isEmpty())
        <div class="super-card py-40 text-center relative overflow-hidden mx-4 rounded-[3rem] group">
            <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.03] transition-colors"></div>
            <div class="w-32 h-32 bg-slate-950 border border-white/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-indigo-500/5 animate-pulse"></div>
                <svg class="w-12 h-12 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-3xl font-black text-white mb-4 tracking-tighter">Inventory Vacuum</h2>
            <p class="text-slate-500 font-medium max-w-sm mx-auto leading-relaxed uppercase tracking-[0.1em]">No refurbished nodes currently available in the active matrix. System refresh scheduled for next cycle.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 px-4">
            @foreach($laptops as $laptop)
            <div class="super-card group overflow-hidden flex flex-col hover:border-indigo-500/40 transition-all duration-700 hover:-translate-y-3 rounded-[2.5rem] border-white/5 bg-slate-950/40 backdrop-blur-3xl shadow-2xl">
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.03] transition-colors"></div>
                
                <!-- Node Visualizer -->
                <div class="aspect-[4/3] relative overflow-hidden bg-slate-950/80 shadow-inner group-hover:bg-slate-950 transition-colors duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80 z-10"></div>
                    @if($laptop->image)
                        <img src="{{ app('filesystem')->url($laptop->image) }}" class="w-full h-full object-cover group-hover:scale-115 transition-transform duration-1000 filter brightness-90 group-hover:brightness-100">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-7xl opacity-5 group-hover:opacity-10 transition-opacity duration-700 select-none">💻</div>
                    @endif
                    
                    <div class="absolute top-6 left-6 z-20">
                        <div class="bg-indigo-500 text-white text-[9px] font-black px-4 py-2 rounded-xl border border-indigo-400 shadow-2xl shadow-indigo-500/40 uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse shadow-[0_0_8px_white]"></span>
                            {{ $laptop->status ?? 'Verified' }}
                        </div>
                    </div>
                </div>

                <!-- Entity Information Matrix -->
                <div class="p-8 flex-grow relative z-20 -mt-10">
                    <div class="bg-slate-950/90 backdrop-blur-3xl rounded-[2rem] p-6 border border-white/5 mb-8 shadow-2xl group-hover:border-indigo-500/20 transition-all duration-500">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.4em] mb-3 opacity-60 group-hover:opacity-100 transition-opacity">{{ $laptop->brand }} protocol</p>
                        <h3 class="text-white font-black text-2xl group-hover:text-indigo-400 transition-colors line-clamp-1 tracking-tighter mb-4">{{ $laptop->model }}</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-white tracking-tighter">₹{{ number_format($laptop->price) }}</span>
                            <span class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Base Fee</span>
                        </div>
                    </div>

                    <!-- Hardware Parameter Metrics -->
                    <div class="space-y-4 mb-10 px-2">
                        <div class="flex items-center gap-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] group/param">
                            <div class="w-8 h-8 rounded-xl bg-indigo-500/5 group-hover/param:bg-indigo-500/20 flex items-center justify-center text-indigo-400 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                            </div>
                            {{ $laptop->processor }} · {{ $laptop->ram }}
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] group/param">
                            <div class="w-8 h-8 rounded-xl bg-purple-500/5 group-hover/param:bg-purple-500/20 flex items-center justify-center text-purple-400 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                            </div>
                            {{ $laptop->storage }} Repository
                        </div>
                    </div>

                    <!-- Interface Handoff -->
                    <div class="flex gap-4">
                        <button class="flex-grow py-5 rounded-[1.5rem] bg-indigo-500 text-white font-black text-[10px] uppercase tracking-[0.3em] shadow-2xl shadow-indigo-500/30 hover:bg-indigo-400 transition-all border border-indigo-400 hover:scale-[1.03] active:scale-95 group/btn">
                            Initialize Acquisition
                            <svg class="w-4 h-4 inline-block ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button class="w-16 h-16 rounded-[1.5rem] border border-white/10 bg-white/5 text-slate-500 hover:text-indigo-400 hover:border-indigo-500/40 hover:bg-indigo-500/10 transition-all flex items-center justify-center shadow-xl hover:rotate-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
