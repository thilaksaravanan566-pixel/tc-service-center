@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-2xl w-full">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.devices.index') }}" class="text-slate-400 hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Update <span class="text-red-600">Specs</span></h1>
                <p class="text-slate-500 text-sm font-medium">Modify hardware details for {{ $device->brand }} {{ $device->model }}</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-luxury shadow-lg border border-slate-100">
            <form action="{{ route('admin.devices.update', $device->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-red-600 mb-2">Processor / CPU</label>
                        <input type="text" name="processor" value="{{ old('processor', $device->processor) }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. Intel Core i7 12th Gen">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-red-600 mb-2">RAM Amount</label>
                            <input type="text" name="ram_old" value="{{ old('ram_old', $device->ram_old) }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. 16GB DDR4">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-red-600 mb-2">Storage Amount</label>
                            <input type="text" name="storage_old" value="{{ old('storage_old', $device->storage_old) }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800" placeholder="e.g. 512GB NVMe SSD">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-red-600 mb-2">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $device->serial_number) }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-bold py-4 rounded-xl shadow-md transition-all uppercase tracking-wider text-sm">
                            Save Hardware Updates
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
