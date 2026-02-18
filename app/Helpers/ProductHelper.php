<?php

namespace App\Helpers;

use App\Models\Product;

class ProductHelper
{
    public static function getReviewAverageRating(Product $product): float
    {
        $reviews = $product->reviews;

        if ($reviews->isEmpty()) {
            return 0;
        }

        return $reviews->avg(callback: 'rating') ?? 0;
    }
}
