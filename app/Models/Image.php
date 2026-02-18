<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $path
 * @property string $alt
 * @property int $sort
 * @property bool $is_primary
 * @property string $imageable_type
 * @property int $imageable_id
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = [
        'path',
        'alt',
        'sort',
        'is_primary',
    ];

    protected $appends = ['url'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn(): string => ImageHelper::getImageUrl(path: $this->path),
        );
    }
}
