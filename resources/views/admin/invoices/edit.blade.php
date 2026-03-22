@extends('layouts.admin')

@section('title', 'Edit Invoice')

@section('content')
<div class="container-fluid" x-data="invoiceForm()">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Invoice: {{ $invoice->invoice_number }}</h1>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Customer & Order Info --}}
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ $invoice->customer_name }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $invoice->phone }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $invoice->email }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ $invoice->address }}</textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Device Info</label>
                                <input type="text" name="device_name" class="form-control" value="{{ $invoice->device_name }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Technician</label>
                                <input type="text" name="technician" class="form-control" value="{{ $invoice->technician }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invoice Items Table --}}
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice Items</h6>
                        <button type="button" @click="addItem()" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 30%">Item Name</th>
                                        <th style="width: 30%">Description</th>
                                        <th style="width: 10%">Qty</th>
                                        <th style="width: 15%">Price</th>
                                        <th style="width: 15%">Total</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td>
                                                <input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" class="form-control form-control-sm" required>
                                            </td>
                                            <td>
                                                <input type="text" :name="'items['+index+'][description]'" x-model="item.description" class="form-control form-control-sm">
                                            </td>
                                            <td>
                                                <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" class="form-control form-control-sm" step="0.01" min="0" @input="calculateItemTotal(item)">
                                            </td>
                                            <td>
                                                <input type="number" :name="'items['+index+'][price]'" x-model.number="item.price" class="form-control form-control-sm" step="0.01" min="0" @input="calculateItemTotal(item)">
                                            </td>
                                            <td class="text-right align-middle">
                                                <span x-text="formatCurrency(item.total)"></span>
                                            </td>
                                            <td>
                                                <button type="button" @click="removeItem(index)" class="btn btn-danger btn-sm" :disabled="items.length === 1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold align-middle">Subtotal</td>
                                        <td class="text-right align-middle font-weight-bold">
                                            <span x-text="formatCurrency(subtotal)"></span>
                                            <input type="hidden" name="subtotal" :value="subtotal">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right align-middle font-weight-bold">Tax Amount</td>
                                        <td class="p-0">
                                            <input type="number" name="tax" x-model.number="taxAmount" class="form-control form-control-sm border-0 text-right font-weight-bold" step="0.01" min="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right align-middle font-weight-bold">Discount</td>
                                        <td class="p-0">
                                            <input type="number" name="discount" x-model.number="discount" class="form-control form-control-sm border-0 text-right font-weight-bold" step="0.01" min="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="4" class="text-right font-weight-bold align-middle">Grand Total</td>
                                        <td class="text-right align-middle font-weight-bold">
                                            <span x-text="formatCurrency(grandTotal)"></span>
                                            <input type="hidden" name="total" :value="grandTotal">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-4 text-right">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-save"></i> Update Invoice
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function invoiceForm() {
    return {
        items: @json($invoice->items),
        taxAmount: {{ $invoice->tax }},
        discount: {{ $invoice->discount }},
        
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0);
        },
        
        get grandTotal() {
            return (this.subtotal + parseFloat(this.taxAmount)) - parseFloat(this.discount);
        },
        
        addItem() {
            this.items.push({ item_name: '', description: '', quantity: 1, price: 0, total: 0 });
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        
        calculateItemTotal(item) {
            item.total = item.quantity * item.price;
        },
        
        formatCurrency(value) {
            return '₹' + parseFloat(value).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}
</script>
@endpush
@endsection
