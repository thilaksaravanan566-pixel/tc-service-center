@extends('layouts.admin')

@section('title', 'Manual Billing — Service Center')

@section('content')
<div class="max-w-6xl mx-auto py-8" x-data="invoiceSystem()">
    
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-8 fade-up">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight italic uppercase">
                <span x-text="billType === 'estimation' ? 'Generate' : 'Manual'"></span> 
                <span :class="billType === 'estimation' ? 'text-sky-500' : 'text-emerald-500'" x-text="billType === 'estimation' ? 'Estimation' : 'Tax Invoice'"></span>
            </h1>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">
                <span x-show="billType === 'estimation'">Create an editable quotation for customers</span>
                <span x-show="billType === 'gst'">Generate a legal tax invoice (Locks after saving)</span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white transition-all text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Cancel
            </a>
            <button @click="submitInvoice()" class="px-6 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Finalize & Save
            </button>
        </div>
    </div>

    <form id="invoiceForm" action="{{ route('admin.invoices.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT: INVOICE ITEMS --}}
            <div class="lg:col-span-2 space-y-6 fade-up stagger-1">
                
                <div class="card p-6 border-indigo-500/20">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest">Billable Items & Services</h3>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest">
                            + Add Line Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    <th class="pb-4 pt-0 px-2 w-[40%] text-left">Item Name / Service</th>
                                    <th class="pb-4 pt-0 px-2 w-[15%] text-center">Qty</th>
                                    <th class="pb-4 pt-0 px-2 w-[20%] text-right">Price (₹)</th>
                                    <th class="pb-4 pt-0 px-2 w-[20%] text-right">Amount (₹)</th>
                                    <th class="pb-4 pt-0 px-2 w-[5%]"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b border-white/5 group hover:bg-white/[0.02]">
                                        <td class="py-4 px-2">
                                            <input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" placeholder="E.g. LED Replacement" class="w-full bg-transparent border-none focus:ring-0 text-gray-200 font-bold placeholder-gray-700 text-sm p-0" required>
                                            <input type="text" :name="'items['+index+'][description]'" x-model="item.description" placeholder="Description (optional)" class="w-full bg-transparent border-none focus:ring-0 text-gray-500 text-[10px] p-0 mt-1 placeholder-gray-800">
                                        </td>
                                        <td class="py-4 px-2">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="updateTotals()" class="w-full bg-transparent border-none focus:ring-0 text-white font-bold text-center text-sm p-0" required>
                                        </td>
                                        <td class="py-4 px-2">
                                            <input type="number" step="0.01" :name="'items['+index+'][price]'" x-model.number="item.price" @input="updateTotals()" class="w-full bg-transparent border-none focus:ring-0 text-indigo-400 font-bold text-right text-sm p-0" required>
                                        </td>
                                        <td class="py-4 px-2 text-right">
                                            <span class="text-white font-black text-sm tracking-tighter" x-text="formatINR(item.quantity * item.price)"></span>
                                        </td>
                                        <td class="py-4 px-2 text-right">
                                            <button type="button" @click="removeItem(index)" class="text-gray-600 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100" :disabled="items.length === 1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- AGGREGATE SUMMARY --}}
                    <div class="mt-8 pt-6 border-t border-white/5 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Net Subtotal</span>
                            <span class="text-white font-bold" x-text="formatINR(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm" x-show="billType === 'gst'">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Applied GST (%)</span>
                                <input type="number" name="gst_percentage" x-model.number="gstPercent" @input="updateTotals()" class="w-12 bg-white/5 border border-white/10 rounded px-1.5 py-0.5 text-[10px] text-indigo-400 font-black outline-none focus:border-indigo-500">
                            </div>
                            <span class="text-white font-bold" x-text="formatINR(gstAmount)"></span>
                        </div>
                        <input type="hidden" name="gst_percentage" :value="billType === 'gst' ? gstPercent : 0" x-if="billType === 'estimation'">
                        <div class="flex justify-between items-center pt-4 border-t border-white/10">
                            <span class="text-indigo-400 font-black uppercase tracking-widest text-xs italic">Grand Payable</span>
                            <span class="text-2xl font-black text-white italic tracking-tighter" x-text="formatINR(total)"></span>
                        </div>
                    </div>
                </div>

                {{-- BILLING NOTES --}}
                <div class="card p-6 border-white/5">
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Internal Notes / Terms</h3>
                    <textarea name="notes" placeholder="Warranty information, additional charges detail, or specific payment instructions..." class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-sm text-gray-300 outline-none focus:border-indigo-500 h-24"></textarea>
                </div>
            </div>

            {{-- RIGHT: SETTINGS & CUSTOMER --}}
            <div class="space-y-6 fade-up stagger-2">
                
                {{-- INVOICE IDENTITY --}}
                <div class="card p-5 border-white/5">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Invoice Identity</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Document Type (Estimation / GST)</label>
                            <div class="flex bg-white/5 rounded-xl p-1 gap-1 border border-white/10">
                                <button type="button" @click="billType = 'estimation'; updateTotals()" :class="billType === 'estimation' ? 'bg-sky-500 text-white shadow-lg' : 'text-gray-400 hover:text-white'" class="flex-1 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Estimation (Quote)</button>
                                <button type="button" @click="billType = 'gst'; updateTotals()" :class="billType === 'gst' ? 'bg-emerald-500 text-white shadow-lg' : 'text-gray-400 hover:text-white'" class="flex-1 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">GST Tax Invoice</button>
                            </div>
                            <input type="hidden" name="bill_type" :value="billType">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Document Number</label>
                            <input type="text" name="invoice_number" :value="invoiceNumber" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs ring-0 outline-none" readonly>
                        </div>
                        <div x-show="billType === 'estimation'">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Valid Until (Optional)</label>
                            <input type="date" name="valid_until" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500 [&::-webkit-calendar-picker-indicator]:invert">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Billing Category</label>
                            <div class="flex gap-2">
                                <template x-for="type in ['dealer', 'online', 'walkin']">
                                    <button type="button" @click="customerType = type" :class="customerType === type ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-white/5 text-gray-500 border-white/10'" class="flex-1 py-2 rounded-lg border text-[9px] font-black uppercase tracking-widest transition-all">
                                        <span x-text="type"></span>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="billing_type" :value="customerType">
                        </div>
                    </div>
                </div>

                {{-- CUSTOMER INFO --}}
                <div class="card p-5 border-white/5">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Customer Details</h3>
                    <div class="space-y-4">
                        <input type="hidden" name="customer_id" value="{{ $order ? $order->customer_id : '' }}">
                        <input type="hidden" name="service_order_id" value="{{ $order ? $order->id : '' }}">
                        
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Full Name</label>
                            <input type="text" name="customer_name" value="{{ $order ? ($order->device->customer->name ?? $order->dealer->name ?? '') : '' }}" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Mobile / Contact</label>
                            <input type="text" name="phone" value="{{ $order ? ($order->device->customer->mobile ?? $order->dealer->mobile ?? '') : '' }}" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500" required>
                        </div>
                        <div x-show="billType === 'gst'">
                            <label class="text-[10px] font-black text-emerald-400 uppercase mb-1 flex justify-between items-center">
                                <span>🧾 Customer GSTIN (B2B)</span>
                                <span x-show="isGstValid === true" class="text-emerald-400">✅</span>
                                <span x-show="isGstValid === false" class="text-rose-500">❌ Invalid Format</span>
                            </label>
                            <input type="text" name="customer_gst" x-model="customerGst" @input="customerGst = $event.target.value.toUpperCase()" placeholder="e.g. 33ABCDE1234F1Z5" maxlength="15" :class="isGstValid === false ? 'border-rose-500 focus:border-rose-500' : (isGstValid === true ? 'border-emerald-500 focus:border-emerald-500' : 'border-white/10 focus:border-emerald-500')" class="w-full bg-white/5 border rounded-lg p-2.5 text-white font-bold text-xs outline-none transition-all uppercase placeholder-gray-600">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Email (Optional)</label>
                            <input type="email" name="email" value="{{ $order ? ($order->device->customer->email ?? $order->dealer->email ?? '') : '' }}" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Billing Address</label>
                            <textarea name="address" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-[11px] outline-none focus:border-indigo-500 h-20">{{ $order ? ($order->device->customer->address ?? $order->dealer->address ?? '') : '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT STATUS --}}
                <div class="card p-5 border-white/5 bg-indigo-600/[0.03]">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Payment & Settlement</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Settlement Status</label>
                            <select name="payment_status" x-model="payStatus" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500 cursor-pointer">
                                <option value="unpaid">Unpaid / Credit</option>
                                <option value="partial">Partial Payment</option>
                                <option value="paid">Fully Paid</option>
                            </select>
                        </div>
                        <div x-show="payStatus !== 'unpaid'">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-1 block">Payment Method</label>
                            <select name="payment_method" class="w-full bg-white/5 border border-white/10 rounded-lg p-2.5 text-white font-bold text-xs outline-none focus:border-indigo-500 cursor-pointer text-gray-200">
                                <option value="cash">💵 Cash Payment</option>
                                <option value="upi">📱 UPI / PhonePe / GPay</option>
                                <option value="card">💳 Debit/Credit Card</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        {{-- HIDDEN INPUTS FOR CALCULATION --}}
        <input type="hidden" name="subtotal" :value="subtotal">
        <input type="hidden" name="gst_amount" :value="gstAmount">
        <input type="hidden" name="total" :value="total">
        <input type="hidden" name="discount" value="0"> {{-- Reserved for future --}}
        <input type="hidden" name="device_name" value="{{ $order && $order->device ? $order->device->brand . ' ' . $order->device->model : 'General Repair' }}">
        <input type="hidden" name="technician" value="{{ $order && $order->technician ? $order->technician->name : 'N/A' }}">

    </form>
