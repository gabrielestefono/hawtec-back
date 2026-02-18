<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $icon
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'slug',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(related: Product::class, foreignKey: 'product_category_id');
    }
}
