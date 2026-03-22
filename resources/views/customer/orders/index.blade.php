@extends('layouts.customer')
@section('title', 'My Orders')

@section('content')
<div class="animate-slide-up">

    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <div>
            <h1 class="page-title">My Orders</h1>
            <p class="page-sub">Track your product orders and purchase history.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-primary">
            <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Browse Shop
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <p class="empty-state-title">No orders yet</p>
                <p class="empty-state-text">When you purchase spare parts or devices, your orders will appear here.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:16px">Explore Products</a>
            </div>
        </div>
    @else
        <div class="card" style="overflow:hidden">
            <div style="overflow-x:auto">
                <table class="pro-table" style="min-width:700px">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align:right">Amount</th>
                            <th style="text-align:right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <?php /** @var \App\Models\ProductOrder $order */ ?>
                        <tr>
                            <td>
                                <span style="font-size:0.8rem;font-weight:600;color:var(--primary);font-family:monospace">
                                    #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px">
                                    <div style="width:40px;height:40px;border-radius:var(--radius-sm);background:var(--primary-50);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">
                                        @if($order->sparePart?->image_path)
                                            <img src="{{ app('filesystem')->url($order->sparePart->image_path) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                        @else
                                            <span style="font-size:1.1rem">📦</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">{{ $order->sparePart?->name ?? 'Product' }}</p>
                                        <p style="font-size:0.72rem;color:var(--text-muted)">Qty: {{ $order->quantity }}</p>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.8rem;color:var(--text-secondary)">{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                @php
                                    $map = [
                                        'pending' => ['badge-amber','Pending'],
                                        'confirmed' => ['badge-sky','Confirmed'],
                                        'packed' => ['badge-blue','Packed'],
                                        'shipped' => ['badge-blue','Shipped'],
                                        'out_for_delivery' => ['badge-blue','Out for Delivery'],
                                        'delivered' => ['badge-green','Delivered'],
                                        'cancelled' => ['badge-red','Cancelled'],
                                    ];
                                    [$cls,$lbl] = $map[$order->status] ?? ['badge-gray', ucfirst($order->status)];
                                @endphp
                                <span class="badge {{ $cls }}">{{ $lbl }}</span>
                            </td>
                            <td style="text-align:right;font-size:0.875rem;font-weight:700;color:var(--text-primary)">
                                ₹{{ number_format($order->total_price ?? ($order->quantity * ($order->sparePart?->price ?? 0))) }}
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('customer.orders.track', $order->id) }}" class="btn btn-sm btn-secondary">Track</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div style="padding:16px 24px;border-top:1px solid var(--border)">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
