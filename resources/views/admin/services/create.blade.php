@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-black text-slate-900 mb-8">Create New <span class="text-blue-600">Service Job</span></h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-blue-600 uppercase mb-4 tracking-widest">1. Customer Information</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Customer Name" class="p-3 rounded-xl border border-slate-200 outline-none focus:border-blue-500">
                            <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile Number" class="p-3 rounded-xl border border-slate-200 outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-blue-600 uppercase mb-4 tracking-widest">2. Device Specifications</h2>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <select name="type" class="p-3 rounded-xl border border-slate-200 outline-none bg-white">
                                <option value="">Select Type</option>
                                <option value="laptop" {{ old('type') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="desktop" {{ old('type') == 'desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="printer" {{ old('type') == 'printer' ? 'selected' : '' }}>Printer</option>
                            </select>
                            <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Brand" class="p-3 rounded-xl border border-slate-200 outline-none">
                            <input type="text" name="model" value="{{ old('model') }}" placeholder="Model" class="p-3 rounded-xl border border-slate-200 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="serial_number" placeholder="Serial Number" class="p-3 rounded-xl border border-slate-200 outline-none">
                            <input type="text" name="processor" placeholder="CPU / Processor" class="p-3 rounded-xl border border-slate-200 outline-none">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-blue-600 uppercase mb-4 tracking-widest">3. Problem Description</h2>
                        <textarea name="problem" rows="4" placeholder="Describe the issue..." class="w-full p-3 rounded-xl border border-slate-200 outline-none h-32"></textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                        <h2 class="text-xs font-black text-blue-600 uppercase mb-4 tracking-widest">Damage Photos</h2>
                        <input type="file" name="photos[]" multiple class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
                        <p class="text-[10px] text-slate-400 mt-3 italic text-center">Capture condition before repair.</p>
                    </div>
                    
                    <button type="submit" class="btn-luxury w-full py-5 text-lg">REGISTER TC JOB</button>
                    <a href="{{ route('admin.services.index') }}" class="block text-center text-slate-400 text-sm hover:text-slate-600">Cancel & Return</a>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection