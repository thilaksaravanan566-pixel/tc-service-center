@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Stock <span class="text-indigo-500">Intelligence</span></h1>
            <p class="text-gray-400 text-sm">Real-time audit log of all inventory movements across the network.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#13131f] p-6 rounded-3xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Total SKU</p>
            <p class="text-3xl font-black text-white">{{ \App\Models\Product::count() }}</p>
        </div>
        <div class="bg-[#13131f] p-6 rounded-3xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Monthly Inflow</p>
            <p class="text-3xl font-black text-emerald-500">{{ \App\Models\InventoryLog::where('type', 'IN')->where('created_at', '>=', now()->startOfMonth())->sum('quantity') }}</p>
        </div>
        <div class="bg-[#13131f] p-6 rounded-3xl border border-white/5">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Monthly Outflow</p>
            <p class="text-3xl font-black text-rose-500">{{ \App\Models\InventoryLog::where('type', 'OUT')->where('created_at', '>=', now()->startOfMonth())->sum('quantity') }}</p>
        </div>
    </div>

    <div class="bg-[#13131f] rounded-3xl border border-white/5 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Product</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Entity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse(\App\Models\InventoryLog::with(['product', 'dealer'])->latest()->paginate(20) as $log)
                <tr class="hover:bg-white/[0.02] transition">
                    <td class="px-6 py-4">
                        <p class="text-xs font-bold text-gray-300">{{ $log->created_at->format('d M, H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-black text-white truncate w-48">{{ $log->product->name ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold {{ $log->dealer_id ? 'text-indigo-400' : 'text-emerald-400' }}">
                            {{ $log->dealer->business_name ?? 'CENTRAL DEPOT' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-[9px] font-black {{ $log->type === 'IN' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                            {{ $log->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm font-black text-white">{{ $log->quantity }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-gray-500 italic">{{ $log->description }}</p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-600 italic">No inventory movements recorded.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-white/5">
            {{ \App\Models\InventoryLog::latest()->paginate(20)->links() }}
        </div>
    </div>
</div>
@endsection
