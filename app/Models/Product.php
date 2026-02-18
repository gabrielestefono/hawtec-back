<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $long_description
 * @property string $brand
 * @property string $sku
 * @property string $price
 * @property string|null $badge
 * @property int $stock_quantity
 * @property int $product_category_id
 * @property array $colors
 * @property array $specs
 * @property string $current_price
 * @property string|null $sale_price
 * @property bool $has_offer
 * @property int|null $discount_percentage
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<Image> $images
 * @property ProductCategory $category
 * @property Collection<ProductReview> $reviews
 * @property Collection<ProductOffer> $offers
 * @property string $current_price
 * @property string|null $sale_price
 * @property bool $has_offer
 * @property int|null $discount_percentage
 * @property ProductOffer|null $activeOffer
 * @property float $reviews_rating
 * @property int $reviews_count
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $appends = [
        'current_price',
        'sale_price',
        'has_offer',
        'discount_percentage',
        'reviews_rating',
        'reviews_count',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'brand',
        'sku',
        'price',
        'badge',
        'stock_quantity',
        'product_category_id',
        'colors',
        'specs',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'colors' => 'array',
            'specs' => 'array',
        ];
    }

    public function images(): MorphMany
    {
        return $this->morphMany(related: Image::class, name: 'imageable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(related: ProductCategory::class, foreignKey: 'product_category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(related: ProductReview::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(related: ProductOffer::class);
    }

    public function getCurrentPriceAttribute(): string
    {
        return $this->activeOffer()?->offer_price ?? $this->price;
    }

    public function getSalePriceAttribute(): ?string
    {
        $activeOffer = $this->activeOffer();

        return $activeOffer ? $activeOffer->offer_price : $this->price;
    }

    public function getHasOfferAttribute(): bool
    {
        return $this->activeOffer() !== null;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        $activeOffer = $this->activeOffer();

        if (! $activeOffer) {
            return null;
        }

        $originalPrice = (float) $this->price;
        $salePrice = (float) $activeOffer->offer_price;

        if ($originalPrice <= 0) {
            return null;
        }

        return (int) round(num: (($originalPrice - $salePrice) / $originalPrice) * 100);
    }

    public function activeOffer(): ProductOffer|null
    {
        if ($this->relationLoaded(key: 'offers')) {
            /**
             * @var Collection<ProductOffer> $offers
             */
            $offers = $this->offers;

            $offers = $offers->sortByDesc('starts_at');

            /**
             * @var ProductOffer|null $offer
             */
            $offer = $offers->first(callback: fn(ProductOffer $offer): bool => $offer->isActive());

            return $offer;
        }

        /**
         * @var HasMany<ProductOffer> $offers
         */
        $offers = $this->offers();

        /**
         * @var HasMany<ProductOffer> $offers
         */
        $offers = $offers->active();

        $offers = $offers->orderByDesc(column: 'starts_at');

        $offers = $offers->orderByDesc(column: 'id');

        /**
         * @var ProductOffer|null $offer
         */
        $offer = $offers->first();

        return $offer;
    }

    public function getReviewsRatingAttribute(): float
    {
        if ($this->relationLoaded(key: 'reviews')) {
            /**
             * @var Collection<ProductReview> $reviews
             */
            $reviews = $this->reviews;
        } else {
            /**
             * @var HasMany<ProductReview> $reviewsHasMany
             */
            $reviewsHasMany = $this->reviews();

            /**
             * @var Collection<ProductReview> $reviews
             */
            $reviews = $reviewsHasMany->get();
        }


        if ($reviews->isEmpty() || $avg = $reviews->avg(callback: 'rating') === null) {
            return 0;
        }

        return round(num: $avg, precision: 2);
    }

    public function getReviewsCountAttribute(): int
    {
        if ($this->relationLoaded(key: 'reviews')) {
            /**
             * @var Collection<ProductReview> $reviews
             */
            $reviews = $this->reviews;

            $count = $reviews->count();
            return $count;
        }

        return $this->reviews()->count();
    }
}
