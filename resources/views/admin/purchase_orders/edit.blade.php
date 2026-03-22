@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.purchase-orders.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back
                </a>
                <h1 class="text-3xl font-black text-slate-900">Edit <span class="text-red-600">Purchase Order</span></h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm">
                <ul class="list-disc pl-5 font-medium text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-slate-100">
            <form action="{{ route('admin.purchase-orders.update', $purchase_order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Supplier Name</label>
                        <input type="text" name="supplier_name" value="{{ old('supplier_name', $purchase_order->supplier_name) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Item Name</label>
                            <input type="text" name="item_name" value="{{ old('item_name', $purchase_order->item_name) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Quantity</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $purchase_order->quantity) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Cost Price (per unit)</label>
                            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $purchase_order->cost_price) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Order Date</label>
                            <input type="date" name="order_date" value="{{ old('order_date', $purchase_order->order_date ? \Carbon\Carbon::parse($purchase_order->order_date)->format('Y-m-d') : '') }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Status</label>
                        <select name="status" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800 cursor-pointer">
                            <option value="Pending" {{ old('status', $purchase_order->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Received" {{ old('status', $purchase_order->status) == 'Received' ? 'selected' : '' }}>Received</option>
                            <option value="Cancelled" {{ old('status', $purchase_order->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest shadow-lg shadow-slate-200 transition-all">
                            Update Document
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
