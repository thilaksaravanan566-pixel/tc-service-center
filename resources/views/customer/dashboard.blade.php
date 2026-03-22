@extends('layouts.customer')
@section('title', 'Dashboard')

@section('content')
<div class="animate-slide-up" style="max-width:1280px">

    {{-- Welcome Banner --}}
    <div class="card mb-6" style="padding:24px 28px;background:linear-gradient(135deg,#0ea5e9 0%,#0369a1 100%);border:none;display:flex;flex-wrap:wrap;gap:16px;justify-content:space-between;align-items:center">
        <div>
            <p style="font-size:0.75rem;color:rgba(255,255,255,0.7);margin-bottom:4px;font-weight:500">Welcome back,</p>
            <h1 style="font-size:1.5rem;font-weight:700;color:#ffffff;line-height:1.2">{{ auth('customer')->user()->name ?? 'Customer' }}</h1>
            <p style="font-size:0.8rem;color:rgba(255,255,255,0.65);margin-top:4px">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('customer.service.book') }}" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.25);padding:9px 18px;font-size:0.8rem">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Book Service
            </a>
            <a href="{{ route('shop.index') }}" class="btn" style="background:#fff;color:var(--primary-dark);padding:9px 18px;font-size:0.8rem">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Shop Now
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">

        <div class="stat-card animate-slide-up">
            <div class="stat-icon" style="background:var(--primary-50)">
                <svg style="width:22px;height:22px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <div class="stat-label">Active Repairs</div>
                <div class="stat-value">{{ $orders->whereNotIn('status', ['completed','cancelled'])->count() }}</div>
                <div class="stat-sub">In progress</div>
            </div>
        </div>

        <div class="stat-card animate-slide-up-delay">
            <div class="stat-icon" style="background:#f0fdf4">
                <svg style="width:22px;height:22px;color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <div class="stat-label">Completed Jobs</div>
                <div class="stat-value">{{ $orders->where('status','completed')->count() }}</div>
                <div class="stat-sub">All time</div>
            </div>
        </div>

        <div class="stat-card animate-slide-up-delay">
            <div class="stat-icon" style="background:#fffbeb">
                <svg style="width:22px;height:22px;color:#d97706" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <div class="stat-label">Cart Items</div>
                <div class="stat-value">{{ $cartCount ?? 0 }}</div>
                <div class="stat-sub">Saved for checkout</div>
            </div>
        </div>

        <div class="stat-card animate-slide-up-delay-2">
            <div class="stat-icon" style="background:#fdf4ff">
                <svg style="width:22px;height:22px;color:#9333ea" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="stat-label">Total Spent</div>
                <div class="stat-value" style="font-size:1.1rem">₹{{ number_format(auth('customer')->user()->productOrders()->sum('total_price') ?? 0) }}</div>
                <div class="stat-sub">Lifetime value</div>
            </div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start" class="flex-col-mobile">

        {{-- Recent Repairs --}}
        <div class="card animate-slide-up">
            <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                <div class="section-title">
                    <span class="section-title-accent"></span> Recent Repairs
                </div>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-secondary">View all</a>
            </div>

            @if(($recentOrders ?? collect())->count() > 0)
                <div style="overflow:hidden">
                    @foreach($recentOrders as $order)
                    <?php /** @var \App\Models\ServiceOrder $order */ ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 24px;border-bottom:1px solid var(--border);gap:12px" onmouseover="this.style.background='var(--primary-50)'" onmouseout="this.style.background='transparent'" class="transition-colors">
                        <div style="display:flex;align-items:center;gap:12px;min-width:0">
                            <div style="width:40px;height:40px;border-radius:var(--radius-sm);background:var(--primary-50);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem">
                                {{ ($order->device && $order->device->brand == 'Apple') ? '🍎' : '💻' }}
                            </div>
                            <div style="min-width:0">
                                <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">#{{ $order->tc_job_id }} — {{ $order->device?->model ?? 'Unknown Device' }}</p>
                                <p style="font-size:0.72rem;color:var(--text-muted);margin-top:2px">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
                            @php
                                $statusColors = [
                                    'completed' => 'badge-green', 'cancelled' => 'badge-red',
                                    'pending' => 'badge-amber', 'delivered' => 'badge-green',
                                ];
                                $badgeClass = $statusColors[$order->status] ?? 'badge-sky';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                            <a href="{{ route('tracking.show', $order->tc_job_id) }}" class="icon-btn" style="width:32px;height:32px">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="empty-state-title">No repairs yet</p>
                    <p class="empty-state-text">Book a service to get started with your first repair job.</p>
                    <a href="{{ route('customer.service.book') }}" class="btn btn-primary" style="margin-top:16px">Book Service</a>
                </div>
            @endif
        </div>

        {{-- Right Column --}}
        <div style="display:flex;flex-direction:column;gap:16px">

            {{-- Tech Lab Card --}}
            <div class="card" style="padding:24px;background:linear-gradient(135deg,#0ea5e9 0%,#0369a1 100%);border:none">
                <div style="font-size:2rem;margin-bottom:12px">🧪</div>
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:6px">Thambu Tech Lab</h3>
                <p style="font-size:0.8rem;color:rgba(255,255,255,0.7);line-height:1.5;margin-bottom:16px">Interactive hardware simulations, PC building games & repair training.</p>
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="btn" style="background:#fff;color:var(--primary-dark);width:100%;justify-content:center;font-size:0.8rem">Enter Lab →</a>
            </div>

            {{-- Quick Links --}}
            <div class="card" style="padding:20px">
                <div class="section-title" style="margin-bottom:14px">
                    <span class="section-title-accent"></span> Quick Actions
                </div>
                <div style="display:flex;flex-direction:column;gap:4px">
                    <a href="{{ route('customer.service.book') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:0.875rem;font-weight:500;text-decoration:none;transition:all 0.15s" onmouseover="this.style.background='var(--primary-50)';this.style.color='var(--primary-dark)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Book a Repair
                    </a>
                    <a href="{{ route('customer.warranty.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:0.875rem;font-weight:500;text-decoration:none;transition:all 0.15s" onmouseover="this.style.background='var(--primary-50)';this.style.color='var(--primary-dark)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        View Warranty
                    </a>
                    <a href="{{ route('shop.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:0.875rem;font-weight:500;text-decoration:none;transition:all 0.15s" onmouseover="this.style.background='var(--primary-50)';this.style.color='var(--primary-dark)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Browse Spare Parts
                    </a>
                    <a href="{{ route('customer.service.custom-build') }}" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:0.875rem;font-weight:500;text-decoration:none;transition:all 0.15s" onmouseover="this.style.background='var(--primary-50)';this.style.color='var(--primary-dark)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
                        <svg style="width:16px;height:16px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        Custom PC Build
                    </a>
                </div>
            </div>

            {{-- System Status --}}
            <div class="card" style="padding:20px">
                <div class="section-title" style="margin-bottom:14px">
                    <span class="section-title-accent"></span> System Status
                </div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:0.8rem;color:var(--text-secondary)">Service Portal</span>
                        <span class="badge badge-green">Operational</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:0.8rem;color:var(--text-secondary)">Order Tracking</span>
                        <span class="badge badge-green">Online</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:0.8rem;color:var(--text-secondary)">Workshop</span>
                        <span class="badge badge-sky">Open</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media (max-width:900px) {
    .flex-col-mobile { grid-template-columns: 1fr !important; }
}
</style>
@endsection
