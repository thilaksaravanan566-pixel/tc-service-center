<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string|null $username
 * @property string $mobile
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $email
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TechBadge> $badges
 * @property-read int|null $badges_count
 * @property-read \App\Models\Cart|null $cart
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerFollowup> $followups
 * @property-read int|null $followups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductOrder> $productOrders
 * @property-read int|null $product_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceOrder> $serviceOrders
 * @property-read int|null $service_orders_count
 * @property-read \App\Models\TechPlayer|null $techPlayer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TechScore> $techScores
 * @property-read int|null $tech_scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WarrantyCertificate> $warranties
 * @property-read int|null $warranties_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WarrantyClaim> $warrantyClaims
 * @property-read int|null $warranty_claims_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUsername($value)
 * @mixin \Eloquent
 */
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'username',
        'mobile',
        'email',
        'address',
        'password',
        'gst_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * A customer can have many devices (Laptops, Printers, etc.)
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * A customer can have many service orders
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    /**
     * A customer can have many product orders (spare parts)
     */
    public function productOrders(): HasMany
    {
        return $this->hasMany(ProductOrder::class);
    }

    /**
     * Customer's shopping cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Customer's addresses
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Customer's warranty certificates
     */
    public function warranties(): HasMany
    {
        return $this->hasMany(WarrantyCertificate::class);
    }

    /**
     * Customer's warranty claims
     */
    public function warrantyClaims(): HasMany
    {
        return $this->hasMany(WarrantyClaim::class);
    }

    /**
     * Customer's in-app notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    /**
     * Unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    /**
     * CRM follow-up notes
     */
    public function followups(): HasMany
    {
        return $this->hasMany(\App\Models\CustomerFollowup::class);
    }

    /**
     * THAMBU TECH LAB RELATIONSHIPS
     */
    public function techPlayer()
    {
        return $this->hasOne(TechPlayer::class);
    }

    public function techScores()
    {
        return $this->hasMany(TechScore::class);
    }

    public function badges()
    {
        return $this->belongsToMany(TechBadge::class, 'tech_player_badge', 'customer_id', 'badge_id')->withPivot('unlocked_at');
    }
}