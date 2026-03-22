<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $brand
 * @property string $model
 * @property string $processor
 * @property string|null $gpu
 * @property string $ram
 * @property string $storage
 * @property numeric $price
 * @property int $stock
 * @property string|null $description
 * @property string|null $image
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereGpu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereProcessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereRam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsedLaptop whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UsedLaptop extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'processor',
        'gpu',
        'ram',
        'storage',
        'price',
        'stock',
        'description',
        'image',
        'status',
    ];
}
