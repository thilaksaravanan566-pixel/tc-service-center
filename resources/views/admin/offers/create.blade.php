@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.offers.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back to Offers
                </a>
                <h1 class="text-3xl font-black text-slate-900">Create <span class="text-red-600">Promotion</span></h1>
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

        <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-[#d4af37] uppercase mb-2">Offer Title / Headline</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. 50% Off Screen Replacements" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37] transition-all font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-gray-500 uppercase mb-2">Promotional Content</label>
                        <textarea name="description" rows="5" placeholder="Describe the offer details..." required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37] transition-all font-bold text-slate-800">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-blue-600 uppercase mb-2">Discount/Promo Code (Optional)</label>
                            <input type="text" name="discount_code" value="{{ old('discount_code') }}" placeholder="e.g. SPRING50" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37] transition-all font-black text-slate-800 uppercase tracking-widest">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Campaign Status</label>
                            <div class="flex items-center mt-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                  <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#d4af37]"></div>
                                  <span class="ml-3 text-sm font-black text-slate-700 uppercase tracking-wider">Publish Offer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-slate-500 uppercase mb-2">Creative Banner Image (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-[#d4af37] transition-all font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-[#d4af37]">
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full luxury-btn font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-xl transition-all">
                            Broadcast Promotion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
