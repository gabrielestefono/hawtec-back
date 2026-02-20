<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_selectable
 * @property int $display_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<Spec> $specs
 */
class SpecType extends Model
{
    /** @use HasFactory<\Database\Factories\SpecTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'is_selectable',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_selectable' => 'boolean',
        ];
    }

    public function specs(): HasMany
    {
        return $this->hasMany(Spec::class);
    }
}
