<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TechBadge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TechBadge extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'tech_player_badge', 'badge_id', 'customer_id')->withPivot('unlocked_at');
    }
}
