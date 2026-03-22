@extends('layouts.admin')

@section('title', 'Billing & Revenue Center')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-8 fade-up">
            <div>
                <h1 class="text-3xl font-black text-white italic uppercase tracking-tighter">Billing <span class="text-indigo-500">& Revenue</span></h1>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1 italic">Financial settlement for all service operations</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.invoices.create') }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Manual Invoice
                </a>
            </div>
        </div>

        {{-- REVENUE STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 fade-up stagger-1">
            <div class="card p-6 border-indigo-500/10 bg-indigo-600/5">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Paid Revenue</p>
                <h3 class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($stats['total_revenue'] ?? 0, 0) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-[10px] text-emerald-500 font-bold uppercase">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5V3a1 1 0 112 0v5a1 1 0 01-1 1h-5zM11 13a1 1 0 110 2h-5V17a1 1 0 11-2 0v-5a1 1 0 011-1h5z" clip-rule="evenodd"/></svg>
                    Settled
                </div>
            </div>
            <div class="card p-6 border-rose-500/10">
                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Pending Settlement</p>
                <h3 class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($stats['pending_payment'] ?? 0, 0) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-[10px] text-rose-500 font-bold uppercase">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    Credit / Unpaid
                </div>
            </div>
            <div class="card p-6 border-amber-500/10 bg-amber-500/5">
                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">B2B Dealer Split</p>
                <h3 class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($stats['dealer_revenue'] ?? 0, 0) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-[10px] text-amber-500 font-bold uppercase tracking-tighter">
                    {{ number_format(($stats['total_revenue'] > 0 ? ($stats['dealer_revenue'] / $stats['total_revenue'] * 100) : 0), 0) }}% contribution
                </div>
            </div>
            <div class="card p-6 border-sky-500/10 bg-sky-500/5">
                <p class="text-[10px] font-black text-sky-400 uppercase tracking-widest mb-1">Retail Revenue</p>
                <h3 class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($stats['retail_revenue'] ?? 0, 0) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-[10px] text-sky-400 font-bold uppercase tracking-tighter">
                    {{ number_format(($stats['total_revenue'] > 0 ? ($stats['retail_revenue'] / $stats['total_revenue'] * 100) : 0), 0) }}% contribution
                </div>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="card overflow-hidden border-white/5 fade-up stagger-2">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Global Invoicing Log</h3>
                <div class="flex gap-2">
                    <input type="text" placeholder="Search invoices..." class="bg-white/5 border border-white/10 rounded-lg px-4 py-1.5 text-xs text-indigo-400 font-bold placeholder-gray-700 outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-white/[0.02]">
                            <th class="p-5">Identity / Date</th>
                            <th class="p-5">Billing Category</th>
                            <th class="p-5">Customer / Ref</th>
                            <th class="p-5 text-right">Settlement (₹)</th>
                            <th class="p-5 text-center">Status</th>
                            <th class="p-5 text-center">Method</th>
                            <th class="p-5 text-right rounded-tr-xl">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse ($invoices as $invoice)
                        <tr class="hover:bg-indigo-600/[0.02] transition-all group">
                            <td class="p-5">
                                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-sm font-black text-white italic hover:text-indigo-400 block tracking-tight">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <span class="text-[10px] text-gray-500 font-bold uppercase mt-1">{{ $invoice->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="p-5">
                                <span class="inline-block px-3 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $invoice->billing_type === 'dealer' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-sky-500/10 text-sky-400' }}">
                                    {{ $invoice->billing_type ?? 'RETAIL' }}
                                </span>
                            </td>
                            <td class="p-5">
                                <p class="text-sm font-black text-gray-300 italic">{{ $invoice->customer_name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($invoice->service_order_id)
                                        <span class="text-[9px] font-black text-gray-600 uppercase tracking-tighter">Job #{{ $invoice->serviceOrder->tc_job_id ?? $invoice->service_order_id }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-5 text-right">
                                <p class="text-sm font-black text-white italic tracking-tighter">₹{{ number_format($invoice->total, 2) }}</p>
                                <p class="text-[9px] text-gray-600 font-bold uppercase">Incl. GST</p>
                            </td>
                            <td class="p-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest italic
                                    {{ $invoice->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-500' : ($invoice->payment_status === 'partial' ? 'bg-amber-500/10 text-amber-500' : 'bg-rose-500/10 text-rose-500') }}">
                                    {{ $invoice->payment_status }}
                                </span>
                            </td>
                            <td class="p-5 text-center">
                                @if($invoice->payment_method)
                                    <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest italic opacity-60">{{ str_replace('_', ' ', $invoice->payment_method) }}</span>
                                @else
                                    <span class="text-[10px] text-gray-700 font-black uppercase tracking-widest italic">--</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="p-2 bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-white transition-all shadow-sm shadow-indigo-500/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="p-2 bg-indigo-500/10 border border-indigo-500/20 rounded-lg text-indigo-400 hover:bg-indigo-500 hover:text-white transition-all shadow-sm shadow-indigo-500/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-20 text-center">
                                <div class="text-gray-600 font-black uppercase tracking-widest italic text-xs mb-4">No billing history found</div>
                                <a href="{{ route('admin.invoices.create') }}" class="text-indigo-500 font-black hover:underline uppercase text-[10px] tracking-widest underline decoration-indigo-500/30">Generate your first invoice</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.01]">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 20px;
    }
</style>
@endsection
