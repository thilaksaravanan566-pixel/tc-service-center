<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'dealer_id',
        'type',
        'brand',
        'model',
        'serial_number',
        'processor',
        'ram',
        'ssd',
        'hdd',
        'ram_old',
        'storage_old',
        'damage_photos',
    ];

    protected $casts = [
        'damage_photos' => 'array',
    ];

    public function getTypeLabelAttribute(): string
    {
        $map = [
            'laptop' => 'Laptop',
            'desktop' => 'Desktop / PC',
            'printer' => 'Printer',
            'repair' => 'Standard Repair',
            'warranty' => 'Warranty Ticket',
            'desktop_assemble' => 'Custom Desktop Build',
            'cctv' => 'CCTV Installation',
            'used_laptop' => 'Second Laptops Order',
        ];

        return $map[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }
}