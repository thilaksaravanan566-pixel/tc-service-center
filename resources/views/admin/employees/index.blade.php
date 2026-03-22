@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto animate-fade-in-up">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Staff & <span class="text-blue-600">Payroll</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage employees, salaries, and biometric mapping</p>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="bg-slate-900 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-slate-200 transition-all text-[10px] uppercase tracking-widest">+ Add Employee</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[10px] uppercase font-black tracking-[0.2em]">
                        <th class="p-5">Employee Info</th>
                        <th class="p-5">Base Salary</th>
                        <th class="p-5">ZKTeco ID</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($employees)) @foreach($employees as $emp)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-600 font-black flex items-center justify-center text-xl">
                                    {{ substr($emp->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-wider">{{ $emp->name }}</h3>
                                    <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest bg-blue-50 inline-block px-2 py-0.5 rounded mt-1">{{ $emp->role }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $emp->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5 font-black text-slate-700 text-lg">
                            ₹{{ number_format($emp->salary, 2) }}
                            <span class="block text-[9px] text-slate-400 tracking-widest uppercase">Monthly</span>
                        </td>
                        <td class="p-5">
                            @if($emp->biometric_id)
                                <span class="bg-green-100 text-green-700 font-mono px-3 py-1 rounded-md text-xs font-black shadow-sm flex items-center inline-flex gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    ID: {{ $emp->biometric_id }}
                                </span>
                            @else
                                <span class="bg-slate-100 text-slate-400 font-mono px-3 py-1 rounded-md text-[10px] font-black uppercase shadow-sm">
                                    Not Mapped
                                </span>
                            @endif
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.employees.edit', $emp->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove employee permanently?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-500 hover:text-white text-red-500 px-3 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-colors shadow-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No employees loaded.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
