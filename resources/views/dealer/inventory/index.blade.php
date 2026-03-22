@extends('layouts.dealer')

@section('title', 'Local Virtual Inventory')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Retail <span class="text-emerald-500">Virtual</span> Stock</h1>
        <p class="text-gray-400 mt-2 font-medium tracking-tight">Real-time visibility of your local hardware assets and spare parts.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('dealer.inventory.logs') }}" class="btn btn-secondary border border-white/5 bg-white/5 text-gray-300 hover:text-white transition">
            <i class="fas fa-history mr-2"></i> Stock Movement Logs
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
    <div class="card p-8 border-0 bg-white shadow-2xl rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-50 rounded-full blur-3xl group-hover:bg-emerald-100 transition duration-700"></div>
        <div class="relative">
            <p class="text-xs uppercase font-extrabold text-gray-400 tracking-widest mb-4">Total SKUs Active</p>
            <p class="text-5xl font-black italic tracking-tighter text-gray-900 leading-none">{{ $inventory->total() }}</p>
            <p class="text-[10px] font-bold text-emerald-600 mt-4 uppercase flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Sync Verified
            </p>
        </div>
    </div>
    
    <div class="card p-8 border-0 bg-gray-900 text-white shadow-2xl rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition duration-700"></div>
        <div class="relative">
            <p class="text-xs uppercase font-extrabold text-gray-500 tracking-widest mb-4">Cumulative Units</p>
            <p class="text-5xl font-black italic tracking-tighter text-white leading-none">{{ $inventory->sum('stock_quantity') }}</p>
            <p class="text-[10px] font-bold text-indigo-400 mt-4 uppercase flex items-center gap-1">
                <i class="fas fa-warehouse"></i> Local DC Capacity
            </p>
        </div>
    </div>

    <div class="md:col-span-2 card p-8 border-0 bg-gradient-to-br from-indigo-600 to-blue-700 text-white shadow-2xl rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-3xl transition duration-1000 group-hover:scale-150"></div>
        <div class="relative flex items-center justify-between h-full">
            <div>
                <h3 class="text-2xl font-black italic uppercase tracking-tighter mb-2">Automated <br> Procurement</h3>
                <p class="text-xs text-indigo-100/60 font-medium leading-relaxed max-w-xs">System tracks your usage in service jobs and deducts stock from your local inventory automatically.</p>
            </div>
            <a href="{{ route('dealer.orders.create') }}" class="btn bg-white text-indigo-600 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 active:scale-95 transition-all">Restock Now</a>
        </div>
    </div>
</div>

<div class="card p-0 border-0 bg-white shadow-2xl rounded-[3rem] overflow-hidden group">
    <div class="px-10 py-8 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-black italic uppercase tracking-tighter text-gray-900">Inventory Catalog</h3>
        <div class="flex gap-4">
             <div class="relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" class="input p-3 pl-12 bg-gray-50 border-gray-100 rounded-2xl text-xs font-bold" placeholder="Filter by SKU or Brand...">
             </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Hardware Catalog Item</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Brand / Class</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Valuation (Dealer)</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Current Stock</th>
                    <th class="px-10 py-6 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($inventory as $item)
                <tr class="hover:bg-indigo-50/20 transition group/row">
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover/row:bg-white group-hover/row:text-indigo-600 group-hover/row:shadow-sm transition">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div>
                                <p class="text-base font-black text-gray-900 tracking-tighter leading-none mb-1 group-hover/row:text-indigo-600 transition">{{ $item->product->name }}</p>
                                <p class="text-[10px] font-mono text-gray-400 italic">SKU: {{ $item->product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-sm font-bold text-gray-700 leading-none mb-1">{{ $item->product->brand }}</p>
                        <p class="text-[10px] text-indigo-400 font-black uppercase tracking-widest italic">{{ $item->product->category }}</p>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-sm font-black text-gray-900 italic tracking-tighter">₹{{ number_format($item->product->dealer_price, 2) }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">B2B Base</p>
                    </td>
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-black italic tracking-tighter text-gray-900">{{ $item->stock_quantity }}</span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">UNITS</span>
                        </div>
                        <div class="w-24 h-1 bg-gray-100 rounded-full mt-2 overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min(($item->stock_quantity / 50) * 100, 100) }}%"></div>
                        </div>
                    </td>
                    <td class="px-10 py-6 text-right">
                        @if($item->stock_quantity > 10)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase rounded-lg">High Availability</span>
                        @elseif($item->stock_quantity > 0)
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[9px] font-black uppercase rounded-lg">Healthy Supply</span>
                        @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded-lg">Stock-Out Agent</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center opacity-40">
                            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                            <h4 class="text-lg font-black uppercase italic tracking-tighter">Warehouse Empty</h4>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Your local inventory is completely depleted. Restock from procurement shop.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-100">
        {{ $inventory->links() }}
    </div>
</div>
@endsection
