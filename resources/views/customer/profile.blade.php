@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-6xl mx-auto min-h-[70vh] pb-24">

    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16 px-4">
        <div class="flex items-center gap-10">
            <div class="w-24 h-24 rounded-[2rem] bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute top-0 right-0 w-8 h-8 bg-indigo-500/10 blur-xl"></div>
                <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h1 class="text-5xl font-black text-white tracking-tighter mb-4">Identity Registry</h1>
                <p class="text-slate-500 font-medium text-lg leading-relaxed max-w-xl">Manage your core biological identifiers and security protocols linked to this terminal.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 shrink-0 bg-white/5 p-4 rounded-[2rem] border border-white/5 backdrop-blur-3xl shadow-2xl">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500/40">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
            </div>
            <div class="pr-2">
                <p class="text-white font-black text-[10px] uppercase tracking-widest">Auth Status</p>
                <p class="text-emerald-400 font-black text-[9px] uppercase tracking-widest">Linked & Secured</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 px-4">
        
        <!-- Navigation Hub -->
        <div class="lg:col-span-1">
            <div class="super-card p-4 sticky top-28 border-white/5 bg-slate-950/40 backdrop-blur-3xl rounded-[2.5rem] shadow-2xl">
                <nav class="space-y-3">
                    <a href="#" class="flex items-center gap-4 px-6 py-5 bg-indigo-500 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl shadow-indigo-500/30 transition-all scale-100 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Personal Node
                    </a>
                    <a href="#" class="flex items-center gap-4 px-6 py-5 text-slate-500 hover:bg-white/5 hover:text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] transition-all group scale-100 hover:scale-[1.02] active:scale-95">
                        <svg class="w-5 h-5 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Market Credits
                    </a>
                    <a href="#" class="flex items-center gap-4 px-6 py-5 text-slate-500 hover:bg-white/5 hover:text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] transition-all group scale-100 hover:scale-[1.02] active:scale-95">
                        <svg class="w-5 h-5 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Signal Config
                    </a>
                    
                    <div class="h-px bg-white/5 my-6 mx-4"></div>
                    
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-4 px-6 py-5 text-red-500/60 hover:text-red-500 hover:bg-red-500/10 rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] transition-all group scale-100 hover:scale-[1.02] active:scale-95">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Terminate Session
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Credential Arrays -->
        <div class="lg:col-span-3 space-y-12">
            
            <!-- Registry Update -->
            <div class="super-card p-12 lg:p-16 relative overflow-hidden group rounded-[3.5rem] bg-slate-950/40 backdrop-blur-3xl border-white/5">
                {{-- Decorative background text --}}
                <div class="absolute -right-24 -top-24 text-[12rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">ENTITY</div>
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.02] transition-colors"></div>
                
                <h2 class="text-[10px] font-black text-white uppercase tracking-[0.4em] mb-12 flex items-center gap-6 relative z-10">
                    <span class="w-12 h-px bg-indigo-500/30"></span>
                    Base Diagnostics
                </h2>

                <form class="space-y-10 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 group/field">
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">Biological Identity</label>
                            <input type="text" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800" value="{{ auth('customer')->user()->name }}">
                        </div>
                        <div class="space-y-4 group/field">
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">Comms Protocol (Phone)</label>
                            <input type="text" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800" value="{{ auth('customer')->user()->phone ?? 'NOT_SET' }}">
                        </div>
                    </div>
                    
                    <div class="space-y-4 group/field">
                        <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2">Primary Access Hash (Email)</label>
                        <div class="relative group/input">
                            <input type="email" class="super-input w-full py-5 opacity-40 cursor-not-allowed bg-slate-950/50 text-slate-400 font-bold" value="{{ auth('customer')->user()->email }}" readonly>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-[9px] font-black text-slate-700 uppercase tracking-widest bg-slate-900 px-3 py-1 rounded-lg border border-white/5">Locked Matrix</div>
                        </div>
                    </div>

                    <div class="space-y-4 group/field">
                        <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-indigo-400 transition-colors">Geospatial Coordinates (Address)</label>
                        <textarea class="super-input w-full resize-none py-6 leading-relaxed font-semibold text-slate-300 placeholder-slate-800" rows="4" placeholder="Specify extraction coordinates...">{{ auth('customer')->user()->address }}</textarea>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="button" class="px-16 py-6 rounded-3xl bg-indigo-500 text-white font-black text-[11px] uppercase tracking-[0.3em] hover:bg-indigo-400 transition-all shadow-2xl shadow-indigo-500/40 hover:scale-[1.02] active:scale-95" onclick="alert('Registry Synchronized.')">
                            Synchronize Registry
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Rotation -->
            <div class="super-card p-12 lg:p-16 relative overflow-hidden group rounded-[3.5rem] bg-slate-950/40 backdrop-blur-3xl border-white/5 border-t-indigo-500/20">
                <div class="absolute -right-24 -top-24 text-[12rem] font-black text-red-500/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">SHIELD</div>
                <div class="absolute inset-0 bg-red-500/[0.01] pointer-events-none group-hover:bg-red-500/[0.02] transition-colors"></div>
                
                <h2 class="text-[10px] font-black text-white uppercase tracking-[0.4em] mb-12 flex items-center gap-6 relative z-10">
                    <span class="w-12 h-px bg-red-500/30"></span>
                    Entropy Rotation
                </h2>

                <form class="space-y-10 relative z-10">
                    <div class="space-y-4 group/field">
                        <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-red-400 transition-colors">Current Validation Hash</label>
                        <input type="password" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800" placeholder="••••••••••••••••">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4 group/field">
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-red-400 transition-colors">New Entropy Sequence</label>
                            <input type="password" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800" placeholder="••••••••••••••••">
                        </div>
                        <div class="space-y-4 group/field">
                            <label class="block text-[10px] font-black text-slate-600 uppercase tracking-[0.3em] ml-2 group-focus-within/field:text-red-400 transition-colors">Confirm Sequence</label>
                            <input type="password" class="super-input w-full py-5 text-white font-black tracking-widest placeholder-slate-800" placeholder="••••••••••••••••">
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="button" class="w-full sm:w-auto px-16 py-6 rounded-3xl bg-slate-900 text-red-400 border border-red-500/30 font-black text-[11px] uppercase tracking-[0.3em] hover:bg-red-500 hover:text-white transition-all shadow-2xl hover:scale-[1.02] active:scale-95" onclick="alert('Entropy Successfully Rotated.')">
                            Rotate Credentials
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
