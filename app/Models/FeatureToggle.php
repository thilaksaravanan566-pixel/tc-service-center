<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_active
 * @property string $group
 * @property string|null $description
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeatureToggle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeatureToggle extends Model
{
    protected $fillable = ['name', 'is_active', 'group', 'description', 'icon'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Check if a feature/module is currently active.
     */
    public static function isEnabled(string $name): bool
    {
        /** @var \App\Models\FeatureToggle|null $toggle */
        $toggle = static::query()->where('name', $name)->first();
        return $toggle ? $toggle->is_active : false;
    }
}
