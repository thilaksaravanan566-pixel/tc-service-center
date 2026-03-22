<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\ServiceOrder|null $serviceOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DamagePhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DamagePhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DamagePhoto query()
 * @mixin \Eloquent
 */
class DamagePhoto extends Model
{
    protected $fillable = [
        'service_order_id',
        'photo',
        'type'
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}