@extends('layouts.admin')

@section('title', 'Shipment Dispatch Console')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Logistic Dispatch Control</h1>
        <p class="text-gray-500 mt-2 font-medium">Provisioning shipment for Order: <span class="text-blue-600 font-mono tracking-tighter">{{ $order->order_number }}</span></p>
    </div>
    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary border border-gray-200">
        <i class="fas fa-times mr-2"></i> Cancel Dispatch
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
    <div class="space-y-8">
        <div class="card p-10 shadow-2xl border-0 bg-white relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl group-hover:bg-blue-500/10 transition duration-700"></div>
            <div class="relative">
                <form action="{{ route('admin.logistics.storeShipment', $order->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-10">
                        <label class="block text-xs uppercase font-extrabold text-gray-400 tracking-widest mb-6 border-b border-gray-100 pb-2">Logistics Routing Protocol</label>
                        <div class="grid grid-cols-2 gap-6">
                            <label class="relative flex flex-col items-center gap-4 p-8 border-2 rounded-3xl cursor-pointer transition-all hover:border-blue-500/50 group/item hover:bg-blue-50/10 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30 has-[:checked]:shadow-xl has-[:checked]:shadow-blue-600/10 border-gray-100 bg-gray-50/30">
                                <input type="radio" name="method" value="courier" class="peer hidden" checked x-on:change="method = 'courier'">
                                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-gray-400 group-hover/item:text-blue-500 peer-checked:text-blue-600 peer-checked:bg-white shadow-sm transition">
                                    <i class="fas fa-shipping-fast text-2xl"></i>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight">Express Carrier</p>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1">Delhivery, BlueDart, Ecom Express</p>
                                </div>
                                <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                            </label>

                            <label class="relative flex flex-col items-center gap-4 p-8 border-2 rounded-3xl cursor-pointer transition-all hover:border-blue-500/50 group/item hover:bg-blue-50/10 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30 has-[:checked]:shadow-xl has-[:checked]:shadow-blue-600/10 border-gray-100 bg-gray-50/30">
                                <input type="radio" name="method" value="bus_parcel" class="peer hidden" x-on:change="method = 'bus_parcel'">
                                <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-gray-400 group-hover/item:text-blue-500 peer-checked:text-blue-600 peer-checked:bg-white shadow-sm transition">
                                    <i class="fas fa-bus text-2xl"></i>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight">Bus Transit</p>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1">ST Parcel, private carriers</p>
                                </div>
                                <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="method-courier" class="space-y-6 animate-in slide-in-from-left duration-500">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Carrier Entity Name</label>
                                <input type="text" name="courier_name" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold text-gray-800" placeholder="e.g. Professional Couriers">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Carrier Tracking Token</label>
                                <input type="text" name="tracking_number" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold text-blue-600 font-mono tracking-tighter" placeholder="e.g. PRO-98834455">
                            </div>
                        </div>
                    </div>

                    <div id="method-bus" class="hidden space-y-6 animate-in slide-in-from-right duration-500 mt-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Bus/Fleet Name</label>
                                <input type="text" name="bus_name" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold" placeholder="e.g. SRM Transports">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">LR System Number</label>
                                <input type="text" name="lr_number" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold font-mono" placeholder="e.g. LR-776655">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Dispatch Origin</label>
                                <input type="text" name="from_location" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold" placeholder="Origin DC">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-2">Dispatch Endpoint</label>
                                <input type="text" name="to_location" class="input w-full p-4 bg-gray-100/50 border border-gray-200 rounded-2xl font-bold" placeholder="Destination Hub">
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-gray-100 flex items-center justify-between">
                         <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mark Order as Shipped automatically</p>
                         </div>
                         <button type="submit" class="btn btn-primary px-12 py-4 text-sm font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all">
                            Dispatch Shipment <i class="fas fa-paper-plane ml-2"></i>
                         </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <div class="card p-8 bg-gray-900 text-white shadow-2xl border-0 overflow-hidden relative group">
            <div class="absolute -left-6 -bottom-6 w-32 h-32 bg-blue-600/20 rounded-full blur-3xl transition duration-1000 group-hover:scale-150"></div>
            <div class="relative">
                <h3 class="text-xs uppercase font-extrabold tracking-widest text-blue-400 mb-8 border-b border-white/5 pb-4">Consignment Manifest</h3>
                <div class="space-y-6">
                    @foreach($order->items as $item)
                    <div class="flex items-start justify-between group/item">
                        <div>
                            <p class="text-sm font-bold text-indigo-50 leading-none mb-1">{{ $item->product->name }}</p>
                            <p class="text-[10px] font-mono text-gray-500 italic uppercase">SKU: {{ $item->product->sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest bg-blue-500/5 px-2 py-1 rounded">X {{ $item->quantity }} UNITS</p>
                            <p class="text-xs font-medium text-gray-500 mt-1 italic">Confirmed Allocation</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-10 pt-8 border-t border-white/5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Gross Shipment Value</p>
                            <p class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Receiver</p>
                            <p class="text-sm font-bold text-blue-500 tracking-tight">{{ $order->dealer->business_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 leading-none">
            <div class="card p-6 border-0 bg-blue-50/50 flex flex-col items-center justify-center text-center group hover:bg-blue-600 transition duration-500 cursor-default">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-lg flex items-center justify-center text-blue-600 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-barcode text-xl"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-blue-200">Inventory Handover</p>
                <p class="text-sm font-black text-gray-800 mt-2 group-hover:text-white">STOCK EX-WAREHOUSE</p>
            </div>
            <div class="card p-6 border-0 bg-emerald-50/50 flex flex-col items-center justify-center text-center group hover:bg-emerald-600 transition duration-500 cursor-default">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-lg flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-emerald-200">Logistics Tracking</p>
                <p class="text-sm font-black text-gray-800 mt-2 group-hover:text-white">LIVE VISIBILITY ACTIVE</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="method"]').forEach(input => {
        input.addEventListener('change', (e) => {
            if(e.target.value === 'courier') {
                document.getElementById('method-courier').classList.remove('hidden');
                document.getElementById('method-bus').classList.add('hidden');
            } else {
                document.getElementById('method-courier').classList.add('hidden');
                document.getElementById('method-bus').classList.remove('hidden');
            }
        });
    });
</script>
@endsection
