<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryUpdate extends Model
{
    protected $fillable = [
        'delivery_location_id',
        'updated_by',
        'status',
        'previous_status',
        'note',
        'proof_photo',
        'otp_code',
        'otp_verified',
        'otp_verified_at',
        'lat',
        'lng',
    ];

    protected $casts = [
        'otp_verified'    => 'boolean',
        'otp_verified_at' => 'datetime',
        'lat'             => 'decimal:7',
        'lng'             => 'decimal:7',
    ];

    public function deliveryLocation(): BelongsTo
    {
        return $this->belongsTo(DeliveryLocation::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
