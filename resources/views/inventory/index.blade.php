@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-slate-900 mb-8 tracking-tight">TC Computer Spare Parts</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($spareParts as $part)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-slate-100">
                <div class="h-48 bg-slate-200 relative">
                    <img src="{{ asset('storage/' . $part->image_path) }}" alt="{{ $part->name }}" class="w-full h-full object-cover">
                    @if($part->stock <= 5)
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] px-2 py-1 rounded-full font-bold">ONLY {{ $part->stock }} LEFT</span>
                    @endif
                </div>

                <div class="p-5">
                    <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider">{{ $part->category }}</p>
                    <h3 class="text-lg font-bold text-slate-800 truncate">{{ $part->name }}</h3>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $part->description }}</p>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-slate-900">₹{{ number_format($part->price, 2) }}</span>
                        <button class="bg-yellow-400 hover:bg-yellow-500 text-slate-900 px-4 py-2 rounded-lg font-bold text-sm transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection