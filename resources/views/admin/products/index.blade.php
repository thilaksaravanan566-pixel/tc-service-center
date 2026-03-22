@extends('layouts.admin')

@section('title', 'Global Product Catalog')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Global Product Catalog</h1>
        <p class="text-gray-500">Manage your entire inventory and dealer pricing from one dashboard.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i> Add New Product
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4">
        <p class="text-xs uppercase font-bold text-gray-500">Total Products</p>
        <p class="text-2xl font-bold">{{ \App\Models\Product::count() }}</p>
    </div>
    <div class="card p-4 border-l-4 border-blue-500">
        <p class="text-xs uppercase font-bold text-gray-500">Active Items</p>
        <p class="text-2xl font-bold">{{ \App\Models\Product::where('status', 'active')->count() }}</p>
    </div>
    <div class="card p-4 border-l-4 border-red-500">
        <p class="text-xs uppercase font-bold text-gray-500">Low Stock</p>
        <p class="text-2xl font-bold text-red-500">{{ \App\Models\Product::where('stock_quantity', '<', 10)->count() }}</p>
    </div>
    <div class="card p-4 border-l-4 border-green-500">
        <p class="text-xs uppercase font-bold text-gray-500">Avg. Margin</p>
        <p class="text-2xl font-bold">~{{ number_format(\App\Models\Product::where('selling_price', '>', 0)->count() > 0 ? \App\Models\Product::where('selling_price', '>', 0)->get()->avg(fn($p) => ($p->selling_price - $p->purchase_price) / $p->selling_price * 100) : 0, 1) }}%</p>
    </div>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Product Details</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Brand/Model</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Pricing (P/S/D)</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Global Stock</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($products as $product)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ $product->name }}</p>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $product->sku ?? 'NO-SKU' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-medium">{{ $product->brand ?? '--' }}</p>
                    <p class="text-xs text-gray-400">{{ $product->model ?? '--' }}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        <p class="text-xs">💰 <span class="text-gray-400">P:</span> ₹{{ number_format($product->purchase_price, 2) }}</p>
                        <p class="text-xs font-bold">🏷️ <span class="text-gray-400">S:</span> ₹{{ number_format($product->selling_price, 2) }}</p>
                        <p class="text-xs text-blue-600 font-bold">🤝 <span class="text-gray-400">D:</span> ₹{{ number_format($product->dealer_price, 2) }}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-bold {{ $product->stock_quantity < 10 ? 'text-red-500' : 'text-gray-700' }}">
                        {{ $product->stock_quantity }} units
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="badge {{ $product->status === 'active' ? 'badge-green' : ($product->status === 'inactive' ? 'badge-gray' : 'badge-red') }}">
                        {{ strtoupper($product->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-blue-500 transition">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
</div>
@endsection
