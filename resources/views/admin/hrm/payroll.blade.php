@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">💰 HRM Payroll</h1>
            <p class="text-gray-400 text-sm mt-1">Employee salary management and payroll processing</p>
        </div>
        <form action="{{ route('admin.hrm.payroll.generate') }}" method="POST" class="flex gap-3 items-center">
            @csrf
            <select name="month" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m)->format('F') }}</option>
                @endfor
            </select>
            <select name="year" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                @for($y = now()->year; $y >= 2024; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg font-medium transition-all">
                ⚙️ Generate Payroll
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-white">{{ $stats['employee_count'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Employees</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-yellow-400">₹{{ number_format($stats['total_payroll'], 0) }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Payroll</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-emerald-400">{{ $stats['paid_count'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Paid</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-red-400">{{ $stats['pending_count'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Pending</p>
        </div>
    </div>

    {{-- Payroll Table --}}
    <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-3 px-4">Employee</th>
                    <th class="text-left py-3 px-4">Role</th>
                    <th class="text-right py-3 px-4">Base Salary</th>
                    <th class="text-right py-3 px-4">Bonus</th>
                    <th class="text-right py-3 px-4">Deductions</th>
                    <th class="text-right py-3 px-4">Net Salary</th>
                    <th class="text-center py-3 px-4">Status</th>
                    <th class="text-center py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($employees)) @foreach($employees as $emp)
                @php $record = $salaryRecords[$emp->id] ?? null; @endphp
                <tr class="border-t border-white/5">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->name) }}&background=1e1e2e&color=eab308&size=36" class="w-9 h-9 rounded-full">
                            <div>
                                <p class="text-white font-medium text-sm">{{ $emp->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $emp->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs bg-blue-500/20 text-blue-400 capitalize">{{ $emp->role }}</span>
                    </td>
                    <td class="py-3 px-4 text-right text-gray-300 text-sm">₹{{ number_format($emp->salary ?? 0, 0) }}</td>
                    <td class="py-3 px-4 text-right text-emerald-400 text-sm">{{ $record ? '+₹'.number_format($record->bonus, 0) : '-' }}</td>
                    <td class="py-3 px-4 text-right text-red-400 text-sm">{{ $record ? '-₹'.number_format($record->deductions, 0) : '-' }}</td>
                    <td class="py-3 px-4 text-right text-yellow-400 font-bold">
                        {{ $record ? '₹'.number_format($record->net_salary, 0) : '₹'.number_format($emp->salary ?? 0, 0) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($record)
                            @if($record->status === 'paid')
                                <span class="px-2 py-1 rounded text-xs bg-emerald-500/20 text-emerald-400">✅ Paid</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs bg-yellow-500/20 text-yellow-400">⏳ Pending</span>
                            @endif
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-500/20 text-gray-400">Not Generated</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($record && $record->status !== 'paid')
                        <form action="{{ route('admin.hrm.payroll.pay', $record->id) }}" method="POST" onsubmit="return confirm('Mark as PAID?')">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white text-xs rounded-lg transition-all">
                                Mark Paid
                            </button>
                        </form>
                        @elseif(!$record)
                            <span class="text-gray-600 text-xs">Generate first</span>
                        @else
                            <span class="text-emerald-500 text-xs">✓ Paid</span>
                        @endif
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="8" class="py-12 text-center text-gray-500">No employees found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Filter by month/year --}}
    <div class="flex justify-center">
        <form method="GET" class="flex gap-3 items-center">
            <select name="month" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m)->format('F') }}</option>
                @endfor
            </select>
            <select name="year" class="bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 text-sm">
                @for($y = now()->year; $y >= 2024; $y--)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm rounded-lg transition-all">
                View Month
            </button>
        </form>
    </div>

</div>
@endsection
