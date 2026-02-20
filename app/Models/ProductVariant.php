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
 * @property int|null $color_id
 * @property int|null $storage_id
 * @property int|null $ram_id
 * @property string|null $voltage
 * @property string $price
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

    protected $fillable = [
        'product_id',
        'sku',
        'color_id',
        'storage_id',
        'ram_id',
        'voltage',
        'price',
        'stock_quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
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
}
