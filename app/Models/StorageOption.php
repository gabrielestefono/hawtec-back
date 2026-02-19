<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StorageOption extends Model
{
    /** @use HasFactory<\Database\Factories\StorageOptionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'value_gb',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'storage_id');
    }
}
