@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-6xl mx-auto pb-24">

    <!-- Header Matrix -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-8 mb-12">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Final Registry</h1>
                <p class="text-slate-500 font-medium mt-2">Initialize the final phase of component acquisition.</p>
            </div>
        </div>
        <a href="{{ route('customer.cart.index') }}" class="px-8 py-3.5 rounded-2xl bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 transition-all flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Payload
        </a>
    </div>

    @if($errors->any())
        <div class="mb-12 p-8 rounded-3xl bg-red-500/5 border border-red-500/10 text-red-500 text-xs font-black uppercase tracking-widest shadow-2xl">
            <div class="flex items-center gap-4 mb-4">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                Registry Collision Errors:
            </div>
            <ul class="list-disc list-inside space-y-2 opacity-80">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.cart.placeOrder') }}">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">

            <!-- Manifest Data -->
            <div class="xl:col-span-2 space-y-12">

                <!-- Logistics Configuration -->
                <div class="super-card p-10 relative overflow-hidden group">
                    <div class="absolute -right-12 -top-12 text-9xl font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">SHIP</div>
                    
                    <h2 class="text-sm font-black text-white uppercase tracking-[0.3em] mb-12 flex items-center gap-4 relative z-10">
                        <span class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-400 text-xs font-black flex items-center justify-center border border-indigo-500/20 shadow-lg">01</span>
                        Logistics Manifest
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                        {{-- Extraction Option --}}
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="delivery_type" value="take_away" class="sr-only peer" {{ old('delivery_type', 'take_away') === 'take_away' ? 'checked' : '' }}>
                            <div class="p-8 rounded-3xl border border-white/5 bg-slate-950/50 peer-checked:border-indigo-500/50 peer-checked:bg-indigo-500/5 transition-all flex items-start gap-6 group-hover:bg-indigo-500/[0.02] shadow-2xl">
                                <span class="w-14 h-14 rounded-2xl bg-slate-900 flex items-center justify-center shrink-0 border border-white/5 text-2xl shadow-inner group-hover:scale-110 transition-transform">🏪</span>
                                <div>
                                    <p class="text-white font-black text-xl tracking-tight">Manual Extraction</p>
                                    <p class="text-slate-500 text-[11px] mt-2 font-medium leading-relaxed">Collect instantly from the primary hub located at central coordinates.</p>
                                    <p class="text-indigo-400 text-[9px] font-black uppercase tracking-[0.2em] mt-5 px-3 py-1 bg-indigo-500/10 rounded-lg inline-block">Zero Entropy Fee</p>
                                </div>
                            </div>
                            <div class="absolute top-8 right-8 w-6 h-6 rounded-full border border-white/10 peer-checked:border-indigo-500 bg-slate-950 flex items-center justify-center transition-all">
                                <div class="w-3 h-3 rounded-full bg-indigo-500 opacity-0 peer-checked:opacity-100 transition shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            </div>
                        </label>

                        {{-- Delivery Protocol --}}
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="delivery_type" value="delivery" class="sr-only peer" {{ old('delivery_type') === 'delivery' ? 'checked' : '' }}>
                            <div class="p-8 rounded-3xl border border-white/5 bg-slate-950/50 peer-checked:border-indigo-500/50 peer-checked:bg-indigo-500/5 transition-all flex items-start gap-6 group-hover:bg-indigo-500/[0.02] shadow-2xl">
                                <span class="w-14 h-14 rounded-2xl bg-slate-900 flex items-center justify-center shrink-0 border border-white/5 text-2xl shadow-inner group-hover:scale-110 transition-transform">🛵</span>
                                <div>
                                    <p class="text-white font-black text-xl tracking-tight">Rapid Terminal Delivery</p>
                                    <p class="text-slate-500 text-[11px] mt-2 font-medium leading-relaxed">Automated courier dispatch to your verified nodal destination.</p>
                                    <p class="text-purple-400 text-[9px] font-black uppercase tracking-[0.2em] mt-5 px-3 py-1 bg-purple-500/10 rounded-lg inline-block">Priority Flux</p>
                                </div>
                            </div>
                            <div class="absolute top-8 right-8 w-6 h-6 rounded-full border border-white/10 peer-checked:border-indigo-500 bg-slate-950 flex items-center justify-center transition-all">
                                <div class="w-3 h-3 rounded-full bg-indigo-500 opacity-0 peer-checked:opacity-100 transition shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Nodal Destination Matrix -->
                    <div id="delivery-fields" class="mt-12 space-y-10 pt-12 border-t border-white/5 relative z-10 {{ old('delivery_type') === 'delivery' ? '' : 'hidden' }}">
                        <h3 class="font-black text-white flex items-center gap-4 text-xl tracking-tight">
                            <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> 
                            Destination Matrix
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[500px] overflow-y-auto pr-4 custom-scrollbar py-2">
                            @if($addresses->isNotEmpty()) 
                                @foreach($addresses as $addr)
                                <label class="relative cursor-pointer block group">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" class="sr-only peer" {{ old('address_id', $addresses->first()?->id) == $addr->id ? 'checked' : '' }}>
                                    <div class="p-6 rounded-3xl border border-white/5 bg-slate-900/50 peer-checked:border-indigo-500/50 peer-checked:bg-indigo-500/5 transition-all h-full group-hover:bg-indigo-500/[0.02] shadow-xl">
                                        <p class="font-black text-white text-base mb-3 leading-tight tracking-tight">{{ $addr->address_line }}</p>
                                        <p class="text-slate-500 text-[11px] font-medium leading-relaxed">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->postal_code }}</p>
                                        <div class="mt-5 flex items-center gap-3 text-indigo-400 font-black text-[9px] uppercase tracking-widest px-3 py-1.5 bg-indigo-500/10 rounded-xl w-fit">
                                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                                            ID: {{ $addr->phone ?? auth('customer')->user()->phone }}
                                        </div>
                                    </div>
                                    <div class="absolute top-6 right-6 w-5 h-5 rounded-full border border-white/10 peer-checked:border-indigo-500 bg-slate-900 flex items-center justify-center transition-all">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 opacity-0 peer-checked:opacity-100 transition shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                                    </div>
                                </label>
                                @endforeach 
                            @else
                                <div class="col-span-full mb-6 bg-indigo-500/5 text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] p-8 rounded-3xl border border-indigo-500/20 flex items-center gap-6 shadow-2xl">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0 shadow-inner">
                                        <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    Initializing first-time nodal registry. Please define a terminal destination.
                                </div>
                            @endif

                            <label class="relative cursor-pointer block group md:col-span-full mt-4">
                                <input type="radio" name="address_id" value="new" class="sr-only peer" {{ old('address_id', $addresses->isEmpty() ? 'new' : '') === 'new' ? 'checked' : '' }}>
                                <div class="p-8 rounded-[2rem] border-2 border-dashed border-white/5 bg-white/[0.02] peer-checked:border-indigo-500/40 peer-checked:border-solid peer-checked:bg-indigo-500/10 transition-all text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] flex items-center justify-center gap-4 group-hover:bg-white/[0.05] group-hover:scale-[0.99] transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center shadow-lg group-hover:rotate-90 transition-transform duration-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    Inject New Destination Node
                                </div>
                            </label>
                        </div>

                        <!-- Telemetry Input Grid -->
                        <div id="new-address-fields" class="mt-12 space-y-8 bg-slate-950/50 p-10 rounded-[2.5rem] border border-white/5 shadow-2xl {{ old('address_id', $addresses->isEmpty() ? 'new' : '') === 'new' ? '' : 'hidden' }}">
                            <h4 class="text-[11px] font-black text-white uppercase tracking-[0.3em] mb-4 flex items-center gap-4">
                                <span class="w-3 h-1 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                                New Location Identifier
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Comms Signal (Phone) *</label>
                                    <input type="text" name="delivery_mobile" value="{{ old('delivery_mobile', auth('customer')->user()->phone) }}" class="super-input w-full py-4 text-white font-medium">
                                </div>
                                <div class="md:col-span-2 space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Global Coordinates (Address) *</label>
                                    <input type="text" name="new_address_line" value="{{ old('new_address_line') }}" placeholder="Detailed sector, quadrant, and structure identifiers..." class="super-input w-full py-4 text-white font-medium">
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Sector (City) *</label>
                                    <input type="text" name="new_city" value="{{ old('new_city') }}" class="super-input w-full py-4">
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Region (State) *</label>
                                    <input type="text" name="new_state" value="{{ old('new_state') }}" class="super-input w-full py-4">
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Encryption Key (PIN) *</label>
                                    <input type="text" name="new_postal_code" value="{{ old('new_postal_code') }}" class="super-input w-full py-4">
                                </div>
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Live Map Telemetry (Optional)</label>
                                    <input type="url" name="delivery_location_url" value="{{ old('delivery_location_url') }}" placeholder="Paste terminal link (Google Maps)..." class="super-input w-full py-4">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Financial Authorization -->
                <div class="super-card p-10 relative overflow-hidden group">
                    <div class="absolute -right-12 -top-12 text-9xl font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">PAY</div>
                    
                    <h2 class="text-sm font-black text-white uppercase tracking-[0.3em] mb-12 flex items-center gap-4 relative z-10">
                        <span class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-400 text-xs font-black flex items-center justify-center border border-indigo-500/20 shadow-lg">02</span>
                        Quantum Payments
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 relative z-10">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="payment_method" value="cod" class="sr-only peer" checked>
                            <div class="p-8 rounded-[2rem] border border-white/5 bg-slate-950/50 peer-checked:border-indigo-500/50 peer-checked:bg-indigo-500/5 transition-all flex items-center gap-6 shadow-2xl group-hover:bg-indigo-500/[0.02]">
                                <span class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center shrink-0 border border-white/5 text-3xl shadow-inner group-hover:scale-110 transition-transform">💵</span>
                                <div>
                                    <p class="font-black text-white text-xl tracking-tight">Post-Arrival Credits</p>
                                    <p class="text-slate-500 text-[11px] mt-2 font-medium">Finalize credits at the delivery node.</p>
                                </div>
                            </div>
                            <div class="absolute top-1/2 -translate-y-1/2 right-10 w-6 h-6 rounded-full border border-white/10 peer-checked:border-indigo-500 bg-slate-950 flex items-center justify-center transition-all">
                                <div class="w-3 h-3 rounded-full bg-indigo-500 opacity-0 peer-checked:opacity-100 transition shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer opacity-30 cursor-not-allowed group">
                            <input type="radio" disabled name="payment_method" value="card" class="sr-only peer">
                            <div class="p-8 rounded-[2rem] border border-white/5 bg-slate-950/20 flex items-center gap-6 grayscale">
                                <span class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center shrink-0 border border-white/5 text-3xl">💳</span>
                                <div>
                                    <p class="font-black text-white text-xl tracking-tight">Direct Asset Link</p>
                                    <p class="text-slate-500 text-[11px] mt-2 font-medium leading-relaxed">Encrypted card & wallet protocols coming soon.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Financial Projection -->
            <div class="xl:col-span-1">
                <div class="super-card p-10 sticky top-24 border-white/5 bg-slate-950/50 backdrop-blur-3xl overflow-hidden relative">
                    <div class="absolute -right-12 -top-12 text-9xl font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">PAY</div>
                    
                    <h2 class="text-sm font-black text-white uppercase tracking-[0.3em] mb-12 relative z-10">Unit Manifest</h2>

                    <div class="space-y-6 mb-12 max-h-[35vh] overflow-y-auto custom-scrollbar pr-3 relative z-10">
                        @if(($items ?? collect())->count() > 0)
                            @foreach($items as $cartItem)
                            <?php /** @var \App\Models\CartItem $cartItem */ ?>
                            <div class="flex items-center gap-5 py-5 border-b border-white/5 last:border-0 group hover:bg-white/[0.02] rounded-2xl px-2 transition-all">
                                <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center overflow-hidden shrink-0 border border-white/5 shadow-inner">
                                    <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    @if($cartItem->sparePart?->image_path)
                                        <img src="{{ app('filesystem')->url($cartItem->sparePart?->image_path) }}" class="object-contain w-full h-full p-2 opacity-80 group-hover:scale-110 transition-transform">
                                    @else
                                        <div class="text-2xl opacity-10">⚙️</div>
                                    @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-white font-black text-sm truncate leading-tight tracking-tight group-hover:text-indigo-400 transition-colors">{{ $cartItem->sparePart?->name ?? 'Unknown Component' }}</p>
                                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.15em] mt-2 leading-none flex items-center gap-2">
                                        <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                                        {{ $cartItem->quantity }} Units
                                    </p>
                                </div>
                                <div class="text-white font-black text-base shrink-0 tracking-tighter">
                                    ₹{{ number_format($cartItem->quantity * ($cartItem->sparePart?->price ?? 0)) }}
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-slate-950/80 p-8 rounded-[2rem] space-y-6 mb-12 border border-white/5 shadow-inner relative z-10">
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.2em]">
                            <span class="text-slate-500">Node Subtotal</span>
                            <span class="text-slate-200">₹{{ number_format($subtotal) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-[0.2em]">
                            <span class="text-slate-500">Logistics Link</span>
                            <span class="text-emerald-500">Complimentary</span>
                        </div>
                        <div class="h-px bg-white/5 my-2"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-white font-black text-xs uppercase tracking-[0.2em] mb-1">Total Payload</span>
                            <span class="text-indigo-400 font-black text-4xl tracking-tighter">₹{{ number_format($subtotal) }}</span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-4 py-6 rounded-3xl bg-indigo-500 text-white text-[11px] font-black uppercase tracking-[0.4em] hover:bg-indigo-400 transition-all shadow-[0_20px_50px_rgba(99,102,241,0.35)] group relative z-10">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Execute Finalization
                    </button>

                    <div class="mt-10 flex flex-col items-center gap-3 relative z-10">
                        <p class="text-[9px] text-slate-600 text-center uppercase tracking-[0.25em] font-black flex items-center justify-center gap-3 bg-slate-950/50 py-2 px-6 rounded-full border border-white/5">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                            Secure Neural Node Active
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('input[name="delivery_type"]').forEach(input => {
    input.addEventListener('change', function() {
        const fields = document.getElementById('delivery-fields');
        if (this.value === 'delivery') {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
        }
    });
});

document.querySelectorAll('input[name="address_id"]').forEach(input => {
    input.addEventListener('change', function() {
        const newFields = document.getElementById('new-address-fields');
        if (this.value === 'new') {
            newFields.classList.remove('hidden');
        } else {
            newFields.classList.add('hidden');
        }
    });
});
</script>
@endsection
