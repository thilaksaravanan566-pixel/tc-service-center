<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registry {{ $order->tc_job_id }} – Fiscal Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #000; 
            color: #fff; 
            padding: 40px;
            font-size: 14px;
        }
        .invoice-wrapper { 
            max-width: 800px; 
            margin: 0 auto; 
            background: #0a0a0f; 
            border: 1px solid rgba(255,255,255,0.05); 
            border-radius: 32px; 
            overflow: hidden;
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5);
        }
        .header { 
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); 
            padding: 60px 50px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            position: relative;
            overflow: hidden;
        }
        .header::after {
            content: 'REGISTRY';
            position: absolute;
            right: -20px;
            bottom: -20px;
            font-size: 100px;
            font-weight: 900;
            opacity: 0.1;
            letter-spacing: -5px;
        }
        .header h1 { font-size: 32px; font-weight: 900; letter-spacing: -1px; }
        .header .meta { text-align: right; }
        .header .meta h2 { font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; opacity: 0.8; margin-bottom: 8px; }
        .header .meta .id { font-size: 24px; font-weight: 900; cursor: default; }

        .content { padding: 50px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 60px; }
        .section-title { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; color: #6366f1; margin-bottom: 20px; border-bottom: 1px solid rgba(99,102,241,0.2); padding-bottom: 10px; }
        .party p { color: #94a3b8; line-height: 1.8; font-weight: 500; }
        .party strong { color: #fff; font-weight: 900; display: block; margin-bottom: 5px; font-size: 16px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { padding: 20px; text-align: left; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: #475569; border-bottom: 1px solid rgba(255,255,255,0.05); }
        td { padding: 25px 20px; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .desc { color: #fff; font-weight: 700; font-size: 15px; margin-bottom: 5px; }
        .sub-desc { font-size: 11px; opacity: 0.6; }
        
        .total-section { 
            background: rgba(255,255,255,0.02); 
            padding: 30px; 
            border-radius: 24px; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            gap: 40px;
        }
        .total-label { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: #6366f1; }
        .total-val { font-size: 32px; font-weight: 900; color: #fff; letter-spacing: -1px; }

        .badge { display: inline-block; padding: 6px 14px; border-radius: 12px; font-size: 10px; font-weight: 900; letter-spacing: 1px; }
        .paid { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
        .pending { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }

        .footer { padding: 40px 50px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); font-size: 11px; color: #475569; letter-spacing: 1px; }
        .footer b { color: #6366f1; }

        @media print {
            body { background: #fff; color: #000; padding: 0; }
            .invoice-wrapper { border: none; border-radius: 0; box-shadow: none; max-width: 100%; }
            .header { background: #000 !important; color: #fff !important; }
            .total-section { background: #f8fafc; border: 1px solid #e2e8f0; }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="header">
            <div>
                <h1>THAMBU CORE</h1>
                <p style="opacity:0.6; font-weight:700; font-size:10px; letter-spacing:3px; margin-top:8px; text-transform:uppercase;">Hardware & Logistics Network</p>
            </div>
            <div class="meta">
                <h2>Registry Index</h2>
                <p class="id">{{ $invoice->invoice_number ?? $order->tc_job_id }}</p>
                <p style="opacity:0.6; font-size:12px; margin-top:5px; font-weight:600;">Authorized at {{ ($invoice ?? $order)->created_at->format('H:i') }} | {{ ($invoice ?? $order)->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="content">
            <div class="grid">
                <div class="party">
                    <h3 class="section-title">Nodal Hub (Origin)</h3>
                    <p>
                        <strong>{{ config('custom.company_name', 'TC Service Center') }}</strong>
                        Global Logistics Hub #001<br>
                        Auth: {{ config('custom.gst_number', 'GSTINXXXXXXXXXXXX') }}<br>
                        Signal: {{ config('custom.company_phone', '+91 XXXXXXXXXX') }}<br>
                        Registry: {{ config('custom.company_email', 'admin@tcservice.com') }}
                    </p>
                </div>
                <div class="party">
                    <h3 class="section-title">Target Node (Recipient)</h3>
                    <p>
                        <strong>{{ $invoice->customer_name ?? $order->customer?->name ?? 'Unknown Identity' }}</strong>
                        Comms: {{ $invoice->phone ?? $order->customer?->mobile ?? '' }}<br>
                        Matrix: {{ $invoice->email ?? $order->customer?->email ?? '' }}<br>
                        Location: {{ $invoice->address ?? $order->delivery_address ?? $order->customer?->address ?? 'HUB PICKUP' }}
                    </p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Module Description</th>
                        <th>Status</th>
                        <th style="text-align:right;">Sub-Credits</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($invoice) && $invoice->items()->count() > 0)
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                <div class="desc">{{ $item->item_name }}</div>
                                <div class="sub-desc">{{ $item->description }} (Qty: {{ $item->quantity }})</div>
                            </td>
                            <td>
                                <span class="badge {{ $invoice->payment_status === 'paid' ? 'paid' : 'pending' }}">
                                    {{ strtoupper($invoice->payment_status) }}
                                </span>
                            </td>
                            <td style="text-align:right; color:#fff; font-weight:900;">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                <div class="desc">Operational Phase: {{ ucwords(str_replace('_', ' ', $order->status)) }}</div>
                                <div class="sub-desc">Target Device: {{ $order->device?->brand }} {{ $order->device?->model }}</div>
                                <div class="sub-desc" style="margin-top:8px; display:block; opacity:0.8;"><b>Anomaly Detected:</b> {{ $order->fault_details }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $order->is_paid ? 'paid' : 'pending' }}">
                                    {{ $order->is_paid ? 'VERIFIED' : 'PENDING' }}
                                </span>
                            </td>
                            <td style="text-align:right; color:#fff; font-weight:900;">₹{{ number_format($order->estimated_cost ?? 0, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-label">Final Gross Payload</div>
                <div class="total-val">₹{{ number_format($invoice->total ?? $order->estimated_cost ?? 0) }}</div>
            </div>
            
            <p style="margin-top:40px; font-size:10px; color:#475569; text-transform:uppercase; letter-spacing:1px; font-weight:900;">
                Protocol: {{ strtoupper($order->delivery_type === 'take_away' ? 'Manual Extraction' : 'Rapid Terminal Delivery') }} | Secure Auth Link: {{ $order->tc_job_id }}
            </p>
        </div>

        <div class="footer">
            <p>Thank you for validating <b>THAMBU CORE</b> — Finalized at Nodal Hub. Remote support active.</p>
            <p style="margin-top:10px; opacity:0.5;">DOCUMENT HAS BEEN ELECTRONICALLY SIGNED AND ENCRYPTED. REPRODUCTION WITHOUT AUTH IS BREACH OF PROTOCOL.</p>
        </div>
    </div>
</body>
</html>
