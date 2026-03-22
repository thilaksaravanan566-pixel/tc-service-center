<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'product_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'previous_stock',
        'new_stock',
        'description',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
