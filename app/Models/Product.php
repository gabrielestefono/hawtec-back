<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $appends = [
        'current_price',
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'badge',
        'stock_quantity',
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
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function getCurrentPriceAttribute(): string
    {
        return $this->activeOffer()?->offer_price ?? $this->price;
    }

    public function activeOffer(): ?ProductOffer
    {
        if ($this->relationLoaded('offers')) {
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
