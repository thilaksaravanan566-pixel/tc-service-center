@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Promotions & <span class="text-red-600">Offers</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage advertisements displayed to customers</p>
            </div>
            <a href="{{ route('admin.offers.create') }}" class="luxury-btn py-3 px-6 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg">+ New Banner</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(!empty($offers)) @foreach($offers as $offer)
                <div class="bg-white rounded-3xl overflow-hidden shadow-md border border-slate-100 flex flex-col group hover:shadow-xl transition-all">
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                        @if($offer->image_path)
                            <img src="{{ asset('storage/' . $offer->image_path) }}" alt="{{ $offer->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <span class="text-6xl">🎁</span>
                        @endif
                        <div class="absolute top-4 right-4 text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full {{ $offer->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }} shadow-lg">
                            {{ $offer->is_active ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="font-black text-lg text-slate-900">{{ $offer->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 flex-1 line-clamp-3">{{ $offer->description }}</p>
                        
                        @if($offer->discount_code)
                        <div class="mt-4 bg-slate-50 p-3 rounded-xl border border-dashed border-slate-300 text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Promo Code</p>
                            <span class="font-mono font-black tracking-widest text-[#d4af37] text-lg">{{ $offer->discount_code }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="flex-1 text-center bg-slate-900 hover:bg-slate-800 text-white py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors">Edit</a>
                            
                            <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this promotion permanentely?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-500 hover:text-white text-red-500 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach @else
                <div class="col-span-full md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-3xl border border-slate-100">
                    <span class="text-6xl block mb-4">📣</span>
                    <h3 class="font-black text-xl text-slate-800">No Promotions Found</h3>
                    <p class="text-slate-500 text-sm mt-2 font-medium">Start advertising exclusive deals to your customers.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
