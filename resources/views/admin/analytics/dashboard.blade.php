@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">📊 Business Analytics</h1>
            <p class="text-gray-400 text-sm mt-1">Real-time performance dashboards and KPI insights</p>
        </div>
        <span class="text-gray-500 text-sm">{{ now()->format('d M Y, H:i') }}</span>
    </div>

    {{-- Core KPIs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-600/20 to-blue-900/10 border border-blue-500/20 rounded-xl p-4">
            <p class="text-blue-400 text-xs uppercase tracking-widest mb-1">Total Customers</p>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_customers']) }}</p>
            <p class="text-blue-400 text-xs mt-1">+{{ $stats['new_customers_month'] }} this month</p>
        </div>
        <div class="bg-gradient-to-br from-purple-600/20 to-purple-900/10 border border-purple-500/20 rounded-xl p-4">
            <p class="text-purple-400 text-xs uppercase tracking-widest mb-1">Total Repairs</p>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_repairs']) }}</p>
            <p class="text-purple-400 text-xs mt-1">{{ $stats['repairs_this_month'] }} this month</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-600/20 to-emerald-900/10 border border-emerald-500/20 rounded-xl p-4">
            <p class="text-emerald-400 text-xs uppercase tracking-widest mb-1">Monthly Revenue</p>
            <p class="text-3xl font-bold text-white">₹{{ number_format($stats['monthly_revenue'], 0) }}</p>
            <p class="text-emerald-400 text-xs mt-1">FY: ₹{{ number_format($stats['yearly_revenue'], 0) }}</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-600/20 to-yellow-900/10 border border-yellow-500/20 rounded-xl p-4">
            <p class="text-yellow-400 text-xs uppercase tracking-widest mb-1">Pending Repairs</p>
            <p class="text-3xl font-bold text-white">{{ $stats['pending_repairs'] }}</p>
            <p class="text-yellow-400 text-xs mt-1">{{ $stats['completed_repairs'] }} completed</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Revenue Trend — Last 12 Months</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Monthly Repairs & Customer Growth</h3>
            <canvas id="growthChart" height="120"></canvas>
        </div>
    </div>

    {{-- Top Products + Technician Performance --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top Selling Products --}}
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">🏆 Top Selling Products</h3>
            @if(!empty($topProducts)) @foreach($topProducts as $i => $part)
            <div class="flex items-center justify-between py-2.5 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 flex items-center justify-center bg-yellow-500/10 text-yellow-400 rounded text-xs font-bold">{{ $i+1 }}</span>
                    <div>
                        <p class="text-white text-sm font-medium">{{ $part->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $part->category }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-emerald-400 text-sm font-semibold">{{ $part->orders_count }} orders</p>
                    <p class="text-gray-500 text-xs">₹{{ number_format($part->total_revenue ?? 0, 0) }}</p>
                </div>
            </div>
            @endforeach @else
            <p class="text-gray-500 text-center py-6">No product orders yet.</p>
            @endif
        </div>

        {{-- Technician Performance --}}
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">👨‍🔧 Technician Performance</h3>
            @if(!empty($technicianPerformance)) @foreach($technicianPerformance as $i => $tech)
            <div class="flex items-center justify-between py-2.5 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tech->name) }}&background=ca8a04&color=fff&size=32" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="text-white text-sm font-medium">{{ $tech->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $tech->total_jobs }} total jobs</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-yellow-400 font-semibold">{{ $tech->completed_jobs }}</p>
                    <p class="text-gray-500 text-xs">completed</p>
                </div>
            </div>
            @endforeach @else
            <p class="text-gray-500 text-center py-6">No technicians found.</p>
            @endif
        </div>
    </div>

    {{-- Service Status Breakdown + Device Types --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Service Status Breakdown</h3>
            <canvas id="statusChart" height="140"></canvas>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Device Types Serviced</h3>
            <canvas id="deviceChart" height="140"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartDefaults = {
    plugins: { legend: { labels: { color: '#94a3b8', usePointStyle: true } } },
    scales: {
        x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,0.04)' } },
        y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,0.04)' } }
    }
};

// Revenue
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Revenue (₹)',
            data: @json($salesData),
            borderColor: '#eab308',
            backgroundColor: 'rgba(234,179,8,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#eab308',
        }]
    },
    options: { responsive: true, ...chartDefaults }
});

// Growth
new Chart(document.getElementById('growthChart'), {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Repairs',
                data: @json($repairData),
                borderColor: '#a78bfa',
                backgroundColor: 'rgba(167,139,250,0.1)',
                fill: true, tension: 0.4,
            },
            {
                label: 'New Customers',
                data: @json($customerData),
                borderColor: '#34d399',
                backgroundColor: 'rgba(52,211,153,0.1)',
                fill: true, tension: 0.4,
            }
        ]
    },
    options: { responsive: true, ...chartDefaults }
});

// Status Pie
const statuses = @json($serviceStatusBreakdown);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statuses).map(s => s.replace(/_/g, ' ').toUpperCase()),
        datasets: [{
            data: Object.values(statuses),
            backgroundColor: ['#3b82f6','#f59e0b','#8b5cf6','#10b981','#ef4444','#06b6d4'],
            borderWidth: 2,
            borderColor: '#0a0a0f',
        }]
    },
    options: { responsive: true, plugins: { legend: { labels: { color: '#94a3b8', usePointStyle: true } } } }
});

// Device Types
const deviceTypes = @json($deviceTypes->pluck('count', 'type'));
new Chart(document.getElementById('deviceChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(deviceTypes).map(s => s.toUpperCase()),
        datasets: [{
            label: 'Count',
            data: Object.values(deviceTypes),
            backgroundColor: 'rgba(202,138,4,0.4)',
            borderColor: '#ca8a04',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, ...chartDefaults }
});
</script>
@endpush