</div>

<script>
function invoiceSystem() {
    return {
        items: [
            @if($order)
                { item_name: 'Repair Service ({{ $order->tc_job_id }})', description: 'Fault: {{ addslashes($order->fault_details) }}', quantity: 1, price: {{ $order->estimated_cost ?? 0 }} },
                @if($order->parts_used)
                    @foreach($order->parts_used as $part)
                        { item_name: '{{ addslashes($part['name']) }}', description: 'Spare Part Replacement', quantity: {{ $part['quantity'] }}, price: {{ $part['price'] }} },
                    @endforeach
                @endif
            @else
                { item_name: '', description: '', quantity: 1, price: 0 }
            @endif
        ],
        gstPercent: 18,
        billType: 'estimation',
        nextEstimateNumber: '{{ $nextEstimateNumber }}',
        nextGstNumber: '{{ $nextGstNumber }}',
        customerType: '{{ $order->order_type ?? "walkin" }}',
        payStatus: '{{ $order && $order->is_paid ? "paid" : "unpaid" }}',
        customerGst: '{{ $order && $order->device && $order->device->customer ? $order->device->customer->gst_number : '' }}',
        subtotal: 0,
        gstAmount: 0,
        total: 0,

        get invoiceNumber() {
            return this.billType === 'estimation' ? this.nextEstimateNumber : this.nextGstNumber;
        },

        get isGstValid() {
            if (!this.customerGst || this.customerGst.length === 0) return null;
            return /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(this.customerGst);
        },

        init() {
            this.updateTotals();
        },

        addItem() {
            this.items.push({ item_name: '', description: '', quantity: 1, price: 0 });
            this.updateTotals();
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
                this.updateTotals();
            }
        },

        updateTotals() {
            this.subtotal = this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
            if (this.billType === 'gst') {
                this.gstAmount = (this.subtotal * this.gstPercent) / 100;
            } else {
                this.gstAmount = 0;
            }
            this.total = this.subtotal + this.gstAmount;
        },

        formatINR(value) {
            return '₹' + parseFloat(value).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        submitInvoice() {
            document.getElementById('invoiceForm').submit();
        }
    }
}
</script>

<style>
    /* Premium styling overrides */
    .card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 20px;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
@endsection
