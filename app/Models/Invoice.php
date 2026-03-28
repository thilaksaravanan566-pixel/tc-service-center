<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\ServiceOrder;
use App\Models\ProductOrder;
use App\Models\InvoiceItem;
use App\Models\Dealer;
use App\Models\DealerOrder;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'dealer_id',
        'customer_name',
        'phone',
        'email',
        'address',
        'service_order_id',
        'product_order_id',
        'device_name',
        'technician',
        'billing_type',
        'subtotal',
        'gst_percentage',
        'gst_amount',
        'discount',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'pdf_path',
        'dealer_order_id',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
        'round_off',
        'is_draft',
        'qr_code_path',
        'barcode_path',
        'template_name',
        'bill_type',
        'valid_until',
        'parent_estimate_id',
        'customer_gst',
        'state_code',
    ];

    protected $casts = [
        'valid_until' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function productOrder()
    {
        return $this->belongsTo(ProductOrder::class);
    }

    public function dealerOrder()
    {
        return $this->belongsTo(DealerOrder::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function estimateFrom()
    {
        return $this->belongsTo(Invoice::class, 'parent_estimate_id');
    }

    public function convertedTo()
    {
        return $this->hasOne(Invoice::class, 'parent_estimate_id');
    }
}
