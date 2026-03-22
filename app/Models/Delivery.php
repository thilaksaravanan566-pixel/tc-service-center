<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_type',
        'reference_id',
        'delivery_person_id',
        'status',
        'pickup_location',
        'drop_location',
        'current_lat',
        'current_lng',
        'picked_up_at',
        'delivered_at',
    ];

    protected $casts = [
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
    ];

    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }
}
