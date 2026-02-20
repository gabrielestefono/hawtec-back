<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property string $description
 * @property string $button_label
 * @property string $button_url
 * @property bool $is_active
 * @property int $sort
 * @property Collection<Image> $images
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Banner extends Model
{
    /** @use HasFactory<\Database\Factories\BannerFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'button_label',
        'button_url',
        'is_active',
        'sort',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function images(): MorphMany
    {
        return $this->morphMany(related: Image::class, name: 'imageable');
    }

    public function primaryImage()
    {
        return $this->images()->where(column: 'is_primary', operator: true)->latest(column: 'updated_at')->first()
            ?? $this->images()->latest(column: 'updated_at')->first();
    }
}
