@extends('layouts.customer')
@section('title', 'Track Service')

@section('content')
<div class="animate-slide-up max-w-5xl mx-auto min-h-[70vh]">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-gray-500 hover:text-indigo-600 flex items-center gap-1.5 mb-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-black text-gray-900 flex items-center gap-3">
                Track Service
                <span class="bg-indigo-50 text-indigo-700 border border-indigo-100 text-sm px-3 py-1 rounded-full uppercase tracking-widest font-black">
                    {{ $order->tc_job_id }}
                </span>
            </h1>
        </div>
        
        <div class="text-left md:text-right">
            <div class="text-[10px] font-black tracking-widest text-gray-400 uppercase mb-0.5">Created On</div>
            <div class="text-gray-900 font-bold text-sm">{{ $order->created_at->format('M d, Y, h:i A') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Timeline -->
        <div class="lg:col-span-2">
            <div class="super-card p-6 sm:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
                
                <h2 class="text-xl font-bold text-gray-900 mb-10">Service Lifecycle</h2>

                @php
                    $steps = [
                        'received' => 'Service Requested',
                        'assigned' => 'Technician Assigned',
                        'diagnosing' => 'Diagnosing',
                        'repairing' => 'Repair In Progress',
                        'packing' => 'Quality Check',
                        'shipping' => 'Ready for Delivery',
                        'delivered' => 'Delivered / Completed',
                        'returned' => 'Returned'
                    ];

                    $statuses = array_keys($steps);
                    $currentIndex = array_search($order->status, $statuses);
                    
                    // Filter out returned if it's not the status
                    if ($order->status !== 'returned') {
                        unset($steps['returned']);
                        $statuses = array_values(array_filter($statuses, fn($s) => $s !== 'returned'));
                    }
                @endphp

                <div class="relative max-w-md mx-auto sm:mx-0 pl-4 sm:pl-0">
                    <!-- Progress Line Background -->
                    <div class="absolute left-[27px] top-8 bottom-8 w-1 bg-gray-100 rounded-full"></div>
                    
                    <!-- Dynamic Progress Line -->
                    @if($currentIndex !== false && $currentIndex >= 0)
                        <div class="absolute left-[27px] top-8 w-1 bg-indigo-500 rounded-full transition-all duration-1000" style="height: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%;"></div>
                    @endif

                    <!-- Stepper Items -->
                    @foreach($steps as $key => $label)
                        @php
                            $stepIndex = array_search($key, $statuses);
                            $isCompleted = $currentIndex !== false && $stepIndex <= $currentIndex;
                            $isActive = $key === $order->status;
                        @endphp

                        <div class="relative flex items-start mb-8 last:mb-0 group">
                            <!-- Circular Indicator -->
                            <div class="w-14 h-14 flex-shrink-0 rounded-full flex items-center justify-center transition-all duration-300 z-10 border-4 border-white
                                {{ $isCompleted ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-400' }}">
                                
                                @if($isActive)
                                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($isCompleted)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                @else
                                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                                @endif
                            </div>

                            <!-- Text Content -->
                            <div class="ml-6 pt-3">
                                <h4 class="font-bold text-base transition-colors {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $label }}
                                </h4>
                                @if($isActive)
                                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-widest mt-1">Current Status</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-6">
            
            <!-- Device Info Card -->
            <div class="super-card p-6">
                <div class="uppercase text-[10px] font-black tracking-widest mb-4 text-gray-400">Device Details</div>
                
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex shrink-0 justify-center items-center text-xl shadow-sm">
                        @if($order->device->type == 'laptop') 💻 @elseif($order->device->type == 'desktop_assemble') 🖥️ @elseif($order->device->type == 'printer') 🖨️ @elseif($order->device->type == 'cctv') 📷 @else 🔧 @endif
                    </div>
                    <div>
                        <div class="text-gray-900 font-bold text-lg leading-tight">{{ $order->device->brand }} {{ $order->device->model }}</div>
                        <div class="text-xs text-gray-500 font-medium">Type: {{ $order->device->type_label }}</div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-6">
                    <div class="text-[10px] font-black tracking-widest text-gray-500 uppercase mb-1">Reported Issue</div>
                    <p class="text-sm font-medium text-gray-700 leading-relaxed">
                        "{{ $order->fault_details }}"
                    </p>
                </div>

                @if(in_array($order->status, ['received', 'assigned']))
                <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100 mt-6">
                    <h4 class="text-[10px] font-black tracking-widest text-indigo-600 uppercase mb-3">Update Hardware & Upload Pre-Service Photos</h4>
                    <form action="{{ route('tracking.updateSpecs', $order->tc_job_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">Processor</label>
                                <input type="text" name="processor" value="{{ $order->device->processor }}" placeholder="e.g. i5 10th Gen" class="w-full border border-gray-200 rounded-lg p-2 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">RAM</label>
                                <input type="text" name="ram" value="{{ $order->device->ram }}" placeholder="e.g. 16GB" class="w-full border border-gray-200 rounded-lg p-2 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">SSD Storage</label>
                                <input type="text" name="ssd" value="{{ $order->device->ssd }}" placeholder="e.g. 512GB NVMe" class="w-full border border-gray-200 rounded-lg p-2 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">HDD Storage</label>
                                <input type="text" name="hdd" value="{{ $order->device->hdd }}" placeholder="e.g. 1TB" class="w-full border border-gray-200 rounded-lg p-2 text-sm text-gray-700 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-500 font-bold uppercase block mb-1">Upload Damage Photos</label>
                            <input type="file" name="photos[]" multiple class="w-full text-gray-500 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-[10px] uppercase tracking-widest rounded-lg transition-colors">
                            Save Specs & Photos
                        </button>
                    </form>
                </div>
                @endif
                
                @if($order->device->damage_photos && count($order->device->damage_photos) > 0)
                <div class="mt-6">
                    <h4 class="text-[10px] font-black tracking-widest text-gray-400 uppercase mb-3">Current Pre-Service Photos</h4>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($order->device->damage_photos as $photo)
                            <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="block rounded-lg overflow-hidden border border-gray-100 bg-gray-50 aspect-video relative group">
                                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Technician & Cost Info -->
            <div class="super-card p-6">
                
                <div class="mb-5">
                    <div class="uppercase text-[10px] font-black tracking-widest mb-3 text-gray-400">Assigned Expert</div>
                    @if($order->technician)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-sm border border-indigo-200">
                                {{ substr($order->technician->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">{{ $order->technician->name }}</div>
                                <div class="text-xs text-indigo-600 font-semibold">TC Certified Technician</div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pending Assignment
                        </div>
                    @endif
                </div>
                
                @if($order->delivery_type === 'delivery')
                <hr class="border-gray-100 my-5">
                <div class="mb-5">
                    <div class="uppercase text-[10px] font-black tracking-widest mb-3 text-gray-400">Delivery Information</div>
                    @if($order->deliveryPartner)
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                    {{ substr($order->deliveryPartner->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-gray-900 text-sm">{{ $order->deliveryPartner->name }}</div>
                            </div>
                            <div class="text-xs text-gray-600 mb-1">
                                <span class="font-semibold">Contact:</span> {{ $order->deliveryPartner->mobile ?? 'Pending' }}
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-semibold">Vehicle:</span> {{ $order->deliveryPartner->vehicle_number ?? 'Pending' }}
                            </div>
                        </div>
                    @else
                        <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Partner Not Assigned
                        </div>
                    @endif
                </div>
                @endif
                
                <hr class="border-gray-100 mb-5">

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500">Estimated Cost</span>
                        <span class="text-lg font-black text-gray-900">₹{{ number_format($order->estimated_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-500">Payment Status</span>
                        @if($order->is_paid)
                            <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-1 rounded-md font-black uppercase tracking-widest">Paid</span>
                        @else
                            <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] px-2 py-1 rounded-md font-black uppercase tracking-widest">Unpaid</span>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($order->engineer_comment)
            <div class="super-card p-6 bg-indigo-50/30 border-indigo-100">
                <div class="uppercase text-[10px] font-black tracking-widest mb-2 text-indigo-600">Technician Notes</div>
                <p class="text-sm font-medium text-gray-700 italic">
                    "{{ $order->engineer_comment }}"
                </p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
