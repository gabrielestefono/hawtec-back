<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $product_variant_id
 * @property string $offer_price
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property int|null $quantity_limit
 * @property int $quantity_sold
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductOffer extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOfferFactory> */
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'offer_price',
        'starts_at',
        'ends_at',
        'quantity_limit',
        'quantity_sold',
    ];

    protected function casts(): array
    {
        return [
            'offer_price' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'quantity_limit' => 'integer',
            'quantity_sold' => 'integer',
        ];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function scopeActive(Builder $query, ?CarbonInterface $at = null): Builder
    {
        $at ??= now();

        return $query
            ->where(function (Builder $query) use ($at): void {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $at);
            })
            ->where(function (Builder $query) use ($at): void {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $at);
            })
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('quantity_limit')
                    ->orWhereColumn('quantity_sold', '<', 'quantity_limit');
            });
    }

    public function isActive(?CarbonInterface $at = null): bool
    {
        $at ??= now();

        if ($this->starts_at !== null && $this->starts_at->isAfter($at)) {
            return false;
        }

        if ($this->ends_at !== null && $this->ends_at->isBefore($at)) {
            return false;
        }

        if ($this->quantity_limit !== null && $this->quantity_sold >= $this->quantity_limit) {
            return false;
        }

        return true;
    }
}
