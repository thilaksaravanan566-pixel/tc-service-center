@extends('layouts.customer')
@section('title', 'Warranty')

@section('content')
<div class="animate-slide-up">

    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <div>
            <h1 class="page-title">Warranty</h1>
            <p class="page-sub">{{ $warranties->count() }} warranty certificate{{ $warranties->count() !== 1 ? 's' : '' }} on your account.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Browse Shop
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span class="alert-text">{{ session('success') }}</span>
        </div>
    @endif

    @if($warranties->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p class="empty-state-title">No warranties yet</p>
                <p class="empty-state-text">Purchase hardware or book a repair to register a warranty on your devices.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:16px">Explore Products</a>
            </div>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px">
            @foreach($warranties as $warranty)
            @php
                $progress = $warranty->progress_percent;
                $daysLeft  = $warranty->days_remaining;
                $isActive  = $warranty->is_active;
            @endphp
            <div class="card" style="padding:24px;display:flex;flex-direction:column;gap:16px;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">

                {{-- Header --}}
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:42px;height:42px;border-radius:var(--radius-sm);background:var(--primary-50);display:flex;align-items:center;justify-content:center;font-size:1.3rem">
                            {{ $warranty->warranty_type === 'product' ? '📦' : '🔧' }}
                        </div>
                        <div>
                            <p style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em">{{ $warranty->warranty_type === 'product' ? 'Product' : 'Service' }} Warranty</p>
                            <span class="badge {{ $isActive ? 'badge-green' : 'badge-red' }}" style="margin-top:4px">
                                {{ $isActive ? 'Active' : 'Expired' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <h3 style="font-size:0.95rem;font-weight:700;color:var(--text-primary);line-height:1.3">
                        @if($warranty->sparePart) {{ $warranty->sparePart->name }}
                        @elseif($warranty->serviceOrder) Service Job #{{ $warranty->serviceOrder->tc_job_id }}
                        @else Protection Certificate
                        @endif
                    </h3>
                    @if($warranty->serial_number)
                        <p style="font-size:0.72rem;color:var(--text-muted);margin-top:4px">SN: {{ $warranty->serial_number }}</p>
                    @endif
                </div>

                {{-- Dates --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div style="padding:10px;background:var(--primary-50);border-radius:var(--radius-sm)">
                        <p style="font-size:0.65rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px">Start Date</p>
                        <p style="font-size:0.8rem;font-weight:600;color:var(--text-primary)">{{ $warranty->warranty_start->format('d M Y') }}</p>
                    </div>
                    <div style="padding:10px;background:var(--primary-50);border-radius:var(--radius-sm)">
                        <p style="font-size:0.65rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px">Expiry Date</p>
                        <p style="font-size:0.8rem;font-weight:600;color:var(--text-primary)">{{ $warranty->warranty_end->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Progress --}}
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:var(--text-muted);margin-bottom:6px">
                        <span>Coverage remaining</span>
                        <span style="font-weight:600;color:{{ $isActive ? ($daysLeft < 30 ? '#ef4444' : 'var(--primary-dark)') : 'var(--text-muted)' }}">
                            {{ $isActive ? "{$daysLeft} days left" : 'Expired' }}
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $daysLeft < 30 ? 'danger' : '' }}" style="width:{{ $progress }}%"></div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:8px;margin-top:4px">
                    <a href="{{ route('customer.warranty.show', $warranty->id) }}" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">View Details</a>
                    @if($isActive && !$warranty->claims->whereIn('status', ['pending','reviewing'])->count())
                        <a href="{{ route('customer.warranty.show', $warranty->id) }}#claim" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">File Claim</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
