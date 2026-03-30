@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.parts.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Inventory
                </a>
                <h1 class="text-3xl font-black text-slate-900">Edit <span class="text-red-600">Spare Part</span></h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Update details for {{ $sparePart->name }}</p>
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
            <form action="{{ route('admin.parts.update', $sparePart->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Part Name</label>
                        <input type="text" name="name" value="{{ old('name', $sparePart->name) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Category</label>
                            <select name="category" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800 cursor-pointer">
                                <option class="text-slate-900 bg-white" value="RAM" {{ old('category', $sparePart->category) == 'RAM' ? 'selected' : '' }}>RAM Memory</option>
                                <option class="text-slate-900 bg-white" value="Storage" {{ old('category', $sparePart->category) == 'Storage' ? 'selected' : '' }}>Storage (SSD/HDD)</option>
                                <option class="text-slate-900 bg-white" value="Display" {{ old('category', $sparePart->category) == 'Display' ? 'selected' : '' }}>Display / Screen</option>
                                <option class="text-slate-900 bg-white" value="Battery" {{ old('category', $sparePart->category) == 'Battery' ? 'selected' : '' }}>Battery</option>
                                <option class="text-slate-900 bg-white" value="Keyboard" {{ old('category', $sparePart->category) == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                <option class="text-slate-900 bg-white" value="Motherboard" {{ old('category', $sparePart->category) == 'Motherboard' ? 'selected' : '' }}>Motherboard / Logic Board</option>
                                <option class="text-slate-900 bg-white" value="Processor" {{ old('category', $sparePart->category) == 'Processor' ? 'selected' : '' }}>Processor / CPU</option>
                                <option class="text-slate-900 bg-white" value="Adapter" {{ old('category', $sparePart->category) == 'Adapter' ? 'selected' : '' }}>Charger / Adapter</option>
                                <option class="text-slate-900 bg-white" value="Cables" {{ old('category', $sparePart->category) == 'Cables' ? 'selected' : '' }}>Cables & Connectors</option>
                                <option class="text-slate-900 bg-white" value="Monitor" {{ old('category', $sparePart->category) == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option class="text-slate-900 bg-white" value="Desktop Cabinet" {{ old('category', $sparePart->category) == 'Desktop Cabinet' ? 'selected' : '' }}>Desktop Cabinet</option>
                                <option class="text-slate-900 bg-white" value="CCTV Camera" {{ old('category', $sparePart->category) == 'CCTV Camera' ? 'selected' : '' }}>CCTV Camera</option>
                                <option class="text-slate-900 bg-white" value="IC" {{ old('category', $sparePart->category) == 'IC' ? 'selected' : '' }}>IC</option>
                                <option class="text-slate-900 bg-white" value="IO IC" {{ old('category', $sparePart->category) == 'IO IC' ? 'selected' : '' }}>IO IC</option>
                                <option class="text-slate-900 bg-white" value="GPU" {{ old('category', $sparePart->category) == 'GPU' ? 'selected' : '' }}>GPU / Graphics Card</option>
                                <option class="text-slate-900 bg-white" value="Thermal Paste" {{ old('category', $sparePart->category) == 'Thermal Paste' ? 'selected' : '' }}>Thermal Paste</option>
                                <option class="text-slate-900 bg-white" value="Webcams" {{ old('category', $sparePart->category) == 'Webcams' ? 'selected' : '' }}>Webcams</option>
                                <option class="text-slate-900 bg-white" value="Speakers" {{ old('category', $sparePart->category) == 'Speakers' ? 'selected' : '' }}>Speakers</option>
                                <option class="text-slate-900 bg-white" value="Panels" {{ old('category', $sparePart->category) == 'Panels' ? 'selected' : '' }}>A, B, C Panels</option>
                                <option class="text-slate-900 bg-white" value="Mouse" {{ old('category', $sparePart->category) == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                <option class="text-slate-900 bg-white" value="Wireless KB&Mouse" {{ old('category', $sparePart->category) == 'Wireless KB&Mouse' ? 'selected' : '' }}>Wireless KB & Mouse</option>
                                <option class="text-slate-900 bg-white" value="CMOS Battery" {{ old('category', $sparePart->category) == 'CMOS Battery' ? 'selected' : '' }}>CMOS Battery</option>
                                <option class="text-slate-900 bg-white" value="Other" {{ (old('category', $sparePart->category) == 'Other' || !in_array($sparePart->category, ['RAM','Storage','Display','Battery','Keyboard','Motherboard','Processor','Adapter','Cables','Monitor','Desktop Cabinet','CCTV Camera','IC','IO IC','GPU','Thermal Paste','Webcams','Speakers','Panels','Mouse','Wireless KB&Mouse','CMOS Battery'])) ? 'selected' : '' }}>Other Component</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Color (Optional)</label>
                            <input type="text" name="color" value="{{ old('color', $sparePart->color) }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Price (₹)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $sparePart->price) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Stock Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock', $sparePart->stock) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Part Photo (Optional)</label>
                        @if($sparePart->image_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $sparePart->image_path) }}" alt="Current Photo" class="h-24 w-24 object-cover rounded-xl shadow-md border border-slate-200">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        <p class="text-[10px] text-slate-500 mt-2 font-medium">Leave empty to keep the current photo.</p>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-lg shadow-slate-200 transition-all">
                            Update Database
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
