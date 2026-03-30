<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->tc_job_id }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
            font-size: 14px;
        }
        .invoice-container {
            padding: 40px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            font-size: 36px;
            font-weight: 900;
            color: #111;
            letter-spacing: -1px;
        }
        .logo span {
            color: #d4af37;
        }
        .company-details {
            text-align: right;
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            color: #d4af37;
            margin-bottom: 20px;
        }
        .meta-container {
            width: 100%;
            margin-bottom: 40px;
        }
        .meta-container td {
            vertical-align: top;
        }
        .customer-info h3, .invoice-info h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #111;
            text-transform: uppercase;
        }
        .customer-info p, .invoice-info p {
            margin: 0;
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }
        .invoice-info {
            text-align: right;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items th {
            background-color: #f8f8f8;
            color: #111;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            font-size: 12px;
            text-transform: uppercase;
        }
        table.items td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            color: #444;
        }
        table.items .amount {
            text-align: right;
        }
        .summary-container {
            width: 100%;
        }
        .summary-box {
            float: right;
            width: 350px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 12px;
            color: #444;
        }
        .summary-table .label {
            text-align: right;
            font-weight: bold;
        }
        .summary-table .value {
            text-align: right;
        }
        .summary-table .total-row td {
            border-top: 2px solid #d4af37;
            border-bottom: 2px solid #d4af37;
            font-weight: bold;
            font-size: 18px;
            color: #111;
            padding: 12px;
        }
        .footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e6f4ea;
            color: #1e8e3e;
            font-weight: bold;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .status-unpaid {
            background-color: #fce8e6;
            color: #d93025;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        
        <!-- Header Section -->
        <table class="header">
            <tr>
                <td style="width: 50%;">
                    <div class="logo">TC <span>Service Center</span></div>
                    <div style="font-size: 11px; color:#777; margin-top: 5px; font-weight: bold; letter-spacing: 1px;">PREMIUM DEVICE CARE</div>
                </td>
                <td class="company-details">
                    <strong>TC Service Center Pvt. Ltd.</strong><br>
                    123 Luxury Tech Boulevard, Silicon Valley<br>
                    Bengaluru, Karnataka - 560001<br>
                    GSTIN: 29AABBCC1234D1Z5<br>
                    {{ \App\Models\CompanyProfile::first()->email ?? 'support@tcservice.com' }} | {{ \App\Models\CompanyProfile::first()->phone ?? '+91 98765 43210' }}
                </td>
            </tr>
        </table>

        <div class="invoice-title">Tax Invoice</div>

        <!-- Meta Details -->
        <table class="meta-container">
            <tr>
                <td class="customer-info" style="width: 50%;">
                    <h3>Billed To:</h3>
                    <p>
                        <strong>{{ $order->customer->name }}</strong><br>
                        {{ $order->customer->address ?? 'No Address Provided' }}<br>
                        Email: {{ $order->customer->email }}<br>
                        Phone: {{ $order->customer->mobile }}
                    </p>
                </td>
                <td class="invoice-info">
                    <h3>Invoice Details:</h3>
                    <p>
                        <strong>Invoice Number:</strong> INV-{{ date('Y') }}-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                        <strong>Date:</strong> {{ date('F d, Y') }}<br>
                        <strong>Job ID:</strong> {{ $order->tc_job_id }}<br>
                        <strong>Payment Status:</strong> 
                        @if($order->is_paid)
                            <span class="status-badge" style="color: green;">PAID</span>
                        @else
                            <span class="status-badge status-unpaid" style="color: red;">UNPAID</span>
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <!-- Itemized Table -->
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 55%;">Description of Service / Product</th>
                    <th class="amount" style="width: 10%;">Qty</th>
                    <th class="amount" style="width: 15%;">Unit Price</th>
                    <th class="amount" style="width: 15%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Repair Service ({{ $order->device->brand }} {{ $order->device->model }})</strong><br>
                        <span style="font-size: 11px; color: #777;">Fault: {{ str($order->fault_details)->limit(50) }}</span><br>
                        <span style="font-size: 11px; color: #777;">Technician Notes: {{ str($order->engineer_comment)->limit(50) ?? 'Standard Service' }}</span>
                    </td>
                    <td class="amount">1</td>
                    <td class="amount">₹{{ number_format($baseAmount, 2) }}</td>
                    <td class="amount">₹{{ number_format($baseAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Summary -->
        <div class="summary-container">
            <div class="summary-box">
                <table class="summary-table">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="value">₹{{ number_format($baseAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">CGST (9%):</td>
                        <td class="value">₹{{ number_format($gstAmount / 2, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">SGST (9%):</td>
                        <td class="value">₹{{ number_format($gstAmount / 2, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">Grand Total:</td>
                        <td class="value">₹{{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Footer Notes -->
        <div style="margin-top: 50px;">
            <p style="font-size: 12px; font-weight: bold; margin-bottom: 5px;">Terms & Conditions</p>
            <p style="font-size: 10px; color: #666; line-height: 1.4; margin: 0;">
                1. Payments must be cleared upon device pickup or delivery.<br>
                2. Goods once sold/repaired will not be taken back without original invoice.<br>
                3. Warranty applicable as per manufacturer or TC Service Center policy (typically 90 days for parts).<br>
                4. All disputes subject to local jurisdiction.<br>
            </p>
            <p style="text-align: right; margin-top: 40px; font-weight: bold; color: #111;">
                Authorized Signatory
            </p>
            <div style="text-align: right; margin-top: 5px;">
                _______________________
            </div>
        </div>

    </div>

    <div class="footer">
        Computer Generated Invoice &bull; No Signature Required &bull; TC Service Center System
    </div>

</body>
</html>
