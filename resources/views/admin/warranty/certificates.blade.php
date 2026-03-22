@extends('layouts.admin')

@section('content')
<div class="p-6 md:p-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">🛡️ Warranty Certificates</h1>
            <p class="text-gray-400 text-sm mt-1">Issue and manage product & service warranty certificates</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.warranty.claims') }}"
               class="bg-white/5 hover:bg-white/10 text-gray-300 border border-white/10 font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2 transition-colors">
                ← Warranty Claims
            </a>
            <button onclick="document.getElementById('certModal').classList.remove('hidden')"
               class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2 transition-colors">
                + Issue Certificate
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Strip --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-yellow-400">{{ $certificates->total() }}</p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Total</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-green-400">
                {{ $certificates->getCollection()->filter(fn($c) => $c->warranty_end && $c->warranty_end >= now())->count() }}
            </p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Active</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-red-400">
                {{ $certificates->getCollection()->filter(fn($c) => $c->warranty_end && $c->warranty_end < now())->count() }}
            </p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Expired</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-blue-400">
                {{ $certificates->getCollection()->sum(fn($c) => $c->claims->count()) }}
            </p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Total Claims</p>
        </div>
    </div>

    {{-- Certificates Table --}}
    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Certificate</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Customer</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Product / Service</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Validity</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Status</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Claims</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                @php
                    $isExpired = $cert->warranty_end && $cert->warranty_end < now();
                    $daysLeft  = $cert->warranty_end ? now()->diffInDays($cert->warranty_end, false) : null;
                @endphp
                <tr class="border-b border-white/5 hover:bg-white/3 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-white font-bold text-xs uppercase tracking-wider">#CERT-{{ str_pad($cert->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-gray-500 text-[10px] mt-0.5">{{ $cert->warranty_type === 'product' ? '📦 Product' : '🔧 Service' }}</p>
                        @if($cert->serial_number)
                            <p class="text-gray-600 text-[10px]">S/N: {{ $cert->serial_number }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white font-bold">{{ $cert->customer?->name ?? 'N/A' }}</p>
                        <p class="text-gray-500 text-xs">{{ $cert->customer?->email }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-300 text-sm">
                        @if($cert->warranty_type === 'product' && $cert->sparePart)
                            <p class="font-semibold">{{ $cert->sparePart->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $cert->sparePart->brand }}</p>
                        @elseif($cert->warranty_type === 'service' && $cert->serviceOrder)
                            <p class="font-semibold">Job #{{ $cert->serviceOrder->tc_job_id }}</p>
                            <p class="text-gray-500 text-xs">Service Order</p>
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white text-xs font-bold">
                            {{ $cert->warranty_start ? \Carbon\Carbon::parse($cert->warranty_start)->format('d M Y') : '—' }}
                        </p>
                        <p class="text-gray-500 text-xs">to</p>
                        <p class="{{ $isExpired ? 'text-red-400' : 'text-green-400' }} text-xs font-bold">
                            {{ $cert->warranty_end ? \Carbon\Carbon::parse($cert->warranty_end)->format('d M Y') : '—' }}
                        </p>
                        @if($daysLeft !== null && !$isExpired)
                            <p class="text-yellow-500 text-[10px] mt-0.5">{{ $daysLeft }}d left</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($isExpired)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-500/10 text-red-400">Expired</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-500/10 text-green-400">Active</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($cert->claims->count() > 0)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-yellow-500/10 text-yellow-400">
                                {{ $cert->claims->count() }} claim(s)
                            </span>
                        @else
                            <span class="text-gray-600 text-xs">No claims</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <p class="text-gray-500 text-4xl mb-3">🛡️</p>
                        <p class="text-gray-400 font-bold">No warranty certificates issued yet.</p>
                        <p class="text-gray-600 text-sm mt-1">Click "Issue Certificate" to create the first one.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">{{ $certificates->links() }}</div>
</div>

{{-- Issue Certificate Modal --}}
<div id="certModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <h2 class="text-lg font-bold text-white">🛡️ Issue New Warranty Certificate</h2>
            <button onclick="document.getElementById('certModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-white text-2xl leading-none transition-colors">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.warranty.certificates.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Customer *</label>
                <select name="customer_id" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                    <option value="">— Select Customer —</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Warranty Type *</label>
                <select name="warranty_type" id="warrantyType" required onchange="toggleWarrantyType(this.value)"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                    <option value="">— Select Type —</option>
                    <option value="product" {{ old('warranty_type') === 'product' ? 'selected' : '' }}>📦 Product Warranty</option>
                    <option value="service" {{ old('warranty_type') === 'service' ? 'selected' : '' }}>🔧 Service Warranty</option>
                </select>
            </div>

            <div id="productField" class="hidden">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Spare Part</label>
                <select name="spare_part_id"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                    <option value="">— Select Part —</option>
                    @foreach($spareParts as $part)
                        <option value="{{ $part->id }}" {{ old('spare_part_id') == $part->id ? 'selected' : '' }}>
                            {{ $part->name }} ({{ $part->brand }}) — {{ $part->warranty_months }}mo warranty
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="serviceField" class="hidden">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Service Order</label>
                <select name="service_order_id"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                    <option value="">— Select Service Order —</option>
                    @foreach($serviceOrders as $so)
                        <option value="{{ $so->id }}" {{ old('service_order_id') == $so->id ? 'selected' : '' }}>
                            #{{ $so->tc_job_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="e.g. SN-XXXXX"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-yellow-500/50">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Purchase Date *</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Warranty Start *</label>
                    <input type="date" name="warranty_start" value="{{ old('warranty_start') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Warranty End *</label>
                    <input type="date" name="warranty_end" value="{{ old('warranty_end') }}" required
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Notes</label>
                <textarea name="notes" rows="3" placeholder="Additional warranty terms or notes..."
                          class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-yellow-500/50 resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-black font-black py-3 rounded-xl text-sm transition-colors uppercase tracking-widest">
                    🛡️ Issue Certificate
                </button>
                <button type="button" onclick="document.getElementById('certModal').classList.add('hidden')"
                        class="px-6 bg-white/5 hover:bg-white/10 text-gray-400 border border-white/10 font-bold py-3 rounded-xl text-sm transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleWarrantyType(val) {
    document.getElementById('productField').classList.toggle('hidden', val !== 'product');
    document.getElementById('serviceField').classList.toggle('hidden', val !== 'service');
}
// Show modal if validation errors occurred
@if($errors->any() && old('warranty_type'))
document.getElementById('certModal').classList.remove('hidden');
toggleWarrantyType('{{ old('warranty_type') }}');
@endif
</script>
@endsection
