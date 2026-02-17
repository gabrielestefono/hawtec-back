<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $appends = [
        'current_price',
        'sale_price',
        'has_offer',
        'discount_percentage',
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'badge',
        'stock_quantity',
        'product_category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
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

        return $activeOffer ? $activeOffer->offer_price : null;
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

        return (int) round((($originalPrice - $salePrice) / $originalPrice) * 100);
    }

    public function activeOffer(): ?ProductOffer
    {
        if ($this->relationLoaded(key: 'offers')) {
            return $this->offers
                ->sortByDesc('starts_at')
                ->first(fn (ProductOffer $offer): bool => $offer->isActive());
        }

        return $this->offers()
            ->active()
            ->orderByDesc('starts_at')
            ->orderByDesc('id')
            ->first();
    }
}
