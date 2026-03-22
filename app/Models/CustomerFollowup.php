<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $created_by
 * @property string $type
 * @property string $notes
 * @property \Illuminate\Support\Carbon|null $followup_at
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereFollowupAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerFollowup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerFollowup extends Model
{
    protected $fillable = [
        'customer_id', 'created_by', 'type', 'notes',
        'followup_at', 'is_completed', 'completed_at',
    ];

    protected $casts = [
        'followup_at'  => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
