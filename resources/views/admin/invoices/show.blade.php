@extends('layouts.admin')

@section('title', 'Invoice Details — ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto py-8">
    
    {{-- ACTIONS --}}
    <div class="flex items-center justify-between mb-8 no-print">
        <a href="{{ route('admin.invoices.index') }}" class="text-gray-400 hover:text-white transition-all text-sm font-bold flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Billing
        </a>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-white font-black text-xs uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/></svg>
                Print Invoice
            </button>
            <a href="{{ route('admin.invoices.download', $invoice->id) }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                PDF Download
            </a>
        </div>
    </div>

    {{-- INVOICE CONTENT --}}
    <div id="invoice-paper" class="bg-white text-slate-900 rounded-[2rem] shadow-2xl overflow-hidden p-12 relative border border-slate-100 min-h-[1000px]">
        
        {{-- WATERMARK / LOGO BACKGROUND --}}
        <div class="absolute top-0 right-0 p-12 opacity-[0.03] pointer-events-none">
            <h1 class="text-8xl font-black italic">INVOICE</h1>
        </div>

        {{-- HEADER: COMPANY vs INVOICE INFO --}}
        <div class="flex justify-between items-start mb-16 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black tracking-tighter text-slate-900 leading-none">THAMBU <span class="text-indigo-600">COMPUTERS</span></h2>
                        <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 mt-1 italic">Service & Solutions Expertise</p>
                    </div>
                </div>
                <div class="text-xs text-slate-500 font-medium leading-relaxed space-y-0.5">
                    <p>123 Tech Hub, Electronics Street</p>
                    <p>Chennai, Tamil Nadu - 600001</p>
                    <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> +91 98765 43210</p>
                    <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> GSTIN: 33AAACG1234A1Z5</p>
                </div>
            </div>

            <div class="text-right">
                <div class="mb-6">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-100">Tax Invoice</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter italic">{{ $invoice->invoice_number }}</h1>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-2">Date: <span class="text-slate-900">{{ $invoice->created_at->format('d M, Y') }}</span></p>
            </div>
        </div>

        {{-- CUSTOMER DETAILS --}}
        <div class="grid grid-cols-2 gap-12 mb-16">
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 border-b border-slate-200 pb-2">Billed To</h3>
                <h4 class="text-lg font-black text-slate-900 mb-1">{{ $invoice->customer_name }}</h4>
                <p class="text-indigo-600 font-bold text-xs mb-3">{{ $invoice->phone }}</p>
                <p class="text-xs text-slate-500 leading-relaxed max-w-[200px]">{{ $invoice->address ?? 'No address provided' }}</p>
            </div>
            <div class="flex flex-col justify-end text-right">
                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Order Origin</p>
                    <span class="inline-block px-4 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest italic shadow-lg shadow-slate-200">{{ $invoice->billing_type ?? 'Walk-in' }}</span>
                </div>
                @if($invoice->serviceOrder)
                <div class="mt-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Job Reference</p>
                    <p class="text-sm font-bold text-slate-900 font-mono">{{ $invoice->serviceOrder->tc_job_id }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="mb-16">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-indigo-600 rounded-xl">
                        <th class="py-4 px-6 text-[10px] font-black text-white uppercase tracking-widest rounded-l-2xl">Description of Services / Spares</th>
                        <th class="py-4 px-4 text-[10px] font-black text-white uppercase tracking-widest text-center italic">Qty</th>
                        <th class="py-4 px-6 text-[10px] font-black text-white uppercase tracking-widest text-right rounded-r-2xl">Price (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="py-6 px-4">
                            <p class="font-black text-slate-800 text-sm italic">{{ $item->item_name }}</p>
                            @if($item->description)
                            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold">{{ $item->description }}</p>
                            @endif
                        </td>
                        <td class="py-6 px-4 text-center font-black text-slate-900 italic">{{ number_format($item->quantity, 0) }}</td>
                        <td class="py-6 px-6 text-right font-black text-slate-900 italic">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SUMMARY & SIGNATURE --}}
        <div class="flex justify-between items-start">
            <div class="w-1/2">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 border-dashed">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Payment Instruction</h3>
                    <div class="text-[11px] text-slate-500 italic space-y-1">
                        <p>1. Please pay within 7 days from the date of invoice.</p>
                        <p>2. Warranty on spares as per manufacturer policy.</p>
                        @if($invoice->notes)
                        <p class="text-slate-900 font-bold mt-3 border-t border-slate-200 pt-2 NOT-ITALIC uppercase tracking-tighter">{{ $invoice->notes }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-12">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(request()->fullUrl()) }}" class="w-16 h-16 opacity-10">
                </div>
            </div>

            <div class="w-1/3 space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-black uppercase tracking-widest">Subtotal</span>
                    <span class="font-black text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-black uppercase tracking-widest">GST ({{ number_format($invoice->gst_percentage, 0) }}%)</span>
                    <span class="font-black text-slate-900 text-indigo-600">₹{{ number_format($invoice->gst_amount, 2) }}</span>
                </div>
                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-slate-400 font-black uppercase tracking-widest text-sm italic">Grand Total</span>
                    <span class="text-2xl font-black text-slate-900 italic tracking-tighter">₹{{ number_format($invoice->total, 2) }}</span>
                </div>
                
                <div class="mt-16 pt-24 text-center border-t border-slate-50">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">Authorized Signature</p>
                    <p class="text-[10px] font-black text-indigo-600 italic mt-1">Thambu Computers Service Team</p>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-20 border-t border-slate-50 pt-8 text-center">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300 italic">This is a system generated invoice. Computer-linked digital signature. NO physical signature required.</p>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    #invoice-paper { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important; }
    main { padding: 0 !important; margin: 0 !important; }
}
</style>
@endsection
