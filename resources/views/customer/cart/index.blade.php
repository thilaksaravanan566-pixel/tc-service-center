@extends('layouts.customer')
@section('title', 'My Cart')

@section('content')
<div class="animate-slide-up">

    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <div>
            <h1 class="page-title">Shopping Cart</h1>
            <p class="page-sub">Review your selected items before checkout.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline">Continue Shopping</a>
    </div>

    @if(isset($items) && $items->isNotEmpty())
        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start" class="cart-grid">

            {{-- Cart Items --}}
            <div class="card" style="overflow:hidden">
                <div style="padding:16px 24px;border-bottom:1px solid var(--border)">
                    <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">{{ $items->sum('quantity') }} item(s) in cart</p>
                </div>
                <div style="display:flex;flex-direction:column">
                    @foreach($items as $cartItem)
                    <?php /** @var \App\Models\CartItem $cartItem */ ?>
                    <div style="display:flex;align-items:center;gap:16px;padding:20px 24px;border-bottom:1px solid var(--border)" onmouseover="this.style.background='var(--primary-50)'" onmouseout="this.style.background='transparent'">

                        {{-- Product Image --}}
                        <div style="width:72px;height:72px;border-radius:var(--radius-sm);background:var(--primary-50);overflow:hidden;flex-shrink:0;border:1px solid var(--border)">
                            @if($cartItem->sparePart?->image_path)
                                <img src="{{ app('filesystem')->url($cartItem->sparePart?->image_path) }}"
                                     alt="{{ $cartItem->sparePart?->name }}"
                                     style="width:100%;height:100%;object-fit:cover">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.8rem">⚙️</div>
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div style="flex:1;min-width:0">
                            <p style="font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em">{{ $cartItem->sparePart?->brand ?? 'Spare Part' }}</p>
                            <h3 style="font-size:0.95rem;font-weight:600;color:var(--text-primary);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $cartItem->sparePart?->name ?? 'Unknown Product' }}</h3>
                            <p style="font-size:0.875rem;font-weight:700;color:var(--primary);margin-top:4px">₹{{ number_format($cartItem->sparePart?->price ?? 0) }} <span style="font-weight:400;font-size:0.75rem;color:var(--text-muted)">per unit</span></p>
                        </div>

                        {{-- Qty Controls --}}
                        <form method="POST" action="{{ route('customer.cart.update', $cartItem->id) }}" style="display:flex;align-items:center;gap:6px">
                            @csrf
                            <button type="button"
                                    onclick="changeQty({{ $cartItem->id }}, -1, {{ $cartItem->sparePart?->stock ?? 99 }})"
                                    class="icon-btn" style="font-size:1rem;font-weight:700">−</button>
                            <input type="number" name="quantity" id="qty-{{ $cartItem->id }}"
                                   value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->sparePart?->stock ?? 99 }}"
                                   style="width:44px;text-align:center;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 4px;font-size:0.875rem;font-weight:600;color:var(--text-primary);outline:none">
                            <button type="button"
                                    onclick="changeQty({{ $cartItem->id }}, 1, {{ $cartItem->sparePart?->stock ?? 99 }})"
                                    class="icon-btn" style="font-size:1rem;font-weight:700">+</button>
                            <button type="submit" id="submit-qty-{{ $cartItem->id }}" class="hidden" style="display:none"></button>
                        </form>

                        {{-- Line Total --}}
                        <div style="text-align:right;min-width:80px">
                            <p style="font-size:0.95rem;font-weight:700;color:var(--text-primary)">₹{{ number_format($cartItem->quantity * ($cartItem->sparePart?->price ?? 0)) }}</p>
                        </div>

                        {{-- Remove --}}
                        <form method="POST" action="{{ route('customer.cart.remove', $cartItem->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn" style="color:#ef4444;border-color:#fecaca" title="Remove">
                                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="card" style="padding:24px;position:sticky;top:84px">
                <h2 style="font-size:0.875rem;font-weight:700;color:var(--text-primary);margin-bottom:20px">Order Summary</h2>
                <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px">
                    <div style="display:flex;justify-content:space-between;font-size:0.8rem">
                        <span style="color:var(--text-secondary)">Subtotal ({{ $items->sum('quantity') }} items)</span>
                        <span style="font-weight:600;color:var(--text-primary)">₹{{ number_format($subtotal) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.8rem">
                        <span style="color:var(--text-secondary)">Shipping</span>
                        <span style="font-weight:600;color:#16a34a">Free</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.8rem">
                        <span style="color:var(--text-secondary)">Tax</span>
                        <span style="font-weight:600;color:var(--text-primary)">₹0</span>
                    </div>
                    <hr style="border:none;border-top:1px solid var(--border);margin:4px 0">
                    <div style="display:flex;justify-content:space-between">
                        <span style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">Total</span>
                        <span style="font-size:1.25rem;font-weight:700;color:var(--primary)">₹{{ number_format($subtotal) }}</span>
                    </div>
                </div>
                <a href="{{ route('customer.cart.checkout') }}" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px">
                    Proceed to Checkout →
                </a>
                <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                    <div style="display:flex;align-items:center;gap:8px;padding:10px;border-radius:var(--radius-sm);background:var(--primary-50);border:1px solid var(--border)">
                        <span style="font-size:1rem">🔒</span>
                        <p style="font-size:0.7rem;color:var(--text-muted)">Secure 256-bit SSL encrypted checkout</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;padding:10px;border-radius:var(--radius-sm);background:var(--primary-50);border:1px solid var(--border)">
                        <span style="font-size:1rem">🚀</span>
                        <p style="font-size:0.7rem;color:var(--text-muted)">Express dispatch within 24 hours</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="empty-state-title">Your cart is empty</p>
                <p class="empty-state-text">Browse our catalogue and add items to your cart to get started.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:16px">Browse Products</a>
            </div>
        </div>
    @endif
</div>

<script>
function changeQty(id, delta, max) {
    let input = document.getElementById('qty-' + id);
    let newVal = parseInt(input.value) + delta;
    if (newVal >= 1 && newVal <= max) {
        input.value = newVal;
        document.getElementById('submit-qty-' + id).click();
    }
}
</script>

<style>
@media (max-width:900px) { .cart-grid { grid-template-columns: 1fr !important; } }
</style>
@endsection
