<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $spec_type_id
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property SpecType $specType
 * @property \Illuminate\Database\Eloquent\Collection<ProductVariant> $variants
 */
class Spec extends Model
{
    /** @use HasFactory<\Database\Factories\SpecFactory> */
    use HasFactory;

    protected $fillable = [
        'spec_type_id',
        'value',
    ];

    public function specType(): BelongsTo
    {
        return $this->belongsTo(SpecType::class);
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_specs');
    }
}
