@extends('layouts.dealer')

@section('title', 'Service Job List')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Service <span class="text-indigo-400">Operations</span></h1>
        <p class="text-gray-400 mt-2 font-medium tracking-tight">Track all customer hardware currently under technical surveillance at TC Center.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('dealer.services.create') }}" class="btn btn-primary bg-indigo-600 px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/30 font-black text-white flex items-center gap-3 cursor-pointer hover:scale-105 active:scale-95 transition-all">
            <i class="fas fa-plus-circle text-lg"></i>
            NEW SERVICE BOOKING
        </a>
    </div>
</div>

<div class="card p-0 border-0 bg-white shadow-2xl rounded-[3rem] overflow-hidden group">
    <div class="px-10 py-8 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-black italic uppercase tracking-tighter text-gray-900 leading-none">Job Manifest</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Job ID / Hardware</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Reported Fault</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Logistic Status</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Estimation</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right leading-none">Surveillance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($orders as $order)
                <tr class="hover:bg-indigo-50/20 transition group/row">
                    <td class="px-10 py-6">
                        <div>
                            <p class="text-base font-black text-gray-900 tracking-tighter leading-none mb-1 group-hover/row:text-indigo-600 transition">#{{ $order->tc_job_id }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic tracking-tighter">{{ $order->device->brand }} {{ $order->device->model }} ({{ $order->device->type }})</p>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-xs font-bold text-gray-700 leading-tight line-clamp-2 max-w-xsitalic italic">"{{ Str::limit($order->fault_details, 50) }}"</p>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-[10px] font-black text-gray-800 uppercase tracking-widest leading-none mb-1 italic">{{ str_replace('_', ' ', $order->delivery_type) }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Partner Pickup</p>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-sm font-black text-gray-900 italic tracking-tighter">₹{{ number_format($order->estimated_cost, 2) }}</p>
                        <span class="text-[9px] font-black uppercase tracking-widest {{ $order->is_paid ? 'text-emerald-500' : 'text-red-400' }}">
                            {{ $order->is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <div class="flex items-center justify-end gap-4">
                            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border shadow-sm
                                @if($order->status === 'completed') bg-emerald-100 text-emerald-700 border-emerald-100 @elseif($order->status === 'repairing') bg-blue-100 text-blue-700 border-blue-100 @else bg-gray-100 text-gray-700 border-gray-100 @endif">
                                {{ strtoupper($order->status) }}
                            </span>
                            <a href="{{ route('dealer.services.show', $order->id) }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition shadow-sm border border-gray-100">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-widest italic opacity-40">No Service Jobs Booked</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
</div>
@endsection
