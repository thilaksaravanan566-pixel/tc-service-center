<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FormField> $fields
 * @property-read int|null $fields_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicForm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DynamicForm extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }
}
