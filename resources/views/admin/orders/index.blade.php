@extends('layouts.admin')

@section('title', 'Corporate Partner Orders')

@section('content')
<div class="mb-8 flex items-end justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Corporate Partner Orders</h1>
        <p class="text-gray-500 mt-2 font-medium">Review B2B purchase requests, approve shipments, and manage dealer inventories.</p>
    </div>
    <div class="flex gap-3">
        <div class="px-4 py-2 bg-gray-100 rounded-xl border border-gray-200">
            <p class="text-[10px] font-bold text-gray-400 uppercase">Incoming Volume</p>
            <p class="text-lg font-bold text-gray-700">{{ $orders->count() }} Orders</p>
        </div>
        <div class="px-4 py-2 bg-yellow-50 rounded-xl border border-yellow-100">
            <p class="text-[10px] font-bold text-yellow-600 uppercase">Awaiting Action</p>
            <p class="text-lg font-bold text-yellow-700">{{ $orders->where('status', 'pending')->count() }} Pending</p>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Order Details</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Partner Entity</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Total Valuation</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Process Stage</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Billing</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @foreach($orders as $order)
                <tr class="hover:bg-blue-50/20 transition group">
                    <td class="px-6 py-4">
                        <p class="font-bold text-sm group-hover:text-blue-600 transition">{{ $order->order_number }}</p>
                        <p class="text-[10px] text-gray-400 font-mono">{{ $order->order_date->format('d M, Y H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs uppercase">
                                {{ substr($order->dealer->business_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ $order->dealer->business_name }}</p>
                                <p class="text-[11px] text-gray-400">{{ $order->dealer->user->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-extrabold text-blue-900">₹{{ number_format($order->total_amount, 2) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-tighter
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-700 @elseif($order->status === 'approved') bg-blue-100 text-blue-700 @elseif($order->status === 'delivered') bg-green-100 text-green-700 @else bg-gray-100 text-gray-700 @endif">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'text-green-500' : 'text-red-400' }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                             <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary bg-white border border-gray-200 hover:shadow-sm">
                                <i class="fas fa-eye mr-2"></i> Inspect
                             </a>
                             @if($order->status === 'approved')
                             <a href="{{ route('admin.logistics.createShipment', $order->id) }}" class="btn btn-sm btn-primary shadow-lg shadow-blue-500/10">
                                <i class="fas fa-truck mr-1"></i> Ship
                             </a>
                             @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
</div>
@endsection
