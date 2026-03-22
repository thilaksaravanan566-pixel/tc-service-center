@extends('layouts.dealer')

@section('title', 'Dealer Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-white tracking-tight">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
    <p class="text-gray-400 mt-2">Here's what's happening with your service orders today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    {{-- Stats Grid --}}
    <div class="card p-8 bg-indigo-600 border-0 shadow-2xl shadow-indigo-600/20 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition duration-700"></div>
        <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest leading-none mb-3">Service Pipeline</p>
        <p class="text-4xl font-black text-white italic tracking-tighter">{{ $stats['pending_orders'] }} <span class="text-xs font-bold text-indigo-300 ml-1">Jobs Active</span></p>
    </div>
    
    <div class="card p-8 bg-white border-0 shadow-xl shadow-black/5 relative overflow-hidden group">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-3">Local Inventory</p>
        <p class="text-4xl font-black text-slate-900 italic tracking-tighter">{{ $stats['inventory_sku_count'] }} <span class="text-xs font-bold text-slate-400 ml-1">SKU items</span></p>
    </div>

    <div class="card p-8 bg-white border-0 shadow-xl shadow-black/5 relative overflow-hidden group">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-3">Financial Settlement</p>
        <p class="text-4xl font-black text-slate-900 italic tracking-tighter">₹{{ number_format($stats['total_revenue'] / 1000, 1) }}k</p>
    </div>

    <div class="card p-8 @if($stats['pending_visits_count'] > 0) bg-rose-600 @else bg-emerald-600 @endif border-0 shadow-xl shadow-rose-600/10 relative overflow-hidden group">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition duration-700"></div>
        <p class="text-[10px] font-black text-white/60 uppercase tracking-widest leading-none mb-3">Field Surveillance</p>
        <p class="text-4xl font-black text-white italic tracking-tighter">{{ $stats['pending_visits_count'] }} <span class="text-xs font-bold text-white/60 ml-1">Visits Scheduled</span></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Recent Activity --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">Recent Service Orders</h3>
            <a href="{{ route('dealer.services.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">View all →</a>
        </div>

        <div class="card overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/5">
                        <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500">Job ID</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500">Device</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500">Status</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($stats['recent_orders'] as $order)
                    <tr class="hover:bg-white/5 transition cursor-pointer" onclick="window.location='{{ route('dealer.services.show', $order->id) }}'">
                        <td class="px-6 py-4 text-xs font-bold text-indigo-400">{{ $order->tc_job_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-300 font-medium">{{ $order->device->brand ?? '' }} {{ $order->device->model ?? 'Device' }}</td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $order->status === 'delivered' ? 'badge-blue' : 'badge-yellow' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 text-right">{{ $order->created_at->format('d M') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No recent bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="space-y-6">
        <h3 class="text-lg font-bold text-white">Quick Actions</h3>
        <div class="card p-6 space-y-4">
            <a href="{{ route('dealer.services.create') }}" class="flex items-center gap-3 p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-xl hover:bg-indigo-500/20 transition group">
                <div class="w-10 h-10 rounded-lg bg-indigo-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Book New Service</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Register a customer device for repair.</p>
                </div>
            </a>
            
            <a href="{{ route('dealer.invoices.index') }}" class="flex items-center gap-3 p-4 bg-white/5 border border-white/5 rounded-xl hover:bg-white/10 transition group">
                <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Financial View</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Check invoices and payments history.</p>
                </div>
            </a>
        </div>
        
        <div class="card p-6 bg-gradient-to-br from-indigo-900/40 to-black border-indigo-500/30">
            <h4 class="text-sm font-bold text-white mb-2">Need Help?</h4>
            <p class="text-xs text-gray-400 leading-relaxed mb-4">Contact our technical support line for emergency hardware issues or system assistance.</p>
            <a href="tel:+919876543210" class="text-sm font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Call Hardware Team
            </a>
        </div>
    </div>
</div>
@endsection
