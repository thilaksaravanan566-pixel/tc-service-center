<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerInventory extends Model
{
    use HasFactory;

    protected $table = 'dealer_inventory';

    protected $fillable = [
        'dealer_id',
        'product_id',
        'stock_quantity',
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
