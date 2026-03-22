<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property string|null $action_url
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read bool $is_read
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification forCustomer(int $customerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereActionUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'title',
        'message',
        'action_url',
        'icon',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getIsReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Make as read.
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Scopes for convenience.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
