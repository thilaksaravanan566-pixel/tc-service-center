@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900">Control <span class="text-blue-600">Center</span></h2>
        <p class="text-slate-500">Real-time performance of TC Service Center.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Jobs</p>
            <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $stats['pending_repairs'] ?? 0 }}</h3>
            <div class="mt-4 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500" style="width: 70%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">In Packing</p>
            <h3 class="text-4xl font-black text-blue-600 mt-2">{{ $stats['packing_count'] ?? 0 }}</h3>
            <p class="text-[10px] text-blue-400 mt-2 font-bold italic">Ready for shipment</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Low Stock Parts</p>
            <h3 class="text-4xl font-black text-red-500 mt-2">{{ $stats['low_stock'] ?? 0 }}</h3>
            <p class="text-[10px] text-red-400 mt-2 font-bold italic underline">Check Inventory →</p>
        </div>

        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl text-white">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Est. Revenue</p>
            <h3 class="text-4xl font-black text-white mt-2">₹{{ number_format($stats['revenue'] ?? 0, 2) }}</h3>
            <p class="text-[10px] text-green-400 mt-2 font-bold uppercase">Paid & Pending</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xl font-bold text-slate-900">Recent Service Orders</h4>
                <a href="#" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase">View All</a>
            </div>
            
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase font-black border-b border-slate-50">
                        <th class="pb-4">Job ID</th>
                        <th class="pb-4">Device (RAM/CPU)</th>
                        <th class="pb-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    {{-- This will be a loop later --}}
                    <tr class="border-b border-slate-50">
                        <td class="py-4 font-bold text-blue-600">TC-2026-001</td>
                        <td class="py-4">
                            <span class="block font-bold">Dell Latitude 5420</span>
                            <span class="text-[10px] text-slate-400">Core i7 | 16GB DDR4</span>
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black rounded-full uppercase tracking-tighter">Packing</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
            <h4 class="text-xl font-bold text-slate-900 mb-6 italic underline">Stock Inventory</h4>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold">Samsung NVMe SSD</p>
                        <p class="text-[10px] text-slate-400 tracking-widest">CATEGORY: STORAGE</p>
                    </div>
                    <span class="text-lg font-black text-slate-900">10</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-500 italic">DDR4 8GB RAM</p>
                        <p class="text-[10px] text-slate-400 tracking-widest uppercase">Low Stock</p>
                    </div>
                    <span class="text-lg font-black text-red-500">2</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection