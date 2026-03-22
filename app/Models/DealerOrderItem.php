<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_order_id',
        'product_id',
        'quantity',
        'price_per_unit',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(DealerOrder::class, 'dealer_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
