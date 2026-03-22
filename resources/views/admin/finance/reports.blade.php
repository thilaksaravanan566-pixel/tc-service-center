@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Profit & Loss Report</h1>
            <p class="text-gray-400 text-sm mt-1">Annual financial report — {{ $year }}</p>
        </div>
        <div class="flex gap-3">
            @for($y = now()->year; $y >= 2024; $y--)
            <a href="{{ route('admin.finance.reports', ['year' => $y]) }}" class="px-3 py-1.5 text-sm rounded-lg {{ $y == $year ? 'bg-yellow-600 text-white' : 'bg-white/5 text-gray-400 hover:bg-white/10' }}">{{ $y }}</a>
            @endfor
        </div>
    </div>

    {{-- Annual Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $totalRev  = $months->sum('revenue');
            $totalExp  = $months->sum('expense');
            $totalProf = $months->sum('profit');
        @endphp
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-5 text-center">
            <p class="text-emerald-400 text-xs uppercase tracking-widest mb-1">Annual Revenue</p>
            <p class="text-3xl font-bold text-white">₹{{ number_format($totalRev, 0) }}</p>
        </div>
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-5 text-center">
            <p class="text-red-400 text-xs uppercase tracking-widest mb-1">Annual Expenses</p>
            <p class="text-3xl font-bold text-white">₹{{ number_format($totalExp, 0) }}</p>
        </div>
        <div class="{{ $totalProf >= 0 ? 'bg-yellow-500/10 border-yellow-500/30' : 'bg-red-500/10 border-red-500/30' }} border rounded-xl p-5 text-center">
            <p class="{{ $totalProf >= 0 ? 'text-yellow-400' : 'text-red-400' }} text-xs uppercase tracking-widest mb-1">Net Profit</p>
            <p class="text-3xl font-bold {{ $totalProf >= 0 ? 'text-emerald-400' : 'text-red-400' }}">₹{{ number_format($totalProf, 0) }}</p>
        </div>
    </div>

    {{-- Monthly Table --}}
    <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-3 px-4">Month</th>
                    <th class="text-right py-3 px-4">Revenue</th>
                    <th class="text-right py-3 px-4">Expenses</th>
                    <th class="text-right py-3 px-4">Net Profit</th>
                    <th class="text-right py-3 px-4">Margin %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $row)
                <tr class="border-t border-white/5">
                    <td class="py-3 px-4 text-white font-medium">{{ $row['month'] }}</td>
                    <td class="py-3 px-4 text-right text-emerald-400">₹{{ number_format($row['revenue'], 0) }}</td>
                    <td class="py-3 px-4 text-right text-red-400">₹{{ number_format($row['expense'], 0) }}</td>
                    <td class="py-3 px-4 text-right {{ $row['profit'] >= 0 ? 'text-yellow-400' : 'text-red-400' }} font-semibold">
                        ₹{{ number_format($row['profit'], 0) }}
                    </td>
                    <td class="py-3 px-4 text-right text-gray-400 text-sm">
                        {{ $row['revenue'] > 0 ? number_format(($row['profit']/$row['revenue'])*100, 1) : '0.0' }}%
                    </td>
                </tr>
                @endforeach
                <tr class="border-t border-yellow-500/30 bg-yellow-500/5">
                    <td class="py-3 px-4 text-yellow-400 font-bold">TOTAL</td>
                    <td class="py-3 px-4 text-right text-emerald-400 font-bold">₹{{ number_format($totalRev, 0) }}</td>
                    <td class="py-3 px-4 text-right text-red-400 font-bold">₹{{ number_format($totalExp, 0) }}</td>
                    <td class="py-3 px-4 text-right font-bold {{ $totalProf >= 0 ? 'text-yellow-400' : 'text-red-400' }}">₹{{ number_format($totalProf, 0) }}</td>
                    <td class="py-3 px-4 text-right text-gray-300 font-semibold">
                        {{ $totalRev > 0 ? number_format(($totalProf/$totalRev)*100, 1) : '0.0' }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
