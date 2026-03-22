@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.laptops.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Laptops Inventory
                </a>
                <h1 class="text-3xl font-black text-slate-900">Add <span class="text-red-600">Used Laptop</span></h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Enter specifications and upload photos for the second-hand laptop.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm">
                <ul class="list-disc pl-5 font-medium text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-slate-100">
            <form action="{{ route('admin.laptops.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Brand</label>
                            <input type="text" name="brand" value="{{ old('brand') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. Dell, HP, Lenovo">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Model</label>
                            <input type="text" name="model" value="{{ old('model') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. Latitude 5490">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Processor (CPU)</label>
                            <input type="text" name="processor" value="{{ old('processor') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. Intel Core i5 8th Gen">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Graphics Card (GPU)</label>
                            <input type="text" name="gpu" value="{{ old('gpu') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. NVIDIA RTX 3060 6GB">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">RAM</label>
                            <input type="text" name="ram" value="{{ old('ram') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. 8GB DDR4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Storage</label>
                            <input type="text" name="storage" value="{{ old('storage') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. 256GB SSD">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Price (₹)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Stock / Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock', 1) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="Number of units">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Description / Condition Info</label>
                        <textarea name="description" rows="4" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="Provide any additional details about the laptop's condition, scratches, missing components, etc.">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6 items-center">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Upload Photo</label>
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Status</label>
                            <select name="status" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available for Sale</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold Out</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-lg shadow-slate-200 transition-all">
                            Save Laptop to Database
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
