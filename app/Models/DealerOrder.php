<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'dealer_id',
        'total_amount',
        'status',
        'payment_status',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function items()
    {
        return $this->hasMany(DealerOrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
