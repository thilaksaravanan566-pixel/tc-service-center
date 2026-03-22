<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $game_type
 * @property int $score
 * @property int|null $time_seconds
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereGameType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereTimeSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TechScore extends Model
{
    protected $fillable = ['customer_id', 'game_type', 'score', 'time_seconds'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
