<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    use HasFactory;

    const STATUS_RECEIVED = 'received';
    const STATUS_DIAGNOSING = 'diagnosing';
    const STATUS_REPAIRING = 'repairing';
    const STATUS_PENDING = 'pending';
    const STATUS_READY = 'ready';
    const STATUS_PACKING = 'packing';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'tc_job_id',
        'customer_id',
        'dealer_id',
        'device_id',
        'technician_id',
        'status',
        'fault_details',
        'engineer_comment',
        'estimated_cost',
        'is_paid',
        'delivery_type',
        'delivery_address',
        'delivery_location_url',
        'delivery_mobile',
        'delivery_partner_id',
        'order_type',
        'priority',
        'parts_used',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'estimated_cost' => 'decimal:2',
        'parts_used' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    public function inspectionPhotos()
    {
        return $this->hasMany(InspectionPhoto::class);
    }

    public function warranties()
    {
        return $this->hasMany(WarrantyCertificate::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
