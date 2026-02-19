<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property ProductColor|null $color
 * @property StorageOption|null $storage
 * @property RamOption|null $ram
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
        return $this->belongsTo(Product::class);
    }

    public function colorOption(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'color_id');
    }

    public function storageOption(): BelongsTo
    {
        return $this->belongsTo(StorageOption::class, 'storage_id');
    }

    public function ramOption(): BelongsTo
    {
        return $this->belongsTo(RamOption::class, 'ram_id');
    }
}
