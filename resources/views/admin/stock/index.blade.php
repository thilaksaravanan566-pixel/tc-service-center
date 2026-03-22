@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Spareparts <span class="text-red-600">Database</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage spare parts and stock levels</p>
            </div>
            <a href="{{ route('admin.parts.create') }}" class="luxury-btn py-3 px-6 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg">+ Add Spare Part</a>
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
                        <th class="p-5">Part Details</th>
                        <th class="p-5">Category & Price</th>
                        <th class="p-5">Stock Level</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($spareParts)) @foreach($spareParts as $part)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center text-2xl relative">
                                    @if($part->image_path)
                                        <img src="{{ asset('storage/' . $part->image_path) }}" alt="{{ $part->name }}" class="w-full h-full object-cover">
                                    @elseif($part->category == 'RAM')
                                        🎛️
                                    @elseif($part->category == 'Storage')
                                        💾
                                    @elseif($part->category == 'Display')
                                        🖥️
                                    @elseif($part->category == 'Battery')
                                        🔋
                                    @elseif($part->category == 'Keyboard')
                                        ⌨️
                                    @elseif($part->category == 'Motherboard')
                                        🧮
                                    @elseif($part->category == 'Processor')
                                        🧠
                                    @elseif($part->category == 'Adapter')
                                        🔌
                                    @elseif($part->category == 'Cables')
                                        🔗
                                    @elseif($part->category == 'Monitor')
                                        🖥️
                                    @elseif($part->category == 'Desktop Cabinet')
                                        🗄️
                                    @elseif($part->category == 'CCTV Camera')
                                        📹
                                    @elseif($part->category == 'IC')
                                        🐜
                                    @elseif($part->category == 'IO IC')
                                        🐜
                                    @elseif($part->category == 'GPU')
                                        🎮
                                    @elseif($part->category == 'Thermal Paste')
                                        💧
                                    @elseif($part->category == 'Webcams')
                                        📷
                                    @elseif($part->category == 'Speakers')
                                        🔊
                                    @elseif($part->category == 'Panels')
                                        🔲
                                    @elseif($part->category == 'Mouse')
                                        🖱️
                                    @elseif($part->category == 'Wireless KB&Mouse')
                                        ⌨️🖱️
                                    @elseif($part->category == 'CMOS Battery')
                                        🪙
                                    @else
                                        ⚙️
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ $part->name }}</h3>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">
                                        SKU: TC-PART-{{ $part->id }}
                                        @if($part->color) <span class="text-slate-300 mx-1">|</span> <span class="text-blue-600 font-bold">{{ $part->color }}</span> @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-5">
                            <div class="text-xs text-slate-600 space-y-1">
                                <p><span class="font-bold text-[10px] uppercase tracking-widest text-blue-600">{{ $part->category }}</span></p>
                                <p class="text-lg font-black text-slate-900">₹{{ number_format($part->price, 2) }}</p>
                            </div>
                        </td>

                        <td class="p-5">
                            <p class="text-[10px] font-black uppercase text-slate-400">In Stock</p>
                            <p class="text-sm font-bold {{ $part->stock <= 5 ? 'text-red-500' : 'text-slate-700' }}">{{ $part->stock }} Units</p>
                            @if($part->stock <= 5)
                                <span class="inline-block mt-1 bg-red-100 text-red-600 text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider">Low Stock</span>
                            @endif
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <a href="{{ route('admin.parts.edit', $part->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.parts.destroy', $part->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this spare part?');">
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
                            No spare parts registered in the database yet.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection