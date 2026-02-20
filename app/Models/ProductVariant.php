<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string|null $slug
 * @property string|null $variant_label
 * @property int|null $color_id
 * @property int|null $storage_id
 * @property int|null $ram_id
 * @property string|null $voltage
 * @property int|null $price_cents
 * @property float|null $price
 * @property int $stock_quantity
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Product $product
 * @property Collection<ProductOffer> $offers
 * @property ProductOffer|null $activeOffer
 * @property Collection<Spec> $specs
 */
class ProductVariant extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantFactory> */
    use HasFactory;

    protected $appends = ['price'];

    protected $hidden = ['price_cents'];

    protected $fillable = [
        'product_id',
        'sku',
        'slug',
        'variant_label',
        'color_id',
        'storage_id',
        'ram_id',
        'voltage',
        'price',
        'price_cents',
        'stock_quantity',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'stock_quantity' => 'integer',
        ];
    }

    public function setPriceAttribute(float|int|string|null $value): void
    {
        if ($value === null) {
            $this->attributes['price_cents'] = null;

            return;
        }

        $this->attributes['price_cents'] = (int) round($value * 100);
    }

    public function getPriceAttribute($value): ?float
    {
        $cents = $this->attributes['price_cents'] ?? $value;

        if ($cents === null) {
            return null;
        }

        return $cents / 100;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }

    public function specs(): BelongsToMany
    {
        return $this->belongsToMany(related: Spec::class, table: 'product_variant_specs');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(related: ProductOffer::class, foreignKey: 'product_variant_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(related: ProductReview::class, foreignKey: 'product_variant_id');
    }
}
