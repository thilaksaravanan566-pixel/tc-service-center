@extends('layouts.admin')

@section('title', 'Delivery Partners — TC Service Center')

@section('content')
<div class="p-6 lg:p-8 min-h-screen" style="background: radial-gradient(ellipse at top left, rgba(251,146,60,0.06) 0%, transparent 60%)">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 fade-up">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #f97316, #ea580c)">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-white tracking-tight">Delivery <span class="text-orange-400">Partners</span></h1>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">Logistics Fleet Management</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.delivery-partners.create') }}"
               class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest text-white transition-all shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, #f97316, #ea580c)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Partner
            </a>
        </div>

        {{-- STATS BAR --}}
        @php
            $total = $partners->total();
            $online = \App\Models\User::whereIn('role', ['delivery_partner', 'delivery'])->where('is_online', true)->count();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 fade-up stagger-1">
            <div class="card p-5 border-white/5">
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Fleet</div>
                <div class="text-3xl font-black text-white italic">{{ $total }}</div>
                <div class="text-[10px] text-orange-400 font-bold uppercase mt-1">Registered Partners</div>
            </div>
            <div class="card p-5 border-white/5">
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Online Now</div>
                <div class="text-3xl font-black italic {{ $online > 0 ? 'text-emerald-400' : 'text-gray-600' }}">{{ $online }}</div>
                <div class="text-[10px] text-gray-500 font-bold uppercase mt-1">Active Partners</div>
            </div>
            <div class="card p-5 border-white/5 col-span-2 sm:col-span-1">
                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Offline</div>
                <div class="text-3xl font-black text-gray-600 italic">{{ $total - $online }}</div>
                <div class="text-[10px] text-gray-600 font-bold uppercase mt-1">Idle Partners</div>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 px-5 py-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 font-bold text-sm fade-up">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card overflow-hidden border-white/5 fade-up stagger-2">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Fleet Registry</h3>
                <a href="{{ route('admin.delivery.live-map') }}" class="flex items-center gap-2 text-[10px] font-black text-orange-400 hover:text-orange-300 uppercase tracking-widest transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Live Map
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-white/[0.02]">
                            <th class="p-5">Partner</th>
                            <th class="p-5">Contact</th>
                            <th class="p-5">Vehicle</th>
                            <th class="p-5 text-center">Status</th>
                            <th class="p-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse($partners as $partner)
                        <tr class="hover:bg-orange-500/[0.02] transition-all group">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm text-orange-400 flex-shrink-0" style="background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2)">
                                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-white italic">{{ $partner->name }}</p>
                                        <p class="text-[10px] text-gray-600 font-bold uppercase">ID-{{ str_pad($partner->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <p class="text-xs font-bold text-gray-300">{{ $partner->email }}</p>
                                @if($partner->mobile)
                                    <p class="text-[10px] text-gray-500 font-bold mt-0.5">📱 {{ $partner->mobile }}</p>
                                @endif
                            </td>
                            <td class="p-5">
                                @if($partner->vehicle_number)
                                    <span class="inline-block px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-black text-gray-300 tracking-wider uppercase">
                                        🚚 {{ $partner->vehicle_number }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-gray-700 font-bold uppercase">Not Set</span>
                                @endif
                            </td>
                            <td class="p-5 text-center">
                                @if($partner->is_online)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-500/10 text-gray-600 border border-gray-700/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-600"></span> Offline
                                    </span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('admin.delivery-partners.edit', $partner->id) }}"
                                       class="p-2 bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-white hover:bg-orange-500/10 hover:border-orange-500/30 transition-all"
                                       title="Edit Partner">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.delivery-partners.destroy', $partner->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove {{ $partner->name }} from the fleet?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-rose-400 hover:bg-rose-500/10 hover:border-rose-500/30 transition-all" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="w-16 h-16 rounded-3xl flex items-center justify-center mx-auto mb-4 bg-orange-500/10 border border-orange-500/20">
                                    <svg class="w-8 h-8 text-orange-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                </div>
                                <p class="text-gray-600 font-black uppercase tracking-widest text-xs mb-4">No Delivery Partners Registered</p>
                                <a href="{{ route('admin.delivery-partners.create') }}" class="text-orange-400 font-black hover:underline uppercase text-[10px] tracking-widest">Register your first partner →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($partners->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.01]">
                {{ $partners->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
