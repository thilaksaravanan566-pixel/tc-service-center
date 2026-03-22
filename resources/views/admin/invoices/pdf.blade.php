<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 11px; line-height: 1.4; margin: 0; padding: 0; }
        .invoice-box { padding: 40px; margin: auto; }
        .header { margin-bottom: 40px; }
        .company-name { font-size: 20px; font-weight: bold; color: #4F46E5; text-transform: uppercase; margin: 0; }
        .tagline { font-size: 8px; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .invoice-title { font-size: 28px; font-weight: 800; color: #111; text-align: right; margin: 0; font-style: italic; }
        .meta-table { width: 100%; margin-bottom: 40px; }
        .meta-table td { vertical-align: top; }
        .to-box { background: #f9fafb; padding: 15px; border-radius: 8px; width: 60%; }
        .to-title { font-size: 8px; font-weight: bold; color: #999; text-transform: uppercase; margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 3px; }
        .customer-name { font-size: 14px; font-weight: bold; margin: 5px 0 2px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #4F46E5; color: #fff; padding: 10px; text-align: left; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .items-table td { padding: 10px; border-bottom: 1px solid #f3f4f6; }
        .item-row { font-weight: bold; font-size: 11px; }
        .item-desc { font-size: 8px; color: #999; text-transform: uppercase; margin-top: 2px; }
        .totals-table { width: 35%; float: right; }
        .totals-table td { padding: 5px 0; }
        .total-row { font-size: 16px; font-weight: 800; border-top: 1px solid #eee; padding-top: 10px !important; }
        .footer { position: absolute; bottom: 40px; width: 100%; text-align: center; color: #ccc; font-size: 8px; text-transform: uppercase; }
        .gst-text { color: #4F46E5; font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="meta-table">
            <tr>
                <td>
                    <h2 class="company-name">THAMBU COMPUTERS</h2>
                    <p class="tagline">Service & Solutions Expertise</p>
                    <div style="margin-top: 10px; color: #666; font-size: 9px;">
                        123 Tech Hub, Electronics Street<br>
                        Chennai, Tamil Nadu - 600001<br>
                        GSTIN: 33AAACG1234A1Z5
                    </div>
                </td>
                <td style="text-align: right;">
                    <div style="margin-bottom: 10px;"><span style="background: #eef2ff; color: #4338ca; padding: 3px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase;">Tax Invoice</span></div>
                    <h1 class="invoice-title">{{ $invoice->invoice_number }}</h1>
                    <p style="font-weight: bold; color: #999; font-size: 9px; margin-top: 5px;">DATE: <span style="color: #333">{{ $invoice->created_at->format('d M, Y') }}</span></p>
                </td>
            </tr>
        </table>

        <table class="meta-table">
            <tr>
                <td>
                    <div class="to-box">
                        <div class="to-title">Billed To</div>
                        <div class="customer-name">{{ $invoice->customer_name }}</div>
                        <div style="color: #4F46E5; font-weight: bold;">{{ $invoice->phone }}</div>
                        <div style="margin-top: 5px; color: #666">{{ $invoice->address ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="text-align: right;">
                    <br><br>
                    <div style="font-size: 9px; font-weight: bold; color: #999; margin-bottom: 3px;">BILLING TYPE</div>
                    <div style="font-size: 11px; font-weight: 800; color: #111; text-transform: uppercase; font-style: italic;">{{ $invoice->billing_type }}</div>
                    @if($invoice->service_order_id)
                    <div style="margin-top: 10px; font-size: 9px; font-weight: bold; color: #999;">REF: {{ $invoice->serviceOrder->tc_job_id }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 60%">Description</th>
                    <th style="width: 10%; text-align: center;">Qty</th>
                    <th style="width: 30%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="item-row">{{ $item->item_name }}</div>
                        @if($item->description)
                        <div class="item-desc">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ (int)$item->quantity }}</td>
                    <td style="text-align: right; font-weight: bold;">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="width: 100%; border-top: 2px solid #f3f4f6; padding-top: 20px;">
            <div style="width: 50%; float: left; font-size: 8px; color: #999; font-style: italic;">
                <p>Notes: Warranty as per manufacturer terms.<br>In case of any queries, contact our support team.</p>
                @if($invoice->notes)
                <p style="color: #666; font-weight: bold; text-transform: uppercase;">Note: {{ $invoice->notes }}</p>
                @endif
            </div>
            <table class="totals-table">
                <tr>
                    <td style="font-weight: bold; color: #999;">Subtotal</td>
                    <td style="text-align: right; font-weight: bold;">₹{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #999;">GST ({{ (int)$invoice->gst_percentage }}%)</td>
                    <td style="text-align: right; font-weight: bold;" class="gst-text">₹{{ number_format($invoice->gst_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td style="font-size: 14px; font-weight: 800; color: #111; font-style: italic;">GRAND TOTAL</td>
                    <td style="text-align: right; font-size: 14px; font-weight: 800; color: #111; font-style: italic;">₹{{ number_format($invoice->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Digital Signature verified. No physical signature required. Thambu Computers © 2026.
        </div>
    </div>
</body>
</html>
