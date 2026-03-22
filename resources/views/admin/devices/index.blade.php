@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Device <span class="text-red-600">Database</span></h1>
                <p class="text-sm text-slate-500 font-medium">Customer devices and hardware specifications</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-luxury shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Device Details</th>
                        <th class="p-5">Hardware Specs</th>
                        <th class="p-5">Customer</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($devices)) @foreach($devices as $device)
                    <tr class="hover:bg-red-50/30 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center text-2xl">
                                    @if($device->type == 'laptop')
                                        💻
                                    @elseif($device->type == 'printer')
                                        🖨️
                                    @else
                                        🖥️
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ $device->brand }} {{ $device->model }}</h3>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">SN: {{ $device->serial_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="p-5">
                            <div class="text-xs text-slate-600 space-y-1">
                                <p><span class="font-bold">CPU:</span> {{ $device->processor ?? 'N/A' }}</p>
                                <p><span class="font-bold">RAM:</span> {{ $device->ram_old ?? 'N/A' }}</p>
                                <p><span class="font-bold">Storage:</span> {{ $device->storage_old ?? 'N/A' }}</p>
                            </div>
                        </td>

                        <td class="p-5">
                            <p class="font-bold text-slate-800">{{ $device->customer->name }}</p>
                            <p class="text-xs text-slate-500">{{ $device->customer->mobile }}</p>
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.devices.show', $device->id) }}" class="bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    View
                                </a>
                                <a href="{{ route('admin.devices.edit', $device->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No devices registered in the database yet.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $devices->links() }}
        </div>
    </div>
</div>
@endsection
