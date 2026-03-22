<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'brand',
        'model',
        'sku',
        'purchase_price',
        'selling_price',
        'dealer_price',
        'stock_quantity',
        'status',
        'description',
        'image_path',
    ];

    public function dealerInventory()
    {
        return $this->hasMany(DealerInventory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(DealerOrderItem::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }
}
