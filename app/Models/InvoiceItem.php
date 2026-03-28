<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_name',
        'description',
        'quantity',
        'price',
        'total',
        'hsn_sac',
        'discount_amount',
        'discount_percentage',
        'tax_percentage',
        'tax_amount',
        'cgst_amount',
        'sgst_amount',
        'igst_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
