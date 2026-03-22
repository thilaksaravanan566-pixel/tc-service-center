@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.services.index') }}" class="text-slate-400 hover:text-blue-600 text-sm font-bold flex items-center gap-2 mb-2">
                    ← Back to Management
                </a>
                <h1 class="text-3xl font-black text-slate-900">Job <span class="text-blue-600">#{{ $order->tc_job_id }}</span></h1>
            </div>
            <div class="flex gap-3">
                @php $invoice = \App\Models\Invoice::where('service_order_id', $order->id)->first(); @endphp
                @if($invoice)
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="bg-blue-50 border border-blue-200 px-6 py-2 rounded-xl text-sm font-bold text-blue-600 hover:bg-blue-100 flex items-center">
                        View Invoice
                    </a>
                @elseif($order->status === 'completed')
                    <a href="{{ route('admin.invoices.create', ['service_order_id' => $order->id]) }}" class="bg-green-600 px-6 py-2 rounded-xl text-sm font-bold text-white hover:bg-green-700 flex items-center shadow-lg shadow-green-100">
                        Generate Invoice
                    </a>
                @else
                    <a href="{{ route('admin.invoices.create', ['service_order_id' => $order->id]) }}" class="bg-white border border-slate-200 px-6 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 flex items-center">
                        Manual Billing
                    </a>
                @endif
                <div class="bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-200 flex items-center">
                    {{ strtoupper($order->status) }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-luxury shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-6">Device & Hardware Specs</h3>
                    <div class="grid grid-cols-2 gap-y-6">
                        <div>
                            <p class="text-slate-400 text-[10px] font-black uppercase">Brand & Model</p>
                            <p class="text-lg font-bold text-slate-800">{{ $order->device->brand }} {{ $order->device->model }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-[10px] font-black uppercase">Serial Number</p>
                            <p class="font-bold text-slate-700">{{ $order->device->serial_number ?? 'Not Provided' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-[10px] font-black uppercase">Processor</p>
                            <p class="text-slate-700">{{ $order->device->processor ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-[10px] font-black uppercase">Memory / Storage</p>
                            <p class="text-slate-700">{{ $order->device->ram_old ?? 'N/A' }} / {{ $order->device->storage_old ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr class="my-8 border-slate-50">
                    <h3 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Reported Fault</h3>
                    <div class="bg-red-50/50 p-4 rounded-2xl border border-red-50 text-slate-700 italic leading-relaxed">
                        "{{ $order->fault_details }}"
                    </div>
                </div>

                <div class="bg-white p-8 rounded-luxury shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-6 border-b pb-2">Pre-Service Intake specs & condition photos</h3>
                    
                    <form action="{{ route('admin.services.uploadPhotos', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Processor</label>
                                <input type="text" name="processor" value="{{ $order->device->processor }}" class="w-full border border-slate-200 rounded-xl p-2 text-sm font-bold text-slate-700 outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">RAM</label>
                                <input type="text" name="ram" value="{{ $order->device->ram }}" class="w-full border border-slate-200 rounded-xl p-2 text-sm font-bold text-slate-700 outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">SSD Storage</label>
                                <input type="text" name="ssd" value="{{ $order->device->ssd }}" class="w-full border border-slate-200 rounded-xl p-2 text-sm font-bold text-slate-700 outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold uppercase block mb-1">HDD Storage</label>
                                <input type="text" name="hdd" value="{{ $order->device->hdd }}" class="w-full border border-slate-200 rounded-xl p-2 text-sm font-bold text-slate-700 outline-none focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] text-slate-400 font-bold uppercase block mb-2">Upload Intake Damage / Condition Photos</label>
                            <input type="file" name="photos[]" multiple class="w-full text-slate-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-colors">
                            Update Intake Hardware Specs & Photos
                        </button>
                    </form>

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mt-8 mb-4">Current Intake Photos</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if($order->device->damage_photos && count($order->device->damage_photos) > 0)
                            @foreach($order->device->damage_photos as $photo)
                                <div class="relative group overflow-hidden rounded-2xl border border-slate-100 aspect-video">
                                    <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold transition-opacity">
                                        View Full
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full py-10 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                                <p class="text-slate-400 text-sm">No intake photos available.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-8 rounded-luxury shadow-sm border border-slate-100 mt-6">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest leading-none">Inventory Consumption (Parts Used)</h3>
                        @if($order->dealer_id)
                        <span class="text-[10px] px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full font-bold">Source: Dealer local Stock</span>
                        @else
                        <span class="text-[10px] px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full font-bold">Source: TC Global stock</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('admin.services.usePart', $order->id) }}" method="POST" class="flex gap-3 mb-8">
                        @csrf
                        <div class="flex-1">
                            <select name="product_id" class="w-full border border-slate-200 rounded-xl p-3 text-sm font-bold text-slate-700 focus:border-blue-500 outline-none appearance-none bg-slate-50/50" required>
                                <option value="" disabled selected>Select hardware part from inventory...</option>
                                @php 
                                    $availableProducts = \App\Models\Product::where('status', 'active')->get();
                                @endphp
                                @foreach($availableProducts as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }} - (₹{{ $order->dealer_id ? $prod->dealer_price : $prod->selling_price }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <input type="number" name="quantity" value="1" min="1" class="w-full border border-slate-200 rounded-xl p-3 text-sm font-bold text-center text-slate-700 focus:border-blue-500 outline-none">
                        </div>
                        <button type="submit" class="bg-blue-600 px-6 py-3 rounded-xl text-xs font-black text-white hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase tracking-widest">
                            Add Part
                        </button>
                    </form>

                    <div class="space-y-4">
                        @forelse($order->parts_used ?? [] as $part)
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 border border-slate-100 rounded-2xl group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm border border-slate-50">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $part['name'] }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $part['quantity'] }} Units @ ₹{{ number_format($part['price'], 2) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <p class="text-sm font-black text-slate-900 italic tracking-tighter">₹{{ number_format($part['price'] * $part['quantity'], 2) }}</p>
                                <form action="{{ route('admin.services.removePart', [$order->id, $part['id']]) }}" method="POST" onsubmit="return confirm('Return this part to stock?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center border-2 border-dashed border-slate-50 rounded-3xl">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic">No hardware consumed yet</p>
                        </div>
                        @endforelse
                    </div>

                    @if($order->parts_used)
                    <div class="mt-8 pt-6 border-t border-slate-50 flex justify-between items-center px-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Aggregate Parts valuation</p>
                        <p class="text-xl font-black text-blue-900 tracking-tighter italic">₹{{ number_format(collect($order->parts_used)->sum(fn($p) => $p['price'] * $p['quantity']), 2) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase mb-4">Customer Details</h3>
                    <p class="text-xl font-black text-slate-900">{{ $order->device->customer->name ?? $order->dealer->name ?? 'N/A' }}</p>
                    <p class="text-blue-600 font-bold mb-4">{{ $order->device->customer->mobile ?? $order->dealer->mobile ?? 'N/A' }}</p>
                    <div class="bg-slate-50 p-3 rounded-xl text-xs text-slate-500">
                        {{ $order->device->customer->email ?? $order->dealer->email ?? 'No email registered' }}
                    </div>
                    @if($order->delivery_type === 'delivery')
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="text-[10px] font-black uppercase text-orange-500 mb-2 tracking-widest">Delivery Details</h4>
                            <p class="text-sm font-bold text-slate-700">{{ $order->delivery_mobile ?? 'Not Provided' }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $order->delivery_address ?? 'Not Provided' }}</p>
                            @if($order->delivery_location_url)
                                <a href="{{ $order->delivery_location_url }}" target="_blank" class="inline-block mt-3 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">
                                    View on Maps
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Delivery Type</h4>
                            <p class="text-sm font-bold text-slate-700">Drop-off / Store Take Away</p>
                        </div>
                    @endif
                </div>

                <div class="bg-slate-900 p-8 rounded-luxury shadow-2xl text-white">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Job Details & Billing</h3>
                    <form action="{{ route('admin.services.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Status</label>
                                <select name="status" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl p-3 outline-none focus:border-blue-500 font-bold text-sm">
                                    <option value="received" {{ $order->status == 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="diagnosing" {{ $order->status == 'diagnosing' ? 'selected' : '' }}>Diagnosing</option>
                                    <option value="repairing" {{ $order->status == 'repairing' ? 'selected' : '' }}>Repairing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="packing" {{ $order->status == 'packing' ? 'selected' : '' }}>Packing</option>
                                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Shipping / Out for Delivery</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered / Handed Over</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Total Cost (₹)</label>
                                <input type="number" step="0.01" name="estimated_cost" value="{{ $order->estimated_cost }}" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl p-3 outline-none focus:border-blue-500 font-bold text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 font-bold mb-1 block">Technician Description</label>
                                <textarea name="engineer_comment" rows="3" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl p-3 outline-none focus:border-blue-500 text-sm">{{ $order->engineer_comment }}</textarea>
                            </div>
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="is_paid" value="0">
                                    <input type="checkbox" name="is_paid" value="1" {{ $order->is_paid ? 'checked' : '' }} class="w-5 h-5 accent-blue-500">
                                    <span class="text-sm font-bold">Mark as Paid</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-xl tracking-widest text-[10px] uppercase transition">
                                Save Updates
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-blue-600 uppercase mb-4">Assign Technician</h3>
                    <form action="{{ route('admin.services.assignTechnician', $order->id) }}" method="POST">
                        @csrf
                        <select name="technician_id" class="w-full text-slate-700 font-bold p-3 mb-4 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm cursor-pointer">
                            <option value="">-- Unassigned --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $order->technician_id == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-black py-3 rounded-xl text-[10px] uppercase tracking-widest transition-colors">
                            Update Assignment
                        </button>
                    </form>
                </div>

                @if($order->delivery_type === 'delivery')
                <div class="bg-white p-6 rounded-luxury shadow-sm border border-slate-100">
                    <h3 class="text-xs font-black text-orange-500 uppercase mb-4">Assign Delivery Partner</h3>
                    <form action="{{ route('admin.services.assignDelivery', $order->id) }}" method="POST">
                        @csrf
                        <select name="delivery_partner_id" class="w-full text-slate-700 font-bold p-3 mb-4 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-orange-100 focus:border-orange-400 transition-all text-sm cursor-pointer">
                            <option value="">-- Unassigned --</option>
                            @if(isset($deliveryPartners))
                                @foreach($deliveryPartners as $partner)
                                    <option value="{{ $partner->id }}" {{ $order->delivery_partner_id == $partner->id ? 'selected' : '' }}>
                                        {{ $partner->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="submit" class="w-full bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white font-black py-3 rounded-xl text-[10px] uppercase tracking-widest transition-colors">
                            Update Assignment
                        </button>
                    </form>

                    @if($order->deliveryPartner)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-[10px] font-black uppercase text-slate-400 mb-2 tracking-widest">Assigned Partner Details</h4>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-slate-700 flex justify-between">
                                <span class="text-slate-500 font-normal">Name:</span> {{ $order->deliveryPartner->name }}
                            </p>
                            <p class="text-sm font-bold text-slate-700 flex justify-between">
                                <span class="text-slate-500 font-normal">Contact:</span> {{ $order->deliveryPartner->mobile ?? 'N/A' }}
                            </p>
                            <p class="text-sm font-bold text-slate-700 flex justify-between">
                                <span class="text-slate-500 font-normal">Vehicle:</span> <span class="bg-slate-100 px-2 rounded">{{ $order->deliveryPartner->vehicle_number ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection