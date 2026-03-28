<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: {{ $settings->font_size }}; color: #1f2937; }
        .theme-text { color: {{ $settings->theme_color }}; }
        .theme-bg { background-color: {{ $settings->theme_color }}; color: white; }
        .theme-border { border-color: {{ $settings->theme_color }}; }
        @media print {
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                background: white !important;
                padding: 0 !important;
            }
            .no-print { display: none !important; }
            @page { margin: 10mm; size: A4; }
            .print-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-gray-50 py-10 px-4">

    <!-- Top floating Action Bar -->
    <div class="max-w-4xl mx-auto mb-6 bg-white p-4 rounded-xl shadow-lg border border-gray-100 flex justify-between items-center no-print">
        <div class="flex items-center gap-3">
            <span class="bg-emerald-100 text-emerald-700 font-bold px-3 py-1 rounded-full text-xs">A4 Format</span>
            <span class="text-gray-500 text-sm font-medium">Invoice #{{ $invoice->invoice_number }}</span>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <button onclick="window.close()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg transition-all">
                Close
            </button>
        </div>
    </div>

    <!-- Printable Invoice Page -->
    <div class="print-container max-w-4xl mx-auto bg-white p-12 shadow-2xl relative">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b-2 pb-6 theme-border">
            <div class="max-w-[60%]">
                @if($company->logo)
                    <img src="{{ asset($company->logo) }}" class="h-16 w-auto mb-4" alt="Company Logo">
                @else
                    <h1 class="font-black text-3xl theme-text mb-2">{{ $company->name }}</h1>
                @endif
                <div class="text-gray-600 leading-relaxed font-medium">
                    <p>{{ $company->address }}</p>
                    <p class="mt-1">P: {{ $company->phone }} | E: {{ $company->email }}</p>
                    @if($company->gst_number)<p class="mt-1 font-bold theme-text">GSTIN: {{ $company->gst_number }}</p>@endif
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-black uppercase theme-text mb-2">{{ $settings->header_text ?: 'INVOICE' }}</h2>
                <p class="text-lg font-bold text-gray-800 tracking-wider">#{{ $invoice->invoice_number }}</p>
                <p class="text-sm text-gray-500 font-medium mt-1">Date: {{ $invoice->created_at->format('d M Y') }}</p>
                <div class="mt-3 inline-block {{ $invoice->payment_status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded font-bold uppercase tracking-wider text-xs">
                    Status: {{ $invoice->payment_status }}
                </div>
            </div>
        </div>

        <!-- Billing Info -->
        <div class="grid grid-cols-2 gap-10 mb-8 w-full border border-gray-200 rounded-lg overflow-hidden">
            <div class="p-4 bg-gray-50 border-r border-gray-200">
                <h3 class="text-xs font-bold uppercase text-gray-500 mb-2">Billed To</h3>
                <p class="font-bold text-lg text-gray-800 mb-1">{{ $invoice->customer_name }}</p>
                <p class="text-gray-600 mb-1">P: {{ $invoice->phone }}</p>
                <p class="text-gray-600">{{ $invoice->address }}</p>
                @if($invoice->customer && $invoice->customer->customer_gst)
                    <p class="text-gray-600 font-medium mt-1">GSTIN: {{ $invoice->customer->customer_gst }}</p>
                @endif
            </div>
            <div class="p-4 bg-white">
                <h3 class="text-xs font-bold uppercase text-gray-500 mb-2">Job Details</h3>
                <table class="w-full text-sm">
                    <tr><td class="py-1 text-gray-600 w-24">Device:</td><td class="py-1 font-medium">{{ $invoice->device_name }}</td></tr>
                    @if($invoice->service_order_id)
                    <tr><td class="py-1 text-gray-600">Job ID:</td><td class="py-1 font-medium">{{ $invoice->serviceOrder->ticket_no ?? '' }}</td></tr>
                    <tr><td class="py-1 text-gray-600">Technician:</td><td class="py-1 font-medium">{{ $invoice->technician }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="theme-bg uppercase text-xs tracking-wider">
                        <th class="py-3 px-4 rounded-tl-lg w-10">#</th>
                        <th class="py-3 px-4">Item & Description</th>
                        @if($settings->show_hsn_sac)<th class="py-3 px-4 w-20">HSN/SAC</th>@endif
                        <th class="py-3 px-4 text-center w-20">Qty</th>
                        <th class="py-3 px-4 text-right w-28">Rate (₹)</th>
                        @if($settings->show_discount)<th class="py-3 px-4 text-right w-24">Dis.</th>@endif
                        <th class="py-3 px-4 text-right rounded-tr-lg w-32">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($invoice->items as $index => $item)
                    <tr class="border-b border-gray-200 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="py-4 px-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="py-4 px-4">
                            <p class="font-bold text-gray-800">{{ $item->item_name }}</p>
                            @if($item->description)<p class="text-sm text-gray-500 mt-1">{{ $item->description }}</p>@endif
                        </td>
                        @if($settings->show_hsn_sac)<td class="py-4 px-4 text-sm">{{ $item->hsn_sac ?: '-' }}</td>@endif
                        <td class="py-4 px-4 text-center font-medium">{{ $item->quantity }}</td>
                        <td class="py-4 px-4 text-right">{{ number_format($item->price, 2) }}</td>
                        @if($settings->show_discount)<td class="py-4 px-4 text-right text-red-500">{{ $item->discount_amount > 0 ? '-'.number_format($item->discount_amount, 2) : '-' }}</td>@endif
                        <td class="py-4 px-4 text-right font-bold">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculations -->
        <div class="flex justify-between items-end mb-12">
            <!-- Left Info / QR Code space -->
            <div class="w-1/2 pr-8">
                <p class="text-sm font-bold theme-text mb-2">{{ $settings->footer_message }}</p>
                <div class="text-xs text-gray-500 leading-relaxed max-w-sm">
                    <strong>Terms & Conditions:</strong><br/>
                    {!! nl2br(e($settings->terms_conditions)) !!}
                </div>
            </div>
            
            <!-- Right Totals -->
            <div class="w-2/5 rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 p-4 border-b border-gray-200">
                    <div class="flex justify-between mb-2"><span class="text-gray-600 font-medium">Subtotal</span><span class="font-bold">₹{{ number_format($invoice->subtotal, 2) }}</span></div>
                    
                    @if($invoice->discount > 0)
                        <div class="flex justify-between mb-2 text-red-500"><span class="font-medium">Overall Discount</span><span class="font-bold">-₹{{ number_format($invoice->discount, 2) }}</span></div>
                    @endif

                    @if($settings->show_tax_breakup)
                        @if($invoice->cgst_amount > 0)
                            <div class="flex justify-between mb-2"><span class="text-gray-600 font-medium">CGST ({{ $invoice->gst_percentage/2 }}%)</span><span class="font-bold">₹{{ number_format($invoice->cgst_amount, 2) }}</span></div>
                            <div class="flex justify-between mb-2"><span class="text-gray-600 font-medium">SGST ({{ $invoice->gst_percentage/2 }}%)</span><span class="font-bold">₹{{ number_format($invoice->sgst_amount, 2) }}</span></div>
                        @endif
                        @if($invoice->igst_amount > 0)
                            <div class="flex justify-between mb-2"><span class="text-gray-600 font-medium">IGST ({{ $invoice->gst_percentage }}%)</span><span class="font-bold">₹{{ number_format($invoice->igst_amount, 2) }}</span></div>
                        @endif
                    @else
                        @if($invoice->gst_amount > 0)
                            <div class="flex justify-between mb-2"><span class="text-gray-600 font-medium">Tax Area ({{ $invoice->gst_percentage }}%)</span><span class="font-bold">₹{{ number_format($invoice->gst_amount, 2) }}</span></div>
                        @endif
                    @endif

                    @if($invoice->round_off != 0)
                        <div class="flex justify-between mb-0"><span class="text-gray-600 font-medium">Round Off</span><span class="font-bold">₹{{ number_format($invoice->round_off, 2) }}</span></div>
                    @endif
                </div>
                <div class="p-4 theme-bg">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold">Total Amount</span>
                        <span class="text-2xl font-black">₹{{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Signature -->
        <div class="mt-auto border-t-2 theme-border pt-6 flex justify-between items-end">
            <div class="text-sm font-medium text-gray-500">
                Powered by TC Service Center ERP System
            </div>
            
            @if($settings->show_signature)
            <div class="text-center w-48">
                <div class="border-b border-gray-400 h-10 mb-2"></div>
                <span class="text-xs font-bold uppercase text-gray-600 tracking-widest">Authorized Signatory</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Auto-Print Script -->
    <script>
        if (new URLSearchParams(window.location.search).has('auto_print')) {
            setTimeout(() => { window.print(); }, 500);
        }
    </script>
</body>
</html>
