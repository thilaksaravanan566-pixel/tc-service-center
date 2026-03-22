<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $user_id
 * @property string $sender_type
 * @property string $message
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupportMessage whereUserId($value)
 * @mixin \Eloquent
 */
class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'sender_type',
        'message',
        'is_read',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
