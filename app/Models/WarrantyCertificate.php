<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $warranty_type
 * @property int|null $spare_part_id
 * @property int|null $service_order_id
 * @property string|null $serial_number
 * @property \Illuminate\Support\Carbon $purchase_date
 * @property \Illuminate\Support\Carbon $warranty_start
 * @property \Illuminate\Support\Carbon $warranty_end
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WarrantyClaim> $claims
 * @property-read int|null $claims_count
 * @property-read \App\Models\Customer $customer
 * @property-read int $days_remaining
 * @property-read bool $is_active
 * @property-read int $progress_percent
 * @property-read \App\Models\ServiceOrder|null $serviceOrder
 * @property-read \App\Models\SparePart|null $sparePart
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereServiceOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereSparePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereWarrantyEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereWarrantyStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarrantyCertificate whereWarrantyType($value)
 * @mixin \Eloquent
 */
class WarrantyCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'warranty_type',
        'spare_part_id',
        'service_order_id',
        'serial_number',
        'purchase_date',
        'warranty_start',
        'warranty_end',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date'  => 'date',
        'warranty_start' => 'date',
        'warranty_end'   => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(WarrantyClaim::class);
    }

    /**
     * Check if warranty is still active today.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && $this->warranty_end->isFuture();
    }

    /**
     * Days remaining in warranty.
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->warranty_end->isPast()) return 0;
        return (int) now()->diffInDays($this->warranty_end);
    }

    /**
     * Progress percentage for warranty bar.
     */
    public function getProgressPercentAttribute(): int
    {
        $total = $this->warranty_start->diffInDays($this->warranty_end);
        if ($total === 0) return 100;
        $elapsed = $this->warranty_start->diffInDays(now());
        return min(100, (int) (($elapsed / $total) * 100));
    }
}
