<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: {{ $settings->font_size }}; color: #333; margin: 0; padding: 20px; }
        .theme-text { color: {{ $settings->theme_color }}; }
        .theme-bg { background-color: {{ $settings->theme_color }}; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        @media print {
            body { margin: 0; padding: 0; }
            @page { margin: 10mm; size: A5; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">Print A5 Document</button>
    </div>

    <div style="max-width: 148mm; margin: 0 auto; border: 1px solid #ccc; padding: 15px; background: #fff;">
        <table style="border: none; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 60%; vertical-align: top;">
                    @if($company->logo)
                        <img src="{{ asset($company->logo) }}" style="height: 40px; margin-bottom: 5px;" alt="Logo">
                    @endif
                    <h3 style="margin: 0;" class="theme-text">{{ $company->name }}</h3>
                    <div style="font-size: 10px;">{{ $company->address }}<br>Ph: {{ $company->phone }}</div>
                    @if($company->gst_number)<div style="font-weight: bold; font-size: 10px;">GSTIN: {{ $company->gst_number }}</div>@endif
                </td>
                <td style="border: none; text-align: right; vertical-align: top;">
                    <h2 style="margin: 0; font-size: 16px;" class="theme-text">{{ $settings->header_text ?: 'INVOICE' }}</h2>
                    <div style="font-weight: bold; font-size: 12px;">#{{ $invoice->invoice_number }}</div>
                    <div style="font-size: 10px;">Date: {{ $invoice->created_at->format('d M y') }}</div>
                </td>
            </tr>
        </table>

        <div style="border: 1px solid #ddd; padding: 5px; padding-left: 8px; margin-top: 10px; font-size: 11px;">
            <b style="color: #666;">Billed To:</b> {{ $invoice->customer_name }} | {{ $invoice->phone }}<br>
            @if($invoice->device_name)
            <b style="color: #666;">Device details:</b> {{ $invoice->device_name }}
            @endif
        </div>

        <table>
            <tr style="font-size: 10px;">
                <th style="width: 5%;">#</th>
                <th>Item Details</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 18%;" class="text-right">Rate</th>
                <th style="width: 20%;" class="text-right">Total</th>
            </tr>
            @foreach($invoice->items as $index => $item)
            <tr style="font-size: 11px;">
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price, 2) }}</td>
                <td class="text-right font-bold">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <table style="border: none; margin-top: 5px;">
            <tr style="border: none;">
                <td style="border: none; width: 50%; vertical-align: bottom; font-size: 10px; color: #555;">
                    {!! nl2br(e($settings->terms_conditions)) !!}
                </td>
                <td style="border: none; width: 50%; padding: 0;">
                    <table style="margin-top: 0;">
                        <tr style="font-size: 11px;">
                            <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">Subtotal</td>
                            <td class="text-right" style="border: none; border-bottom: 1px solid #ddd; width: 40%;">{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount > 0)
                        <tr style="font-size: 11px; color: red;">
                            <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">Discount</td>
                            <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">-{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        @endif
                        @if($settings->show_tax_breakup)
                            @if($invoice->cgst_amount > 0)
                            <tr style="font-size: 11px;">
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">CGST</td>
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">{{ number_format($invoice->cgst_amount, 2) }}</td>
                            </tr>
                            <tr style="font-size: 11px;">
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">SGST</td>
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">{{ number_format($invoice->sgst_amount, 2) }}</td>
                            </tr>
                            @endif
                        @else
                            @if($invoice->gst_amount > 0)
                            <tr style="font-size: 11px;">
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">Tax</td>
                                <td class="text-right" style="border: none; border-bottom: 1px solid #ddd;">{{ number_format($invoice->gst_amount, 2) }}</td>
                            </tr>
                            @endif
                        @endif
                        <tr class="theme-bg" style="font-size: 13px;">
                            <td class="text-right font-bold" style="border: none;">Total</td>
                            <td class="text-right font-bold" style="border: none;">{{ number_format($invoice->total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <div style="text-align: center; font-size: 9px; margin-top: 15px; font-weight: bold;" class="theme-text">
            {{ $settings->footer_message }}
        </div>
    </div>
    
    <script>
        if (new URLSearchParams(window.location.search).has('auto_print')) {
            setTimeout(() => { window.print(); }, 500);
        }
    </script>
</body>
</html>
