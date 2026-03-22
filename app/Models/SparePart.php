<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $brand
 * @property string|null $sku
 * @property string|null $category
 * @property string|null $color
 * @property string|null $description
 * @property float $price
 * @property int $stock
 * @property int|null $warranty_months
 * @property string|null $image_path
 * @property array|null $gallery_images
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read array $all_images
 * @property-read string $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductOrder> $productOrders
 * @property-read int|null $product_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WarrantyCertificate> $warranties
 * @property-read int|null $warranties_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart inCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart inStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereGalleryImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SparePart whereWarrantyMonths($value)
 * @mixin \Eloquent
 */
class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'sku',
        'category',
        'color',
        'description',
        'price',
        'stock',
        'warranty_months',
        'image_path',
        'gallery_images',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active'      => 'boolean',
        'price'          => 'decimal:2',
    ];

    /**
     * Scope: Only in-stock and active parts.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0)->where('is_active', true);
    }

    /**
     * Scope: filter by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 2);
    }

    /**
     * Get all gallery images including primary.
     */
    public function getAllImagesAttribute(): array
    {
        $images = $this->gallery_images ?? [];
        if ($this->image_path) {
            array_unshift($images, $this->image_path);
        }
        return array_unique($images);
    }

    /**
     * Logic: Reduce stock.
     */
    public function reduceStock(int $quantity = 1): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function productOrders(): HasMany
    {
        return $this->hasMany(ProductOrder::class);
    }

    public function warranties(): HasMany
    {
        return $this->hasMany(WarrantyCertificate::class);
    }
}