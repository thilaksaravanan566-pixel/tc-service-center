@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
            <div>
                <a href="{{ route('admin.devices.index') }}" class="text-slate-400 hover:text-red-500 font-bold text-xs uppercase tracking-wider block mb-2 transition-colors">← Back to Devices</a>
                <h1 class="text-2xl font-black text-slate-900">{{ $device->brand }} {{ $device->model }}</h1>
                <p class="text-slate-500 text-sm mt-1">Serial Number: <span class="font-bold text-slate-800">{{ $device->serial_number ?? 'Not provided' }}</span></p>
            </div>
            <a href="{{ route('admin.devices.edit', $device->id) }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-md transition-colors">
                Edit Specifications
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                <h2 class="text-xs font-black text-red-600 uppercase tracking-widest mb-4">Hardware Specifications</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Processor / CPU</p>
                        <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg mt-1 border border-slate-100">{{ $device->processor ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Initial RAM</p>
                        <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg mt-1 border border-slate-100">{{ $device->ram_old ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Initial Storage</p>
                        <p class="text-slate-800 font-bold bg-slate-50 p-3 rounded-lg mt-1 border border-slate-100">{{ $device->storage_old ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                <h2 class="text-xs font-black text-red-600 uppercase tracking-widest mb-4">Ownership Info</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Customer Name</p>
                        <p class="text-slate-800 font-bold text-lg mt-1">{{ $device->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Mobile Number</p>
                        <p class="text-slate-800 font-bold text-lg mt-1">{{ $device->customer->mobile }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase">Email Address</p>
                        <p class="text-slate-600 font-bold bg-slate-50 p-3 rounded-lg mt-1 border border-slate-100 truncate">{{ $device->customer->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
