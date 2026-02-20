<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $long_description
 * @property string|null $brand
 * @property int $product_category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<ProductVariant> $variants
 * @property Collection<ProductBadge> $badges
 * @property Collection<Image> $images
 * @property ProductCategory $category
 * @property Collection<ProductReview> $reviews
 * @property Collection<ProductOffer> $offers
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'long_description',
        'brand',
        'product_category_id',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function badges(): HasMany
    {
        return $this->hasMany(ProductBadge::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function offers(): HasManyThrough
    {
        return $this->hasManyThrough(ProductOffer::class, ProductVariant::class, 'product_id', 'product_variant_id');
    }
}
