@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Stock <span class="text-red-600">Control</span></h1>
                <p class="text-sm text-slate-500 font-medium">Quickly update inventory levels for spare parts.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-luxury shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Part Details</th>
                        <th class="p-5">Category</th>
                        <th class="p-5 text-center">Current Stock</th>
                        <th class="p-5 text-right">Update Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($parts)) @foreach($parts as $part)
                    <tr class="hover:bg-red-50/30 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                                    <img src="{{ asset('storage/' . ($part->image ?? 'defaults/no-image.png')) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ $part->name }}</h3>
                                    <p class="text-[10px] text-slate-400 font-medium">SKU: TC-PART-{{ $part->id }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-5">
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $part->category }}
                            </span>
                        </td>

                        <td class="p-5 text-center">
                            <span class="font-black text-lg {{ $part->stock <= 5 ? 'text-red-600' : 'text-slate-700' }}">
                                {{ $part->stock }} 
                            </span>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-tighter block mt-1">Units</span>
                        </td>

                        <td class="p-5 text-right">
                            <form action="{{ route('admin.inventory.updateStock', $part->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" class="w-16 p-2 text-center border border-slate-200 rounded-lg outline-none focus:border-red-500 font-bold text-slate-700">
                                <button type="submit" class="bg-slate-900 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-xs transition-colors shadow-sm">
                                    + ADD
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No parts available in DB. Go to Spare Parts DB to add them.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
