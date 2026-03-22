<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\SparePart|null $sparePart
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockLog query()
 * @mixin \Eloquent
 */
class StockLog extends Model
{
    protected $fillable = [
        'spare_part_id',
        'type',
        'quantity'
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}