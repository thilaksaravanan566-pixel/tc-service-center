@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Used Laptops <span class="text-red-600">Inventory</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage second-hand laptops, specs, photos, and prices.</p>
            </div>
            <a href="{{ route('admin.laptops.create') }}" class="luxury-btn py-3 px-6 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg">+ Add Laptop</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Laptop & Specs</th>
                        <th class="p-5">Description</th>
                        <th class="p-5">Price & Stock</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($laptops)) @foreach($laptops as $laptop)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center">
                                    @if($laptop->image)
                                        <img src="{{ asset('storage/' . $laptop->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl">💻</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm whitespace-pre-wrap">{{ $laptop->brand }} {{ $laptop->model }}</h3>
                                    <p class="text-[10px] text-slate-500 font-medium mt-1">
                                        <span class="font-bold text-slate-700">CPU:</span> {{ $laptop->processor }}<br>
                                        <span class="font-bold text-slate-700">GPU:</span> {{ $laptop->gpu ?: 'Integrated Info' }}<br>
                                        <span class="font-bold text-slate-700">RAM:</span> {{ $laptop->ram }} | <span class="font-bold text-slate-700">Storage:</span> {{ $laptop->storage }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-5">
                            <p class="text-xs text-slate-600 line-clamp-3 w-64">{{ $laptop->description ?: 'No description provided.' }}</p>
                        </td>

                        <td class="p-5">
                            <div class="space-y-1">
                                <p class="text-lg font-black text-slate-900">₹{{ number_format($laptop->price, 2) }}</p>
                                <p class="text-[10px] font-black uppercase text-slate-400">Stock: <span class="{{ $laptop->stock <= 0 ? 'text-red-500' : 'text-slate-700' }}">{{ $laptop->stock }}</span></p>
                                <span class="inline-block px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $laptop->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $laptop->status }}
                                </span>
                            </div>
                        </td>

                        <td class="p-5 text-right align-top">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <a href="{{ route('admin.laptops.edit', $laptop->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.laptops.destroy', $laptop->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this laptop from inventory?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No used laptops found in the database.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $laptops->links() }}
        </div>
    </div>
</div>
@endsection
