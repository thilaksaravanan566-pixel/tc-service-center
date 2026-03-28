<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0; padding: 0; color: #000; width: 80mm; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .w-full { width: 100%; }
        .border-b { border-bottom: 1px dashed #000; }
        .py-1 { padding: 4px 0; }
        .py-2 { padding: 8px 0; }
        .my-2 { margin: 8px 0; }
        table { border-collapse: collapse; }
        @media print {
            body { margin: 0; padding: 0; }
            @page { margin: 0; size: 80mm auto; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div style="padding: 10px; max-width: 80mm; margin: 0 auto;">
        <!-- Action Bar (Hidden on print) -->
        <div class="no-print text-center border-b pb-2 mb-2">
            <button onclick="window.print()" style="padding: 5px 10px; cursor: pointer; background: #000; color: #fff; border: none; font-weight: bold;">Print Receipt</button>
            <button onclick="window.close()" style="padding: 5px 10px; cursor: pointer; margin-left:10px;">Close</button>
        </div>

        <!-- Header -->
        <h2 class="text-center font-bold" style="margin: 0; font-size: 16px;">{{ $company->name }}</h2>
        <p class="text-center" style="margin: 4px 0;">{{ $company->address }}<br>Ph: {{ $company->phone }}</p>
        @if($company->gst_number)
            <p class="text-center font-bold border-b" style="margin: 4px 0; padding-bottom: 8px;">GSTIN: {{ $company->gst_number }}</p>
        @endif
        
        <!-- Info -->
        <div class="border-b py-2" style="font-size: 11px;">
            <div><span class="font-bold">Bill No:</span> {{ $invoice->invoice_number }}</div>
            <div><span class="font-bold">Date:</span> {{ $invoice->created_at->format('d/m/Y H:i') }}</div>
            <div><span class="font-bold">Cust:</span> {{ $invoice->customer_name }}</div>
            <div><span class="font-bold">Ph:</span> {{ $invoice->phone }}</div>
            @if($invoice->service_order_id)
            <div><span class="font-bold">Job:</span> {{ $invoice->serviceOrder->ticket_no ?? '' }}</div>
            @endif
        </div>
        
        <!-- Items -->
        <table class="w-full mt-2" style="font-size: 11px; margin-top: 8px;">
            <thead>
                <tr class="border-b py-1 font-bold">
                    <td class="text-left py-1 w-full" colspan="3">Item Description</td>
                </tr>
                <tr class="border-b font-bold">
                    <td class="text-left py-1" style="width: 30%;">Qty</td>
                    <td class="text-right py-1" style="width: 35%;">Rate</td>
                    <td class="text-right py-1" style="width: 35%;">Amt</td>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td colspan="3" class="text-left py-1 pb-0">
                        {{ \Illuminate\Support\Str::limit($item->item_name, 35) }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left py-1">{{ $item->quantity }}</td>
                    <td class="text-right py-1">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right py-1 font-bold">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="border-b py-2 text-right mt-2" style="font-size: 12px; border-top: 1px dashed #000;">
            <div style="display: flex; justify-content: space-between;">
                <span>Subtotal:</span> <span>{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            
            @if($settings->show_tax_breakup)
                @if($invoice->cgst_amount > 0)
                <div style="display: flex; justify-content: space-between; font-size: 10px; margin-top: 2px;">
                    <span>CGST:</span> <span>{{ number_format($invoice->cgst_amount, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 10px;">
                    <span>SGST:</span> <span>{{ number_format($invoice->sgst_amount, 2) }}</span>
                </div>
                @endif
                @if($invoice->igst_amount > 0)
                <div style="display: flex; justify-content: space-between; font-size: 10px; margin-top: 2px;">
                    <span>IGST:</span> <span>{{ number_format($invoice->igst_amount, 2) }}</span>
                </div>
                @endif
            @else
                @if($invoice->gst_amount > 0)
                <div style="display: flex; justify-content: space-between; font-size: 10px; margin-top: 2px;">
                    <span>Tax:</span> <span>{{ number_format($invoice->gst_amount, 2) }}</span>
                </div>
                @endif
            @endif

            @if($invoice->discount > 0)
            <div style="display: flex; justify-content: space-between; margin-top: 2px;">
                <span>Discount:</span> <span>-{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            
            <div class="font-bold py-1" style="display: flex; justify-content: space-between; border-top: 1px dashed #000; margin-top: 4px; padding-top: 6px; font-size: 14px;">
                <span>TOTAL:</span> <span>Rs. {{ number_format($invoice->total, 2) }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center py-2" style="font-size: 11px;">
            <p style="margin: 4px 0;" class="font-bold">{{ $settings->footer_message }}</p>
            <p style="margin: 4px 0 0 0; font-size: 9px;">{!! nl2br(e($settings->terms_conditions)) !!}</p>
        </div>
        
        <div class="text-center py-2 border-t" style="font-size: 9px; margin-top: 10px;">
            -- System Generated --
        </div>
    </div>

    <!-- Auto-Print Script -->
    <script>
        if (new URLSearchParams(window.location.search).has('auto_print')) {
            setTimeout(() => { window.print(); }, 800);
        }
    </script>
</body>
</html>
