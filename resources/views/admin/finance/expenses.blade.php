@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Expense Management</h1>
            <p class="text-gray-400 text-sm mt-1">Track all business expenses</p>
        </div>
        <a href="{{ route('admin.finance.dashboard') }}" class="px-4 py-2 bg-white/5 border border-white/10 text-white text-sm rounded-lg hover:bg-white/10 transition-all">
            ← Finance Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="category" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
            <option value="">All Categories</option>
            @foreach($categories as $key => $label)
            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="month" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
            <option value="">All Months</option>
            @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m)->format('F') }}</option>
            @endfor
        </select>
        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg">Filter</button>
        <span class="ml-auto text-gray-400 text-sm self-center">Total: <span class="text-yellow-400 font-bold">₹{{ number_format($totalAmount, 0) }}</span></span>
    </form>

    {{-- Expenses Table --}}
    <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-3 px-4">Description</th>
                    <th class="text-left py-3 px-4">Category</th>
                    <th class="text-left py-3 px-4">Date</th>
                    <th class="text-left py-3 px-4">Mode</th>
                    <th class="text-right py-3 px-4">Amount</th>
                    <th class="text-center py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($expenses)) @foreach($expenses as $exp)
                <tr class="border-t border-white/5">
                    <td class="py-3 px-4">
                        <p class="text-white text-sm">{{ $exp->description }}</p>
                        @if($exp->notes)
                        <p class="text-gray-500 text-xs">{{ $exp->notes }}</p>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs bg-white/5 text-gray-300 capitalize">{{ $exp->category }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-400 text-sm">{{ $exp->expense_date->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-gray-400 text-sm capitalize">{{ $exp->payment_mode }}</td>
                    <td class="py-3 px-4 text-right text-red-400 font-semibold">₹{{ number_format($exp->amount, 2) }}</td>
                    <td class="py-3 px-4 text-center">
                        <form action="{{ route('admin.finance.expenses.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Delete this expense?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs px-2 py-1 bg-red-500/20 text-red-400 rounded hover:opacity-80">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-500">No expenses recorded. Add one from Finance Dashboard.</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-white/5">{{ $expenses->withQueryString()->links() }}</div>
    </div>

</div>
@endsection
