@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-6xl mx-auto pb-24">
    
    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-12 mb-16 px-4">
        <div class="flex items-center gap-10">
            <div class="w-24 h-24 rounded-[2rem] bg-slate-950 border border-white/5 flex items-center justify-center text-emerald-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute top-0 right-0 w-8 h-8 bg-emerald-500/10 blur-xl"></div>
                <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 00-2 2z"/></svg>
            </div>
            <div>
                <h1 class="text-5xl font-black text-white tracking-tighter mb-4">Sentinel Grid Deployment</h1>
                <p class="text-slate-500 font-medium text-lg leading-relaxed max-w-2xl">Architect your professional surveillance perimeter with active monitoring nodes and secure storage protocols. Zero-blindspot neural engineering.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 shrink-0 bg-white/5 p-4 rounded-[2rem] border border-white/5 backdrop-blur-3xl shadow-2xl">
            <div class="relative w-12 h-12 flex items-center justify-center">
                <div class="absolute inset-0 bg-emerald-500 opacity-20 animate-ping rounded-full"></div>
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-[10px] font-black relative z-10">LIVE</div>
            </div>
            <div class="pr-2">
                <p class="text-white font-black text-[10px] uppercase tracking-widest">Surveillance Ops</p>
                <p class="text-emerald-400 font-black text-[9px] uppercase tracking-widest">Active Monitoring Enabled</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mx-4 mb-12 bg-red-500/10 text-red-400 border border-red-500/20 p-8 rounded-[2.5rem] shadow-2xl animate-shake">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em]">Deployment Fault Registry</p>
            </div>
            <ul class="space-y-2 opacity-80 text-xs font-bold pl-12 uppercase tracking-[0.1em]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.service.store') }}" class="super-card p-12 lg:p-16 relative overflow-hidden mx-4 rounded-[3.5rem] bg-slate-950/40 backdrop-blur-3xl border-white/5 group">
        {{-- Decorative background text --}}
        <div class="absolute -right-24 -top-24 text-[12rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">WATCH</div>
        <div class="absolute inset-0 bg-emerald-500/[0.01] pointer-events-none group-hover:bg-emerald-500/[0.02] transition-colors"></div>

        @csrf
        <input type="hidden" name="type" value="cctv">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16 relative z-10">
            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Environment Architecture
                </label>
                <div class="relative">
                    <select name="location_type" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-indigo-500/30">
                        <option value="residential">Residential / Private Terminal</option>
                        <option value="commercial">Commercial / Workspace Hub</option>
                        <option value="industrial">Industrial / Logistic Node</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-purple-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]"></span>
                    Sentinel Node Count
                </label>
                <div class="relative">
                    <input type="number" name="camera_count" min="1" max="64" placeholder="4" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800">
                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-700 font-black text-[10px] uppercase tracking-widest pointer-events-none">Nodes</span>
                </div>
            </div>

            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-blue-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                    Node Placement Protocol
                </label>
                <div class="relative">
                    <select name="placement" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-blue-500/30">
                        <option value="indoor">Internal Vectors Only</option>
                        <option value="outdoor">External Perimeter Only</option>
                        <option value="mixed">Unified (Internal & External)</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-amber-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></span>
                    Deployment Window
                </label>
                <div class="relative">
                    <input type="date" name="preferred_date" class="super-input w-full py-5 appearance-none text-slate-300 font-black tracking-widest uppercase cursor-pointer" min="{{ date('Y-m-d') }}">
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-16 relative z-10 group/field">
            <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 mb-5 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">Deployment Coordinates (Extraction Registry)</label>
            <textarea name="delivery_address" required rows="3" placeholder="Specify the exact terminal for hardware deployment, zone units, and perimeter access signatures..." class="super-input w-full resize-none py-6 leading-relaxed font-semibold text-slate-300 placeholder-slate-800">{{ auth('customer')->user()->address ?? '' }}</textarea>
        </div>

        <div class="mb-20 relative z-10 group/field">
            <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 mb-5 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-emerald-400 transition-colors">Sentinel Configuration Notes</label>
            <textarea name="problem" required rows="5" placeholder="Specify tactical requirements: Thermal imaging, Night-vision neural links, Mass storage array retention, Remote node access protocols..." class="super-input w-full resize-none py-6 leading-relaxed font-semibold text-slate-300 placeholder-slate-800"></textarea>
        </div>

        <div class="pt-8 relative z-10">
            <button type="submit" class="w-full py-7 rounded-[2.5rem] bg-indigo-500 text-white text-[12px] font-black uppercase tracking-[0.4em] hover:bg-indigo-400 transition-all shadow-[0_20px_60px_rgba(99,102,241,0.4)] flex items-center justify-center gap-6 group hover:scale-[1.02] active:scale-95">
                Initialize Grid Deployment
                <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
            <p class="text-center mt-10 text-[9px] font-black text-slate-700 uppercase tracking-[0.4em] opacity-40">By initializing, you authorize the deployment of Sentinel nodes and agree to operational survey fees.</p>
        </div>
    </form>
</div>
@endsection
