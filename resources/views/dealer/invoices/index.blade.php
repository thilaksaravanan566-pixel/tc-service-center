@extends('layouts.dealer')

@section('title', 'My Invoices')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">Financial Ledger</h2>
        <p class="text-gray-400 mt-2">Manage your partner billing, tax invoices and payment receipts.</p>
    </div>
    <div class="flex items-center gap-6">
        <div class="text-right">
            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-widest leading-none">Account Balance</p>
            <p class="text-xl font-bold text-white mt-1">₹{{ number_format(Auth::user()->dealer->invoices()->where('status', 'unpaid')->sum('total'), 2) }}</p>
        </div>
        <div class="h-10 w-px bg-white/10 hidden sm:block"></div>
        <button class="bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 p-2.5 rounded-xl hover:bg-indigo-500/20 transition">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </button>
    </div>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-white/5">
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider">Invoice #</th>
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider">Dated</th>
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider">Assoc. Job</th>
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider">Subtotal</th>
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider">Status</th>
                <th class="px-6 py-4 text-[10px] uppercase font-bold text-gray-500 tracking-wider text-right">Total Payable</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($invoices as $inv)
            <tr class="hover:bg-white/5 transition group cursor-pointer">
                <td class="px-6 py-4 text-sm font-bold text-white group-hover:text-indigo-400 transition">{{ $inv->invoice_number }}</td>
                <td class="px-6 py-4 text-xs font-medium text-gray-400">{{ \Carbon\Carbon::parse($inv->created_at)->format('d M, Y') }}</td>
                <td class="px-6 py-4">
                    @if($inv->serviceOrder)
                        <span class="text-xs font-bold text-indigo-400 group-hover:text-indigo-300">Service #{{ $inv->serviceOrder->tc_job_id }}</span>
                    @elseif($inv->dealerOrder)
                        <span class="text-xs font-bold text-emerald-400 group-hover:text-emerald-300 italic">Procurement #{{ $inv->dealerOrder->order_number }}</span>
                    @else
                        <span class="text-xs font-bold text-gray-600">Manual Entry</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-xs font-medium text-gray-400 italic">₹{{ number_format($inv->subtotal, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="badge {{ $inv->status === 'paid' ? 'badge-green' : 'badge-yellow' }}">{{ strtoupper($inv->status) }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="text-sm font-bold text-white">₹{{ number_format($inv->total, 2) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No invoices generated for your partner profile yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-white/5">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
