@extends('layouts.dealer')

@section('title', 'Hardware Movement Ledger')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Inventory <span class="text-indigo-400">Movement</span> Ledger</h1>
        <p class="text-gray-400 mt-2 font-medium tracking-tight">Audit trail of every stock entry and consumption within your local DC.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('dealer.inventory.index') }}" class="btn btn-secondary border border-white/5 bg-white/5 text-gray-300 hover:text-white transition">
            <i class="fas fa-warehouse mr-2"></i> Current Stock Status
        </a>
    </div>
</div>

<div class="card p-0 border-0 bg-white shadow-2xl rounded-[3rem] overflow-hidden group">
    <div class="px-10 py-8 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-black italic uppercase tracking-tighter text-gray-900">Digital Audit Trail</h3>
        <div class="text-right">
             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Events Records</p>
             <p class="text-sm font-black text-indigo-600">{{ $logs->total() }} LOGS</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Event Occurred</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Affected Catalog Item</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Transaction Scope</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Quantitative Change</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Adjustment Result</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition group/row">
                    <td class="px-10 py-6">
                        <p class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $log->created_at->format('d M, Y') }}</p>
                        <p class="text-[10px] text-gray-400 font-mono tracking-tighter">{{ $log->created_at->format('H:i:s A') }}</p>
                    </td>
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $log->type === 'IN' ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500' }} flex items-center justify-center shadow-sm">
                                <i class="fas {{ $log->type === 'IN' ? 'fa-arrow-down' : 'fa-arrow-up' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none mb-1 italic tracking-tighter">{{ $log->product->name }}</p>
                                <p class="text-[10px] font-mono text-gray-400">{{ $log->product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest leading-none mb-1 italic">{{ str_replace('_', ' ', $log->reference_type ?? 'Manual Adjustment') }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">Ref ID: {{ $log->reference_id ?? '--' }}</p>
                    </td>
                    <td class="px-10 py-6">
                         <div class="flex items-center gap-3">
                            <span class="text-xl font-black italic tracking-tighter {{ $log->type === 'IN' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $log->type === 'IN' ? '+' : '-' }}{{ $log->quantity }}
                            </span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Units</span>
                         </div>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <div class="inline-block text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">New Snapshot</p>
                            <p class="text-sm font-black text-gray-900 italic tracking-tighter">{{ $log->new_stock }} UNITS</p>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-widest italic opacity-40">No Movement Records Found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
