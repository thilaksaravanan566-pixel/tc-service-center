<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $order_type
 * @property int $order_id
 * @property int|null $delivery_partner_id
 * @property int|null $customer_id
 * @property float|null $partner_lat
 * @property float|null $partner_lng
 * @property float|null $customer_lat
 * @property float|null $customer_lng
 * @property string|null $customer_address
 * @property string $delivery_status
 * @property \Illuminate\Support\Carbon|null $partner_location_updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\User|null $deliveryPartner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereCustomerAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereCustomerLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereCustomerLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereDeliveryPartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereDeliveryStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereOrderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation wherePartnerLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation wherePartnerLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation wherePartnerLocationUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryLocation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
        'order_id',
        'delivery_partner_id',
        'customer_id',
        'partner_lat',
        'partner_lng',
        'customer_lat',
        'customer_lng',
        'customer_address',
        'delivery_status',
        'partner_location_updated_at',
    ];

    protected $casts = [
        'partner_lat'                 => 'float',
        'partner_lng'                 => 'float',
        'customer_lat'                => 'float',
        'customer_lng'                => 'float',
        'partner_location_updated_at' => 'datetime',
    ];

    public function deliveryPartner()
    {
        return $this->belongsTo(User::class, 'delivery_partner_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get or create a delivery location record for a given order.
     */
    public static function forOrder(string $type, int $orderId): self
    {
        return self::firstOrCreate(
            ['order_type' => $type, 'order_id' => $orderId],
            ['delivery_status' => 'pending']
        );
    }

    /**
     * Update delivery partner location
     */
    public function updatePartnerLocation(float $lat, float $lng): void
    {
        $this->update([
            'partner_lat'                 => $lat,
            'partner_lng'                 => $lng,
            'partner_location_updated_at' => now(),
        ]);
    }
}
