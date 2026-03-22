@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Finance Dashboard</h1>
            <p class="text-gray-400 text-sm mt-1">Revenue, Expenses & Profit Overview — {{ now()->year }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.finance.expenses') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm rounded-lg transition-all">
                📋 Manage Expenses
            </a>
            <a href="{{ route('admin.finance.reports') }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg transition-all font-medium">
                📊 View Reports
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-emerald-600/20 to-emerald-900/10 border border-emerald-500/20 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-emerald-400 text-xs uppercase tracking-widest font-semibold">Total Revenue</span>
                <span class="text-2xl">💰</span>
            </div>
            <div class="text-3xl font-bold text-white">₹{{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="text-emerald-400 text-xs mt-1">FY {{ now()->year }}</div>
        </div>
        <div class="bg-gradient-to-br from-red-600/20 to-red-900/10 border border-red-500/20 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-red-400 text-xs uppercase tracking-widest font-semibold">Total Expenses</span>
                <span class="text-2xl">📉</span>
            </div>
            <div class="text-3xl font-bold text-white">₹{{ number_format($stats['total_expenses'], 0) }}</div>
            <div class="text-red-400 text-xs mt-1">FY {{ now()->year }}</div>
        </div>
        <div class="bg-gradient-to-br from-yellow-600/20 to-yellow-900/10 border border-yellow-500/20 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-yellow-400 text-xs uppercase tracking-widest font-semibold">Net Profit</span>
                <span class="text-2xl">📈</span>
            </div>
            <div class="text-3xl font-bold {{ $stats['net_profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">₹{{ number_format($stats['net_profit'], 0) }}</div>
            <div class="text-yellow-400 text-xs mt-1">Margin: {{ $stats['profit_margin'] }}%</div>
        </div>
        <div class="bg-gradient-to-br from-purple-600/20 to-purple-900/10 border border-purple-500/20 rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-purple-400 text-xs uppercase tracking-widest font-semibold">This Month</span>
                <span class="text-2xl">📅</span>
            </div>
            <div class="text-3xl font-bold text-white">₹{{ number_format($stats['this_month_revenue'], 0) }}</div>
            <div class="text-purple-400 text-xs mt-1">Exp: ₹{{ number_format($stats['this_month_expense'], 0) }}</div>
        </div>
    </div>

    {{-- Revenue Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white/5 border border-white/10 rounded-xl p-5">
            <h3 class="text-white font-semibold mb-3">Revenue Sources</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">🔧 Service Revenue</span>
                    <span class="text-emerald-400 font-semibold">₹{{ number_format($stats['service_revenue'], 0) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">🛒 Product Sales</span>
                    <span class="text-blue-400 font-semibold">₹{{ number_format($stats['product_revenue'], 0) }}</span>
                </div>
                <div class="border-t border-white/10 pt-3 flex items-center justify-between">
                    <span class="text-white font-medium text-sm">Total</span>
                    <span class="text-yellow-400 font-bold">₹{{ number_format($stats['total_revenue'], 0) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-xl p-5">
            <h3 class="text-white font-semibold mb-3">Expense Categories</h3>
            <div class="space-y-2">
                @if(!empty($expenseByCategory)) @foreach($expenseByCategory as $cat)
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm capitalize">{{ $cat->category }}</span>
                    <span class="text-red-400 text-sm font-medium">₹{{ number_format($cat->total, 0) }}</span>
                </div>
                @endforeach @else
                <p class="text-gray-500 text-sm text-center py-4">No expenses recorded.</p>
                @endif
            </div>
        </div>

        <div class="bg-white/5 border border-white/10 rounded-xl p-5">
            <h3 class="text-white font-semibold mb-3">Recent Expenses</h3>
            <div class="space-y-2">
                @if(!empty($recentExpenses->take(5))) @foreach($recentExpenses->take(5) as $exp)
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-white text-sm">{{ $exp->description }}</p>
                        <p class="text-gray-500 text-xs">{{ $exp->category }} · {{ $exp->expense_date->format('d M') }}</p>
                    </div>
                    <span class="text-red-400 text-sm font-medium">₹{{ number_format($exp->amount, 0) }}</span>
                </div>
                @endforeach @else
                <p class="text-gray-500 text-sm text-center py-4">No recent expenses.</p>
                @endif
            </div>
            <a href="{{ route('admin.finance.expenses') }}" class="block text-center text-yellow-400 text-xs mt-3 hover:underline">View All Expenses →</a>
        </div>
    </div>

    {{-- Revenue vs Expense Chart --}}
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <h3 class="text-white font-semibold mb-4">Revenue vs Expenses — Last 12 Months</h3>
        <canvas id="financeChart" height="80"></canvas>
    </div>

    {{-- Add Expense Form --}}
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <h3 class="text-white font-semibold mb-4">Record New Expense</h3>
        <form action="{{ route('admin.finance.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Category</label>
                    <select name="category" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm" required>
                        <option value="rent">Rent / Lease</option>
                        <option value="utilities">Utilities</option>
                        <option value="salaries">Salaries</option>
                        <option value="parts">Spare Parts</option>
                        <option value="marketing">Marketing</option>
                        <option value="equipment">Equipment</option>
                        <option value="transport">Transport</option>
                        <option value="misc">Miscellaneous</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Description</label>
                    <input type="text" name="description" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm" placeholder="e.g., Monthly shop rent" required>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm" placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Date</label>
                    <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Payment Mode</label>
                    <select name="payment_mode" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-2 rounded-lg text-sm transition-all">
                        ✅ Save Expense
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('financeChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Revenue',
                data: @json($revenueData),
                backgroundColor: 'rgba(16, 185, 129, 0.3)',
                borderColor: '#10b981',
                borderWidth: 2,
                borderRadius: 4,
            },
            {
                label: 'Expenses',
                data: @json($expenseData),
                backgroundColor: 'rgba(239, 68, 68, 0.3)',
                borderColor: '#ef4444',
                borderWidth: 2,
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#94a3b8' } }
        },
        scales: {
            x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: {
                ticks: { color: '#64748b', callback: v => '₹' + (v/1000).toFixed(0) + 'K' },
                grid: { color: 'rgba(255,255,255,0.05)' }
            }
        }
    }
});
</script>
@endpush
