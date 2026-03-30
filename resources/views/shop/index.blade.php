@extends('layouts.customer')
@section('title', 'Spare Parts Shop')

@section('content')
<div class="animate-slide-up" style="padding-bottom:40px">

    {{-- Page Header --}}
    <div class="page-header" style="margin-bottom:24px">
        <h1 class="page-title">🛒 Spare Parts Shop</h1>
        <p class="page-sub">Browse genuine components for all device types</p>
    </div>

    {{-- Search & Filter Bar --}}
    <form method="GET" action="{{ route('shop.index') }}" id="shop-filter-form">
        <div class="card" style="padding:16px;margin-bottom:20px">
            <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">

                {{-- Search --}}
                <div style="position:relative;flex:1;min-width:200px">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text-muted)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search parts, brands..."
                           class="super-input" style="padding-left:38px;width:100%"
                           onchange="this.form.submit()">
                </div>

                {{-- Sort --}}
                <select name="sort" class="super-input" style="width:auto;min-width:140px" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected' : '' }}>Newest First</option>
                    <option value="price_asc"  {{ request('sort') === 'price_asc'           ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc'          ? 'selected' : '' }}>Price: High → Low</option>
                    <option value="name_asc"   {{ request('sort') === 'name_asc'            ? 'selected' : '' }}>Name A–Z</option>
                </select>

                {{-- Results count --}}
                <span style="font-size:0.8rem;color:var(--text-muted);white-space:nowrap">
                    {{ $parts->count() }} {{ Str::plural('item', $parts->count()) }} found
                </span>

                @if(request('search') || (request('category') && request('category') !== 'all'))
                    <a href="{{ route('shop.index') }}" style="font-size:0.8rem;color:var(--primary);text-decoration:none;white-space:nowrap">✕ Clear filters</a>
                @endif
            </div>
        </div>

        {{-- Category Pills --}}
        @if($categories->count() > 0)
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px">
            <a href="{{ route('shop.index', array_merge(request()->except('category'), ['sort' => request('sort')])) }}"
               style="padding:6px 16px;border-radius:50px;font-size:0.78rem;font-weight:600;text-decoration:none;border:1px solid;transition:all 0.15s;
               {{ !request('category') || request('category') === 'all' ? 'background:var(--primary);color:#fff;border-color:var(--primary)' : 'background:transparent;color:var(--text-secondary);border-color:var(--border)' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('shop.index', array_merge(request()->except('category'), ['category' => $cat, 'sort' => request('sort')])) }}"
               style="padding:6px 16px;border-radius:50px;font-size:0.78rem;font-weight:600;text-decoration:none;border:1px solid;transition:all 0.15s;
               {{ request('category') === $cat ? 'background:var(--primary);color:#fff;border-color:var(--primary)' : 'background:transparent;color:var(--text-secondary);border-color:var(--border)' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif
    </form>

    {{-- Products Grid --}}
    @if($parts->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px">
        @foreach($parts as $part)
        <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column;transition:transform 0.15s,box-shadow 0.15s"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.12)'"
             onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">

            {{-- Product Image --}}
            <div style="aspect-ratio:1/1;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative">
                @if($part->image_path)
                    <img src="{{ app('filesystem')->url($part->image_path) }}"
                         alt="{{ $part->name }}"
                         style="width:100%;height:100%;object-fit:contain;padding:16px;transition:transform 0.3s"
                         onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <span style="font-size:3rem;opacity:0.15">⚙️</span>
                @endif

                {{-- Stock badge --}}
                <div style="position:absolute;top:10px;left:10px">
                    @if($part->stock <= 5)
                        <span style="background:#fef2f2;color:#dc2626;font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:20px;border:1px solid #fecaca">Only {{ $part->stock }} left</span>
                    @else
                        <span style="background:#f0fdf4;color:#16a34a;font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:20px;border:1px solid #bbf7d0">In Stock</span>
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div style="padding:14px;flex:1;display:flex;flex-direction:column;gap:6px">
                @if($part->category)
                    <span style="font-size:0.68rem;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.5px">{{ $part->category }}</span>
                @endif
                <a href="{{ route('shop.show', $part->id) }}"
                   style="font-size:0.9rem;font-weight:700;color:var(--text-primary);text-decoration:none;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"
                   onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-primary)'">
                    {{ $part->name }}
                </a>
                @if($part->brand)
                    <span style="font-size:0.72rem;color:var(--text-muted)">{{ $part->brand }}</span>
                @endif

                <div style="margin-top:auto;padding-top:12px;display:flex;align-items:center;justify-content:space-between;gap:8px">
                    <span style="font-size:1.1rem;font-weight:800;color:var(--text-primary)">₹{{ number_format($part->price) }}</span>

                    <form action="{{ route('customer.cart.add', $part->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary" style="padding:7px 14px;font-size:0.75rem;border-radius:var(--radius-sm)">
                            + Cart
                        </button>
                    </form>
                </div>

                <a href="{{ route('shop.show', $part->id) }}"
                   style="font-size:0.75rem;color:var(--primary);text-decoration:none;text-align:center;padding:6px;border:1px solid var(--primary);border-radius:var(--radius-sm);margin-top:4px;transition:all 0.15s"
                   onmouseover="this.style.background='var(--primary-50)'" onmouseout="this.style.background='transparent'">
                    View Details →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- Empty State --}}
    <div class="card" style="padding:60px 24px;text-align:center">
        <div style="font-size:4rem;margin-bottom:16px;opacity:0.3">🔍</div>
        <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:8px">No parts found</h3>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:20px">
            @if(request('search'))
                No results for "<strong>{{ request('search') }}</strong>"
                @if(request('category'))  in category <strong>{{ request('category') }}</strong>@endif.
            @elseif(request('category'))
                No parts in category <strong>{{ request('category') }}</strong>.
            @else
                No parts are currently in stock.
            @endif
        </p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse All Parts</a>
    </div>
    @endif
</div>
@endsection
