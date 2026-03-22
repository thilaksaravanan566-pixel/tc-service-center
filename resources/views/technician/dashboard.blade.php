@extends('layouts.technician')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Current <span class="text-red-600">Operations</span></h1>
                <p class="text-sm text-slate-500 font-medium">Prioritize your pending repairs and diagnosis.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none">Job Queue</h3>
                @forelse($orders as $order)
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative group hover:shadow-xl hover:shadow-red-900/5 transition-all">
                    
                    <!-- Decorative glowing line -->
                    <div class="absolute inset-x-0 -top-px h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>

                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                                TC-{{ $order->tc_job_id }}
                            </span>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $order->status == 'received' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="mb-6 space-y-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Device Spec</p>
                                <p class="font-bold text-slate-800 text-xl">{{ $order->device->brand }} {{ $order->device->model }}</p>
                            </div>
                            
                            <!-- Hardware Specs (View Only) -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Processor / CPU</p>
                                    <p class="text-xs font-bold text-slate-800 truncate" title="{{ $order->device->processor ?? 'N/A' }}">{{ $order->device->processor ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">RAM</p>
                                    <p class="text-xs font-bold text-slate-800 truncate" title="{{ $order->device->ram_old ?? 'N/A' }}">{{ $order->device->ram_old ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Storage</p>
                                    <p class="text-xs font-bold text-slate-800 truncate" title="{{ $order->device->storage_old ?? 'N/A' }}">{{ $order->device->storage_old ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-red-50/50 p-4 rounded-xl border border-red-100">
                                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Reported Fault</p>
                                <p class="text-slate-700 italic text-sm">{{ $order->fault_details }}</p>
                            </div>
                        </div>

                        <!-- Parts Consumption Section -->
                        <div class="mb-8 p-6 bg-slate-50/50 border border-slate-100 rounded-3xl">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 leading-none">
                                <span class="bg-white w-6 h-6 flex items-center justify-center rounded-lg shadow-sm border border-slate-50"><i class="fas fa-microchip text-slate-300 text-[10px]"></i></span> Hardware Consumption
                            </h4>
                            <div class="space-y-2 mb-4">
                                @php $usedParts = $order->parts_used ?? []; @endphp
                                @forelse($usedParts as $part)
                                <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <p class="text-[11px] font-bold text-slate-700 leading-none">{{ $part['name'] }} <span class="text-slate-400 ml-1">x{{ $part['quantity'] }}</span></p>
                                    </div>
                                    <form action="{{ route('technician.services.removePart', [$order->id, $part['id']]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition">Remove</button>
                                    </form>
                                </div>
                                @empty
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic py-4 text-center opacity-50">No hardware deployed yet</p>
                                @endforelse
                            </div>

                            <form action="{{ route('technician.services.usePart', $order->id) }}" method="POST" class="flex gap-2 p-2 bg-white rounded-2xl border border-slate-100">
                                @csrf
                                <select name="product_id" class="flex-1 p-2 bg-slate-50 border-0 rounded-xl text-xs font-bold text-slate-700 outline-none active:bg-slate-100" required>
                                    <option value="" disabled selected>Search stock...</option>
                                    @php $parts = \App\Models\Product::where('status', 'active')->get(); @endphp
                                    @foreach($parts as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (₹{{ $order->dealer_id ? $p->dealer_price : $p->selling_price }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="quantity" value="1" min="1" class="w-12 p-2 bg-slate-50 border-0 rounded-xl text-xs font-bold text-center outline-none">
                                <button type="submit" class="bg-slate-900 hover:bg-slate-800 px-4 rounded-xl text-[10px] font-black text-white uppercase tracking-widest transition shadow-lg shadow-slate-200">LOG</button>
                            </form>
                        </div>

                        <!-- Technician Action Form -->
                        <form action="{{ route('technician.services.updateStatus', $order->id) }}" method="POST" class="mt-8 border-t border-slate-100 pt-6">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Internal Tech Notes</label>
                                <textarea name="engineer_comment" rows="2" class="w-full text-sm p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-medium text-slate-700" placeholder="Add diagnosis details or parts required...">{{ $order->engineer_comment }}</textarea>
                            </div>

                            <div class="flex gap-4">
                                <select name="status" class="flex-1 p-3 bg-white border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 font-bold text-sm text-slate-700 cursor-pointer">
                                    <option value="received" {{ $order->status == 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="diagnosing" {{ $order->status == 'diagnosing' ? 'selected' : '' }}>Diagnosing</option>
                                    <option value="repairing" {{ $order->status == 'repairing' ? 'selected' : '' }}>Repairing</option>
                                    <option value="packing" {{ $order->status == 'packing' ? 'selected' : '' }}>Ready to Pack/Ship</option>
                                </select>
                                
                                <button type="submit" class="bg-slate-900 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-md transition-colors w-1/3">
                                    Update Job
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold italic text-lg mb-2">Queue Empty</p>
                        <p class="text-slate-500 text-sm">There are currently no active devices requiring diagnosis.</p>
                    </div>
                @endforelse
            </div>

        <div class="lg:col-span-1 space-y-8">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none">Field Surveillance</h3>
            
            <div class="space-y-6">
                @forelse($visits as $visit)
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-50 rounded-full group-hover:scale-150 transition duration-700"></div>
                    
                    <div class="relative">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none mb-1">Partner Location</p>
                                <h4 class="text-lg font-black text-slate-900 leading-tight italic">{{ $visit->dealer->business_name }}</h4>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-[9px] font-extrabold uppercase tracking-widest">
                                {{ $visit->status }}
                            </span>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-calendar-day text-xs"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-600 italic">{{ \Carbon\Carbon::parse($visit->visit_date)->format('D, d M Y') }}</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Mission Objective</p>
                                <p class="text-xs font-medium text-slate-700 italic">"{{ $visit->purpose }}"</p>
                            </div>
                        </div>

                        <form action="{{ route('technician.visits.update', $visit->id) }}" method="POST" class="grid grid-cols-2 gap-3">
                            @csrf
                            <select name="status" class="bg-slate-50 border-0 rounded-xl text-[10px] font-black uppercase tracking-widest outline-none px-3">
                                <option value="pending" {{ $visit->status == 'pending' ? 'selected' : '' }}>Stationary</option>
                                <option value="on_site" {{ $visit->status == 'on_site' ? 'selected' : '' }}>On Site</option>
                                <option value="completed" {{ $visit->status == 'completed' ? 'selected' : '' }}>Executed</option>
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3 text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-blue-100">Update</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="py-16 text-center bg-white rounded-[2.5rem] border border-slate-100 border-dashed">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-none">Global surveillance clear</p>
                </div>
                @endforelse
            </div>

            <!-- Global Stock View (Ref) -->
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="relative">
                    <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-6 border-b border-white/5 pb-4 leading-none">Local Spare Inventory</h3>
                    <div class="space-y-4">
                        @php $topParts = \App\Models\Product::orderBy('stock_quantity', 'asc')->take(5)->get(); @endphp
                        @foreach($topParts as $tp)
                        <div class="flex justify-between items-center text-[11px] font-bold">
                            <span class="text-white/60 truncate w-32 tracking-tight">{{ $tp->name }}</span>
                            <span class="px-2 py-0.5 rounded-full {{ $tp->stock_quantity < 5 ? 'bg-red-500/20 text-red-400' : 'bg-white/5 text-white/40' }}">{{ $tp->stock_quantity }} Qty</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
