<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $customer_id
 * @property int $spare_part_id
 * @property int $quantity
 * @property numeric $total_price
 * @property string $status
 * @property string $payment_method
 * @property bool $is_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $delivery_type
 * @property string|null $delivery_address
 * @property string|null $delivery_location_url
 * @property string|null $delivery_mobile
 * @property int|null $delivery_partner_id
 * @property string|null $tracking_number
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User|null $deliveryPartner
 * @property-read string $status_label
 * @property-read \App\Models\SparePart $sparePart
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereDeliveryLocationUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereDeliveryMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereDeliveryPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereDeliveryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereSparePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'spare_part_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'is_paid',
        'delivery_type',
        'delivery_address',
        'delivery_location_url',
        'delivery_mobile',
        'delivery_partner_id',
        'tracking_number',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function deliveryPartner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    /**
     * Status label mapping for display.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}

