<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<array-key, mixed> $content_blocks
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereContentBlocks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DynamicPage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DynamicPage extends Model
{
    protected $fillable = ['title', 'slug', 'content_blocks', 'is_published'];

    protected $casts = [
        'content_blocks' => 'array',
        'is_published' => 'boolean',
    ];
}
