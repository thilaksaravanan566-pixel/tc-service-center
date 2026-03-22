@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-6xl mx-auto pb-24">
    
    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-12 mb-16 px-4">
        <div class="flex items-center gap-10">
            <div class="w-24 h-24 rounded-[2rem] bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute top-0 right-0 w-8 h-8 bg-indigo-500/10 blur-xl"></div>
                <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <h1 class="text-5xl font-black text-white tracking-tighter mb-4">Custom Node Assembly</h1>
                <p class="text-slate-500 font-medium text-lg leading-relaxed max-w-2xl">Architect your unique compute terminal with precision components and active cooling protocols. Engineered for extremis performance.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 shrink-0 bg-white/5 p-4 rounded-[2rem] border border-white/5 backdrop-blur-3xl shadow-2xl">
            <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-500/40">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
            </div>
            <div class="pr-2">
                <p class="text-white font-black text-[10px] uppercase tracking-widest">Assembly Status</p>
                <p class="text-indigo-400 font-black text-[9px] uppercase tracking-widest">Architects Online</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mx-4 mb-12 bg-red-500/10 text-red-400 border border-red-500/20 p-8 rounded-[2.5rem] shadow-2xl animate-shake">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em]">Assembly Fault Registry</p>
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
        <div class="absolute -right-24 -top-24 text-[12rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">BUILD</div>
        <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.02] transition-colors"></div>

        @csrf
        <input type="hidden" name="type" value="desktop_assemble">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16 relative z-10">
            <div class="md:col-span-2 space-y-6 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-emerald-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                    Acquisition Budget Flow (₹)
                </label>
                <div class="relative">
                    <span class="absolute left-8 top-1/2 -translate-y-1/2 text-slate-800 font-black text-4xl group-focus-within/field:text-emerald-500 transition-colors">₹</span>
                    <input type="number" name="budget" placeholder="50,000" class="super-input w-full pl-20 text-4xl py-10 font-black tracking-tighter bg-slate-950/80" required>
                </div>
            </div>
            
            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Processor Architecture
                </label>
                <div class="relative">
                    <select name="processor" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-indigo-500/30 font-bold text-slate-300 py-5">
                        <option value="intel_i5">Intel Core i5 - Tactical Midrange</option>
                        <option value="intel_i7">Intel Core i7 - High Resonant</option>
                        <option value="intel_i9">Intel Core i9 - Ultra Compute</option>
                        <option value="ryzen_5">AMD Ryzen 5 - Balanced Stream</option>
                        <option value="ryzen_7">AMD Ryzen 7 - Multicore Master</option>
                        <option value="ryzen_9">AMD Ryzen 9 - Workstation Node</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-purple-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-purple-500 shadow-[0_0_10px_rgba(168,85,247,0.5)]"></span>
                    Memory Pool (RAM)
                </label>
                <div class="relative">
                    <select name="ram" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-purple-500/30 font-bold text-slate-300 py-5">
                        <option value="8gb">08 GB - Basic Sync</option>
                        <option value="16gb">16 GB - Gaming Standard</option>
                        <option value="32gb">32 GB - Creative Protocol</option>
                        <option value="64gb">64 GB - Extreme Payload</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-blue-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                    Graphics Engine (GPU)
                </label>
                <div class="relative">
                    <select name="gpu" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-blue-500/30 font-bold text-slate-300 py-5">
                        <option value="integrated">Integrated Visual Link</option>
                        <option value="rtx_3060">NVIDIA RTX 3060 - Raytrace Entry</option>
                        <option value="rtx_4070">NVIDIA RTX 4070 - High Fidelity</option>
                        <option value="rtx_4090">NVIDIA RTX 4090 - Ultimate Raytrace</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-4 group/field">
                <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-amber-400 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></span>
                    Primary Storage Repository
                </label>
                <div class="relative">
                    <select name="storage" class="super-input w-full bg-slate-950/80 pr-16 appearance-none cursor-pointer hover:border-amber-500/30 font-bold text-slate-300 py-5">
                        <option value="500gb_nvme">500GB NVMe - Rapid Access</option>
                        <option value="1tb_nvme">1TB NVMe - Extended Archive</option>
                        <option value="2tb_nvme">2TB NVMe - Mass Storage</option>
                    </select>
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-20 relative z-10 group/field">
            <label class="flex items-center gap-3 text-[10px] font-black text-slate-600 mb-5 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">Operational Objectives / Compute Mandate</label>
            <textarea name="problem" required rows="6" placeholder="Specify mission objectives: 4K Neural Rendering, Competitive Latency Gaming, Multi-stream Transcoding, AI Model Training..." class="super-input w-full resize-none py-6 leading-relaxed font-semibold text-slate-300 placeholder-slate-800">{{ old('problem') }}</textarea>
        </div>

        <div class="pt-8 relative z-10">
            <button type="submit" class="w-full py-7 rounded-[2.5rem] bg-indigo-500 text-white text-[12px] font-black uppercase tracking-[0.4em] hover:bg-indigo-400 transition-all shadow-[0_20px_60px_rgba(99,102,241,0.4)] flex items-center justify-center gap-6 group hover:scale-[1.02] active:scale-95">
                Initialize Build Sequence
                <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
            <p class="text-center mt-10 text-[9px] font-black text-slate-700 uppercase tracking-[0.4em] opacity-40">By initializing, you confirm the acquisition request and agree to the component procurement timeline.</p>
        </div>
    </form>
</div>
@endsection
