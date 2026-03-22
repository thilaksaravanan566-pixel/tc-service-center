@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold">Add New Product</h1>
        <p class="text-gray-500 mt-2 text-sm italic">Create a new global catalog item with specific dealer and retail pricing.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary border border-gray-200">
        <i class="fas fa-arrow-left mr-2"></i> Back to Catalog
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="card p-8">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="col-span-2">
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Product Name</label>
                        <input type="text" name="name" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" placeholder="e.g. 512GB NVMe SSD" required>
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Category</label>
                        <select name="category" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl appearance-none">
                            <option value="Storage">Storage</option>
                            <option value="Memory">Memory (RAM)</option>
                            <option value="Processor">Processor (CPU)</option>
                            <option value="Display">Display Panels</option>
                            <option value="Battery">Battery Units</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Brand</label>
                        <input type="text" name="brand" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" placeholder="e.g. Western Digital">
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Model</label>
                        <input type="text" name="model" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" placeholder="e.g. SN850X Black">
                    </div>

                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" placeholder="e.g. WD-512-NVME">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Description</label>
                        <textarea name="description" rows="4" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" placeholder="Detailed product technical specs..."></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-6 bg-blue-50/50 border border-blue-100/50 rounded-2xl">
                    <div class="col-span-3 pb-2 border-b border-blue-100 flex items-center justify-between">
                        <h3 class="text-xs uppercase font-bold text-blue-600">Pricing & Margins</h3>
                        <p class="text-[10px] text-blue-400 italic font-medium whitespace-nowrap px-3 py-1 bg-white border border-blue-100 rounded-lg">Margin Tracker (Live)</p>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Purchase Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">₹</span>
                            <input type="number" step="0.01" name="purchase_price" class="input w-full p-3 pl-8 bg-white border border-gray-200 rounded-xl" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Selling Price (B2C)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">₹</span>
                            <input type="number" step="0.01" name="selling_price" class="input w-full p-3 pl-8 bg-white border border-gray-200 rounded-xl" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Dealer Price (B2B)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">₹</span>
                            <input type="number" step="0.01" name="dealer_price" class="input w-full p-3 pl-8 bg-white border border-gray-200 rounded-xl" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Global Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl" value="0" required>
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-bold text-gray-400 mb-2">Status</label>
                        <select name="status" class="input w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                            <option value="active">Active (Available)</option>
                            <option value="inactive">Inactive (Private)</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="reset" class="btn btn-secondary px-8">Reset Form</button>
                    <button type="submit" class="btn btn-primary px-12 font-bold shadow-lg shadow-blue-500/20">Publish Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="card p-6 bg-gradient-to-br from-gray-900 to-blue-900 text-white shadow-2xl overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition duration-500"></div>
            <div class="relative p-2">
                <h3 class="text-sm font-bold uppercase tracking-widest text-blue-400 mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                    E-commerce Guide
                </h3>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/5">
                            <i class="fas fa-tags text-blue-300"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Dealer vs Retail</p>
                            <p class="text-[11px] text-gray-400 leading-relaxed mt-1">Selling price is what you charge direct customers. Dealer price is discounted for partners.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/5">
                            <i class="fas fa-warehouse text-blue-300"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Global Inventory</p>
                            <p class="text-[11px] text-gray-400 leading-relaxed mt-1">This stock is assigned to your main center. When dealers order, this stock moves to their local inventory.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/5">
                            <i class="fas fa-barcode text-blue-300"></i>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Unique SKU</p>
                            <p class="text-[11px] text-gray-400 leading-relaxed mt-1">Ensure the SKU is unique as it will be used for automated inventory logging and barcode scanning.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
