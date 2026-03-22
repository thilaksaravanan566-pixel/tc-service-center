<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $xp
 * @property int $level
 * @property int $games_played
 * @property float $success_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $coins
 * @property array<array-key, mixed>|null $tycoon_state
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereCoins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereGamesPlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereSuccessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereTycoonState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechPlayer whereXp($value)
 * @mixin \Eloquent
 */
class TechPlayer extends Model
{
    protected $fillable = [
        'customer_id', 
        'xp', 
        'level', 
        'games_played', 
        'success_rate',
        'coins',
        'tycoon_state'
    ];

    protected $casts = [
        'tycoon_state' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
