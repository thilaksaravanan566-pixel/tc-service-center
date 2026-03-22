<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_order_id',
        'method',
        'courier_name',
        'tracking_number',
        'bus_name',
        'from_location',
        'to_location',
        'lr_number',
        'contact_number',
        'dispatch_at',
        'delivery_eta',
        'status',
    ];

    protected $casts = [
        'dispatch_at' => 'datetime',
        'delivery_eta' => 'datetime',
    ];

    public function dealerOrder()
    {
        return $this->belongsTo(DealerOrder::class);
    }
}
