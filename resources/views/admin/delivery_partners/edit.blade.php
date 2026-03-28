@extends('layouts.admin')

@section('title', 'Edit Delivery Partner — {{ $partner->name }}')

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
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-lg text-orange-400" style="background: rgba(249,115,22,0.1); border:1px solid rgba(249,115,22,0.2)">
                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Edit <span class="text-orange-400">{{ $partner->name }}</span></h1>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Delivery Partner · ID-{{ str_pad($partner->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="px-5 py-4 rounded-xl border border-rose-500/20 bg-rose-500/10 text-rose-400 font-bold text-sm fade-up">
                <ul class="space-y-1 list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STATUS BANNER --}}
        <div class="card border-white/5 fade-up p-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($partner->is_online)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Currently Online
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-600 border border-gray-700/30">
                        <span class="w-2 h-2 rounded-full bg-gray-600"></span> Offline
                    </span>
                @endif
                @if($partner->vehicle_number)
                    <span class="text-xs font-black text-gray-400 italic">🚚 {{ $partner->vehicle_number }}</span>
                @endif
            </div>
            @if($partner->location_updated_at)
                <span class="text-[10px] text-gray-600 font-bold">Last seen: {{ \Carbon\Carbon::parse($partner->location_updated_at)->diffForHumans() }}</span>
            @endif
        </div>

        {{-- EDIT FORM --}}
        <div class="card border-white/5 fade-up stagger-1">
            <div class="p-6 border-b border-white/5 bg-white/[0.01]">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Update Account Details</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.delivery-partners.update', $partner->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $partner->email) }}" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ old('mobile', $partner->mobile) }}"
                                   placeholder="+91 99999 99999"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Vehicle Number</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $partner->vehicle_number) }}"
                                   placeholder="TN-01-AB-1234"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700 uppercase">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/5">
                        <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Reset Password <span class="text-gray-700">(leave blank to keep current)</span></div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">New Password</label>
                                <input type="password" name="password"
                                       placeholder="••••••••"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                       placeholder="••••••••"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white font-bold text-sm outline-none focus:border-orange-500 focus:bg-white/[0.07] transition-all placeholder-gray-700">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/5 flex gap-4">
                        <button type="submit"
                                class="flex-1 py-3.5 rounded-xl font-black text-xs uppercase tracking-widest text-white transition-all shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-0.5"
                                style="background: linear-gradient(135deg, #f97316, #ea580c)">
                            Save Changes
                        </button>
                        <form action="{{ route('admin.delivery-partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Remove {{ $partner->name }} from the fleet?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-3.5 rounded-xl font-black text-xs uppercase tracking-widest text-rose-400 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all">
                                Delete
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
