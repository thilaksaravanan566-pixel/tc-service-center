@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-7 fade-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-xs text-gray-500 mt-0.5">Here's what's happening at <strong class="text-gray-400">{{ config('custom.company_name', 'TC Service Center') }}</strong> today.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.services.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Service Order
            </a>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Card 1: Total Orders --}}
        <div class="card p-5 fade-up stagger-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="badge badge-green">↑ 12%</span>
            </div>
            <p class="text-2xl font-black text-white">{{ $stats['total_orders'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Total Service Orders</p>
        </div>

        {{-- Card 2: Revenue --}}
        <div class="card p-5 fade-up stagger-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge badge-green">↑ 8.2%</span>
            </div>
            <p class="text-2xl font-black text-white">₹{{ number_format(array_sum($profits ?? [0]), 0) }}</p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Total Revenue (YTD)</p>
        </div>

        {{-- Card 3: Pending --}}
        <div class="card p-5 fade-up stagger-3">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-yellow-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge badge-yellow">Needs action</span>
            </div>
            <p class="text-2xl font-black text-white">{{ $stats['pending_repairs'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Pending Repairs</p>
        </div>

        {{-- Card 4: Customers --}}
        <div class="card p-5 fade-up stagger-4">
            <div class="flex items-center justify-between mb-4">
                <div class="w-9 h-9 rounded-xl bg-purple-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="badge badge-blue">↑ 3%</span>
            </div>
            <p class="text-2xl font-black text-white">{{ $stats['total_customers'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1 font-medium">Registered Customers</p>
        </div>

    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Revenue Chart --}}
        <div class="card p-6 xl:col-span-1 fade-up stagger-1">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-white">Revenue Overview</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Monthly service revenue this year</p>
                </div>
            </div>
            <div style="height: 240px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- NEW: Category Revenue Split --}}
        <div class="card p-6 fade-up stagger-2 flex flex-col">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-white">Revenue Split</h3>
                <p class="text-xs text-gray-500 mt-0.5">Dealer vs Online vs Walk-in</p>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <div style="width:180px; height:180px;">
                    <canvas id="revenueSplitDonut"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Dealer</p>
                    <p class="text-xs font-black text-indigo-400">₹{{ number_format($revenue_split['dealer'] ?? 0, 0) }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Online</p>
                    <p class="text-xs font-black text-emerald-400">₹{{ number_format($revenue_split['online'] ?? 0, 0) }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">Walk-in</p>
                    <p class="text-xs font-black text-amber-400">₹{{ number_format($revenue_split['walkin'] ?? 0, 0) }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- NEW: Category Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="card p-6 fade-up stagger-1 border-l-4 border-indigo-500/50">
            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Dealer Orders</h4>
            <p class="text-2xl font-black text-white mt-1">{{ $category_orders['dealer'] ?? 0 }}</p>
            <div class="mt-2 text-[10px] font-semibold text-indigo-400">Partner Business</div>
        </div>
        <div class="card p-6 fade-up stagger-2 border-l-4 border-emerald-500/50">
            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Online Orders</h4>
            <p class="text-2xl font-black text-white mt-1">{{ $category_orders['online'] ?? 0 }}</p>
            <div class="mt-2 text-[10px] font-semibold text-emerald-400">Digital Bookings</div>
        </div>
        <div class="card p-6 fade-up stagger-3 border-l-4 border-amber-500/50">
            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Walk-in Orders</h4>
            <p class="text-2xl font-black text-white mt-1">{{ $category_orders['walkin'] ?? 0 }}</p>
            <div class="mt-2 text-[10px] font-semibold text-amber-400">Direct Shop Customers</div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Recent Service Orders --}}
        <div class="card overflow-hidden fade-up stagger-2">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <h3 class="text-sm font-bold text-white">Recent Service Orders</h3>
                <a href="{{ route('admin.services.index') }}" class="text-[11px] font-semibold text-indigo-400 hover:text-indigo-300 transition">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left whitespace-nowrap">Job ID</th>
                            <th class="text-left whitespace-nowrap">Customer</th>
                            <th class="text-left whitespace-nowrap hidden sm:table-cell">Device</th>
                            <th class="text-right whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($recent_services)) @foreach($recent_services as $service)
                        <tr class="group transition-colors">
                            <td>
                                <a href="{{ route('admin.services.show', $service->id) }}" class="text-indigo-400 hover:text-indigo-300 font-mono text-xs font-bold">{{ $service->tc_job_id }}</a>
                            </td>
                            <td class="font-semibold text-gray-200">{{ $service->customer->name ?? 'N/A' }}</td>
                            <td class="hidden sm:table-cell text-gray-400">{{ $service->device->brand ?? '' }} {{ $service->device->model ?? '' }}</td>
                            <td class="text-right">
                                @php
                                $statusMap = [
                                    'received'    => 'badge-yellow',
                                    'assigned'    => 'badge-blue',
                                    'repairing'   => 'badge-blue',
                                    'completed'   => 'badge-green',
                                    'delivered'   => 'badge-green',
                                    'cancelled'   => 'badge-red',
                                ];
                                @endphp
                                <span class="badge {{ $statusMap[$service->status] ?? 'badge-gray' }}">
                                    {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach @else
                        <tr><td colspan="4" class="text-center text-gray-500 py-10 text-xs">No recent service orders.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Product Orders --}}
        <div class="card overflow-hidden fade-up stagger-3">
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <h3 class="text-sm font-bold text-white">Recent Product Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[11px] font-semibold text-indigo-400 hover:text-indigo-300 transition">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Product</th>
                            <th class="text-left hidden sm:table-cell">Customer</th>
                            <th class="text-right">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($recent_product_orders)) @foreach($recent_product_orders as $order)
                        <tr class="transition-colors">
                            <td>
                                <p class="text-gray-200 font-semibold text-xs">{{ $order->sparePart->name ?? 'Unknown' }}</p>
                                <p class="text-gray-500 text-[10px] mt-0.5">Qty: {{ $order->quantity }}</p>
                            </td>
                            <td class="hidden sm:table-cell text-gray-400">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="text-right">
                                <span class="badge {{ $order->is_paid ? 'badge-green' : 'badge-yellow' }}">
                                    {{ $order->is_paid ? 'Paid' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach @else
                        <tr><td colspan="3" class="text-center text-gray-500 py-10 text-xs">No recent product orders.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

{{-- Charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const months  = {!! json_encode($months ?? []) !!};
    const profits = {!! json_encode($profits ?? []) !!};

    Chart.defaults.color = '#6b7280';
    Chart.defaults.font.family = 'Inter';
    Chart.defaults.font.size   = 11;

    // ── Revenue Line Chart ──────────────────────
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (₹)',
                data: profits,
                borderColor: '#6366f1',
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0,0,0,220);
                    g.addColorStop(0, 'rgba(99,102,241,0.25)');
                    g.addColorStop(1, 'rgba(99,102,241,0)');
                    return g;
                },
                borderWidth: 2.5,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#0f0f17',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#13131f',
                    borderColor: 'rgba(255,255,255,.08)',
                    borderWidth: 1,
                    titleColor: '#fff', bodyColor: '#9ca3af',
                    padding: 12, cornerRadius: 10,
                    displayColors: false,
                    callbacks: { label: ctx => '₹' + ctx.parsed.y.toLocaleString() }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,.05)', drawBorder: false }, ticks: { font: { size: 10 } } },
                y: {
                    grid: { color: 'rgba(255,255,255,.05)', drawBorder: false, borderDash: [4,4] },
                    ticks: { font: { size: 10 }, callback: v => '₹' + (v/1000).toFixed(0) + 'k' },
                    beginAtZero: true
                }
            }
        }
    });

    // ── Revenue Split Donut ────────────────────────
    const revDealer = {{ $revenue_split['dealer'] ?? 0 }};
    const revOnline = {{ $revenue_split['online'] ?? 0 }};
    const revWalkin = {{ $revenue_split['walkin'] ?? 0 }};

    new Chart(document.getElementById('revenueSplitDonut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Dealer', 'Online', 'Walk-in'],
            datasets: [{
                data: [revDealer, revOnline, revWalkin],
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: {
            cutout: '75%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#13131f',
                    borderColor: 'rgba(255,255,255,.08)',
                    borderWidth: 1,
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    padding: 10,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ' ₹' + ctx.parsed.toLocaleString()
                    }
                }
            }
        }
    });
});
</script>
@endsection