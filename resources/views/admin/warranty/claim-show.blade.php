@extends('layouts.admin')

@section('content')
<div class="p-6 md:p-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.warranty.claims') }}"
               class="bg-white/5 hover:bg-white/10 border border-white/10 text-gray-400 hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                ← Back to Claims
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">🛡️ Warranty Claim #{{ $claim->id }}</h1>
                <p class="text-gray-400 text-sm mt-0.5">Submitted {{ $claim->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <span class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest
            @if(in_array($claim->status, ['approved','resolved'])) bg-green-500/10 text-green-400 border border-green-500/20
            @elseif($claim->status === 'rejected') bg-red-500/10 text-red-400 border border-red-500/20
            @elseif($claim->status === 'reviewing') bg-blue-500/10 text-blue-400 border border-blue-500/20
            @else bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 @endif">
            {{ ucfirst($claim->status) }}
        </span>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Claim Details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Customer Info --}}
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">👤 Customer Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Name</p>
                        <p class="text-white font-bold">{{ $claim->customer?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-gray-300">{{ $claim->customer?->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Mobile</p>
                        <p class="text-gray-300">{{ $claim->customer?->mobile ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Customer Since</p>
                        <p class="text-gray-300">{{ $claim->customer?->created_at?->format('M Y') ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Certificate Info --}}
            @if($claim->certificate)
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">🛡️ Warranty Certificate</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Certificate ID</p>
                        <p class="text-white font-bold">#CERT-{{ str_pad($claim->certificate->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Type</p>
                        <p class="text-gray-300">{{ ucfirst($claim->certificate->warranty_type) }}</p>
                    </div>
                    @if($claim->certificate->sparePart)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Product</p>
                        <p class="text-white font-bold">{{ $claim->certificate->sparePart->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $claim->certificate->sparePart->brand }}</p>
                    </div>
                    @endif
                    @if($claim->certificate->serviceOrder)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Service Order</p>
                        <p class="text-white font-bold">#{{ $claim->certificate->serviceOrder->tc_job_id }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Warranty Start</p>
                        <p class="text-gray-300">
                            {{ $claim->certificate->warranty_start ? \Carbon\Carbon::parse($claim->certificate->warranty_start)->format('d M Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Warranty End</p>
                        @php $expired = $claim->certificate->warranty_end && $claim->certificate->warranty_end < now(); @endphp
                        <p class="{{ $expired ? 'text-red-400' : 'text-green-400' }} font-bold">
                            {{ $claim->certificate->warranty_end ? \Carbon\Carbon::parse($claim->certificate->warranty_end)->format('d M Y') : '—' }}
                            @if($expired) <span class="text-[10px]">(EXPIRED)</span> @endif
                        </p>
                    </div>
                    @if($claim->certificate->serial_number)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Serial Number</p>
                        <p class="text-gray-300 font-mono text-xs">{{ $claim->certificate->serial_number }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Claim Description --}}
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">📋 Issue Description</h2>
                <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $claim->issue_description ?? $claim->description ?? 'No description provided.' }}</p>
            </div>

            {{-- Admin Notes (if any) --}}
            @if($claim->admin_notes)
            <div class="bg-blue-500/5 border border-blue-500/20 rounded-2xl p-6">
                <h2 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-3">📝 Admin Notes</h2>
                <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $claim->admin_notes }}</p>
                @if($claim->handler)
                    <p class="text-gray-500 text-xs mt-3">— Handled by {{ $claim->handler->name }}
                        @if($claim->resolved_at) on {{ $claim->resolved_at->format('d M Y') }} @endif
                    </p>
                @endif
            </div>
            @endif
        </div>

        {{-- Right: Update Status --}}
        <div class="space-y-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 sticky top-6">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">⚡ Update Claim Status</h2>
                <form method="POST" action="{{ route('admin.warranty.claims.update', $claim->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">New Status</label>
                        <select name="status" required
                                class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-yellow-500/50">
                            <option value="reviewing"  {{ $claim->status === 'reviewing'  ? 'selected' : '' }}>🔍 Under Review</option>
                            <option value="approved"   {{ $claim->status === 'approved'   ? 'selected' : '' }}>✅ Approved</option>
                            <option value="rejected"   {{ $claim->status === 'rejected'   ? 'selected' : '' }}>❌ Rejected</option>
                            <option value="resolved"   {{ $claim->status === 'resolved'   ? 'selected' : '' }}>🎉 Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Admin Notes</label>
                        <textarea name="admin_notes" rows="5"
                                  placeholder="Add notes, resolution steps, or rejection reason..."
                                  class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-yellow-500/50 resize-none">{{ old('admin_notes', $claim->admin_notes) }}</textarea>
                    </div>
                    <button type="submit"
                            class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-3 rounded-xl text-sm transition-colors uppercase tracking-widest">
                        Update Claim
                    </button>
                </form>

                {{-- Timeline --}}
                <div class="mt-6 pt-6 border-t border-white/10">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">📅 Timeline</p>
                    <div class="space-y-3">
                        <div class="flex gap-3 items-start">
                            <div class="w-2 h-2 rounded-full bg-yellow-400 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-xs text-white font-bold">Claim Submitted</p>
                                <p class="text-[10px] text-gray-500">{{ $claim->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        @if($claim->resolved_at)
                        <div class="flex gap-3 items-start">
                            <div class="w-2 h-2 rounded-full bg-green-400 mt-1.5 shrink-0"></div>
                            <div>
                                <p class="text-xs text-white font-bold">{{ ucfirst($claim->status) }}</p>
                                <p class="text-[10px] text-gray-500">{{ $claim->resolved_at->format('d M Y, h:i A') }}</p>
                                @if($claim->handler)
                                    <p class="text-[10px] text-gray-600">by {{ $claim->handler->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
