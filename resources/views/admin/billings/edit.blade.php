@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('admin.billings.index') }}" class="text-slate-400 hover:text-red-600 text-sm font-bold flex items-center gap-2 mb-2 transition-colors">
                    ← Back
                </a>
                <h1 class="text-3xl font-black text-slate-900">Edit <span class="text-red-600">Bill</span></h1>
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
            <form action="{{ route('admin.billings.update', $billing->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $billing->customer_name) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Description / Details</label>
                        <textarea name="description" rows="4" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">{{ old('description', $billing->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Amount (₹)</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount', $billing->amount) }}" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Invoice Date</label>
                            <input type="date" name="invoice_date" value="{{ old('invoice_date', $billing->invoice_date ? \Carbon\Carbon::parse($billing->invoice_date)->format('Y-m-d') : '') }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black tracking-widest text-red-600 uppercase mb-2">Status</label>
                        <select name="status" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-red-100 focus:border-red-400 transition-all font-bold text-slate-800 cursor-pointer">
                            <option value="Unpaid" {{ old('status', $billing->status) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Paid" {{ old('status', $billing->status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Cancelled" {{ old('status', $billing->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
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
