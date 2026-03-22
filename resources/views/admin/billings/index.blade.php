@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Billing <span class="text-red-600">Section</span></h1>
                <p class="text-sm text-slate-500 font-medium">Manage customer bills and invoices</p>
            </div>
            <a href="{{ route('admin.billings.create') }}" class="luxury-btn py-3 px-6 rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg">+ New Bill</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Customer & Invoice Date</th>
                        <th class="p-5">Description</th>
                        <th class="p-5">Amount</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @if(!empty($billings)) @foreach($billings as $bill)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-5">
                            <h3 class="font-bold text-slate-800">{{ $bill->customer_name }}</h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase mt-1">Date: {{ $bill->invoice_date ? \Carbon\Carbon::parse($bill->invoice_date)->format('M d, Y') : 'N/A' }}</p>
                        </td>
                        <td class="p-5">
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $bill->description }}</p>
                        </td>
                        <td class="p-5">
                            <p class="text-lg font-black text-slate-900">₹{{ number_format($bill->amount, 2) }}</p>
                        </td>
                        <td class="p-5">
                            <span class="inline-block bg-{{ $bill->status == 'Paid' ? 'green' : ($bill->status == 'Cancelled' ? 'red' : 'yellow') }}-100 text-{{ $bill->status == 'Paid' ? 'green' : ($bill->status == 'Cancelled' ? 'red' : 'yellow') }}-600 text-[9px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider">
                                {{ $bill->status }}
                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <a href="{{ route('admin.billings.edit', $bill->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.billings.destroy', $bill->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400 font-bold italic">
                            No bills found.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
