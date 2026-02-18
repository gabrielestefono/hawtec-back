<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property string $title
 * @property string $comment
 * @property bool $verified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductReview extends Model
{
    /** @use HasFactory<\Database\Factories\ProductReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'verified',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'verified' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
