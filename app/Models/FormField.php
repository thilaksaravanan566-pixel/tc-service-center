<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $dynamic_form_id
 * @property string $label
 * @property string $name
 * @property string $type
 * @property array<array-key, mixed>|null $options
 * @property bool $is_required
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DynamicForm $form
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereDynamicFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormField extends Model
{
    protected $fillable = [
        'dynamic_form_id', 'label', 'name', 'type', 'options', 'is_required', 'order'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(DynamicForm::class, 'dynamic_form_id');
    }
}
