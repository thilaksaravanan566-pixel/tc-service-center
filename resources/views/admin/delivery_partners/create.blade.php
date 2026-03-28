@extends('layouts.admin')

@section('title', 'Add Delivery Partner')

@section('content')
<div class="p-6 lg:p-8 min-h-screen" style="background: radial-gradient(ellipse at top left, rgba(251,146,60,0.06) 0%, transparent 60%)">
    <div class="max-w-2xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div class="fade-up">
            <a href="{{ route('admin.delivery-partners.index') }}" class="text-gray-500 hover:text-orange-400 text-xs font-black flex items-center gap-2 mb-4 uppercase tracking-widest transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Fleet
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #f97316, #ea580c)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Register <span class="text-orange-400">Delivery Partner</span></h1>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">New logistics agent onboarding</p>
                </div>
            </div>
        </div>

        {{-- ERROR MESSAGES --}}
        @if ($errors->any())
            <div class="px-5 py-4 rounded-xl border border-rose-500/20 bg-rose-500/10 text-rose-400 font-bold text-sm fade-up">
                <ul class="space-y-1 list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="card border-white/5 fade-up stagger-1">
            <div class="p-6 border-b border-white/5 bg-white/[0.01]">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Logistics Account Details</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.delivery-partners.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Name & Email --}}
                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="e.g. Rajan Kumar"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="delivery@email.com"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                        </div>
                    </div>

                    {{-- Mobile & Vehicle --}}
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}"
                                   placeholder="+91 99999 99999"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Vehicle Number</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                                   placeholder="TN-01-AB-1234"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700 uppercase">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="pt-4 border-t border-white/5">
                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Set Login Password</div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Password <span class="text-rose-500">*</span></label>
                                <input type="password" name="password" required
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Confirm Password <span class="text-rose-500">*</span></label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4 border-t border-white/5">
                        <button type="submit"
                                class="w-full py-3.5 rounded-xl font-black text-xs uppercase tracking-widest text-white transition-all shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg, #f97316, #ea580c)">
                            🚚 Register Delivery Partner
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
