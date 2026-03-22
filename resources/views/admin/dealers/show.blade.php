@extends('layouts.admin')

@section('title', 'Dealer Details: ' . $dealer->user->name)

@section('content')
<div class="mb-6 flex flex-wrap justify-between items-end gap-4">
    <div>
        <a href="{{ route('admin.dealers.index') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Dealers
        </a>
        <h2 class="text-2xl font-bold text-white tracking-tight mt-2">{{ $dealer->business_name }}</h2>
        <div class="flex items-center gap-3 mt-1">
            <span class="text-sm text-gray-400">Partner since {{ $dealer->created_at->format('M Y') }}</span>
            <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
            <span class="text-sm text-indigo-400 font-medium">{{ $dealer->user->name }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dealers.edit', $dealer) }}" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm font-medium text-gray-300 hover:text-white hover:bg-white/10 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Edit Profile
        </a>
        <form action="{{ route('admin.dealers.destroy', $dealer) }}" method="POST" onsubmit="return confirm('Deactivate this dealer?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Deactivate
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Stats Cards --}}
    <div class="card p-5 flex flex-col justify-between">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Total Service Orders</span>
        <div class="flex items-end justify-between mt-2">
            <span class="text-3xl font-bold text-white">{{ $dealer->user->serviceOrders->count() }}</span>
            <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>
    </div>
    
    <div class="card p-5 flex flex-col justify-between">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Revenue Generated</span>
        <div class="flex items-end justify-between mt-2">
            @php
                $revenue = $dealer->user->serviceOrders->where('is_paid', true)->sum('estimated_cost');
            @endphp
            <span class="text-3xl font-bold text-white">₹{{ number_format($revenue, 2) }}</span>
            <div class="p-2 bg-emerald-500/10 rounded-lg text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="card p-5 flex flex-col justify-between">
        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Active Status</span>
        <div class="flex items-end justify-between mt-2">
            <span class="text-2xl font-bold {{ $dealer->status === 'active' ? 'text-emerald-400' : 'text-gray-500' }}">{{ ucfirst($dealer->status) }}</span>
            <div class="p-2 {{ $dealer->status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400' }} rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: Profile Info --}}
    <div class="space-y-6">
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 mb-5">Dealer Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase">Contact Email</p>
                    <p class="text-sm text-gray-200 mt-0.5">{{ $dealer->user->email }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase">Phone Number</p>
                    <p class="text-sm text-gray-200 mt-0.5">{{ $dealer->phone }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase">GST Number</p>
                    <p class="text-sm text-gray-200 mt-0.5">{{ $dealer->gst_number ?? 'Not Provided' }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase">Business Address</p>
                    <p class="text-sm text-gray-200 mt-0.5 leading-relaxed">{{ $dealer->address }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 mb-5">Recent Invoices</h3>
            <div class="space-y-3">
                @php
                    $recentInvoices = $dealer->user->serviceOrders->pluck('invoices')->collapse()->sortByDesc('created_at')->take(5);
                @endphp
                @forelse($recentInvoices as $inv)
                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition group">
                    <div>
                        <p class="text-xs font-semibold text-white group-hover:text-indigo-400">{{ $inv->invoice_number }}</p>
                        <p class="text-[10px] text-gray-500">{{ $inv->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-white">₹{{ number_format($inv->total, 2) }}</p>
                        <span class="text-[9px] px-1.5 py-0.5 rounded {{ $inv->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">{{ strtoupper($inv->status) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500 text-center py-4">No invoices yet.</p>
                @endforelse
            </div>
            @if($dealer->user->serviceOrders->pluck('invoices')->collapse()->count() > 5)
            <a href="#" class="block text-center text-xs text-indigo-400 hover:text-indigo-300 font-medium mt-4">View All Invoices</a>
            @endif
        </div>
    </div>

    {{-- Right: Service Orders --}}
    <div class="lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Service Bookings & Activity</h3>
                <span class="text-[10px] text-gray-500">{{ $dealer->user->serviceOrders->count() }} Orders</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Device</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($dealer->user->serviceOrders->sortByDesc('created_at')->take(10) as $order)
                        <tr class="group">
                            <td class="whitespace-nowrap">
                                <span class="text-xs font-bold text-indigo-400">{{ $order->tc_job_id }}</span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-300">{{ $order->device->brand ?? '' }} {{ $order->device->model ?? 'Unknown' }}</span>
                                    <span class="text-[10px] text-gray-600 truncate max-w-[150px]">{{ $order->fault_details }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $order->status === 'delivered' ? 'badge-blue' : 'badge-yellow' }}">{{ ucfirst($order->status ?? 'Received') }}</span>
                            </td>
                            <td>
                                @if($order->is_paid)
                                    <span class="text-emerald-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span class="text-[11px] font-semibold">PAID</span>
                                    </span>
                                @else
                                    <span class="text-red-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                        <span class="text-[11px] font-semibold">UNPAID</span>
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs text-gray-500">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.services.show', $order) }}" class="p-1.5 hover:text-white hover:bg-white/10 rounded transition inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 text-xs">No service bookings found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dealer->user->serviceOrders->count() > 10)
            <div class="px-6 py-3 border-t border-white/5 bg-white/[0.01]">
                <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">View Complete Shipment & Order History →</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
