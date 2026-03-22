<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'phone',
        'address',
        'gst_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
