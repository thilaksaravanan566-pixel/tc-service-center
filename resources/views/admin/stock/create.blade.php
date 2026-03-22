@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.parts.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Inventory
                </a>
                <h1 class="text-3xl font-black text-slate-900">Add <span class="text-red-600">Spare Part</span></h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Register a new item into your Thambu Computers Stock</p>
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

        <div class="bg-white p-8 rounded-luxury shadow-lg border border-slate-100">
            <form action="{{ route('admin.parts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Part Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. 8GB DDR4 RAM Crucial" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Category</label>
                            <select name="category" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800 cursor-pointer">
                                <option value="" disabled selected>Select Category</option>
                                <option value="RAM" {{ old('category') == 'RAM' ? 'selected' : '' }}>RAM Memory</option>
                                <option value="Storage" {{ old('category') == 'Storage' ? 'selected' : '' }}>Storage (SSD/HDD)</option>
                                <option value="Display" {{ old('category') == 'Display' ? 'selected' : '' }}>Display / Screen</option>
                                <option value="Battery" {{ old('category') == 'Battery' ? 'selected' : '' }}>Battery</option>
                                <option value="Keyboard" {{ old('category') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                <option value="Motherboard" {{ old('category') == 'Motherboard' ? 'selected' : '' }}>Motherboard / Logic Board</option>
                                <option value="Processor" {{ old('category') == 'Processor' ? 'selected' : '' }}>Processor / CPU</option>
                                <option value="Adapter" {{ old('category') == 'Adapter' ? 'selected' : '' }}>Charger / Adapter</option>
                                <option value="Cables" {{ old('category') == 'Cables' ? 'selected' : '' }}>Cables & Connectors</option>
                                <option value="Monitor" {{ old('category') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                <option value="Desktop Cabinet" {{ old('category') == 'Desktop Cabinet' ? 'selected' : '' }}>Desktop Cabinet</option>
                                <option value="CCTV Camera" {{ old('category') == 'CCTV Camera' ? 'selected' : '' }}>CCTV Camera</option>
                                <option value="IC" {{ old('category') == 'IC' ? 'selected' : '' }}>IC</option>
                                <option value="IO IC" {{ old('category') == 'IO IC' ? 'selected' : '' }}>IO IC</option>
                                <option value="GPU" {{ old('category') == 'GPU' ? 'selected' : '' }}>GPU / Graphics Card</option>
                                <option value="Thermal Paste" {{ old('category') == 'Thermal Paste' ? 'selected' : '' }}>Thermal Paste</option>
                                <option value="Webcams" {{ old('category') == 'Webcams' ? 'selected' : '' }}>Webcams</option>
                                <option value="Speakers" {{ old('category') == 'Speakers' ? 'selected' : '' }}>Speakers</option>
                                <option value="Panels" {{ old('category') == 'Panels' ? 'selected' : '' }}>A, B, C Panels</option>
                                <option value="Mouse" {{ old('category') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                <option value="Wireless KB&Mouse" {{ old('category') == 'Wireless KB&Mouse' ? 'selected' : '' }}>Wireless KB & Mouse</option>
                                <option value="CMOS Battery" {{ old('category') == 'CMOS Battery' ? 'selected' : '' }}>CMOS Battery</option>
                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other Component</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Color (Optional)</label>
                            <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. Midnight Black" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Price (₹)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Initial Stock Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock') }}" required placeholder="Number of units" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Part Photo (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-lg shadow-slate-200 transition-all">
                            Save to Database
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection