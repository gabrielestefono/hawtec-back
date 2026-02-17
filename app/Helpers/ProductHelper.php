<?php

namespace App\Helpers;

use App\Models\Product;

class ProductHelper
{
    public static function getDiscountPercentage(Product $product): int
    {
        if ($product->offers->isEmpty()) {
            return 0;
        }

        // $originalPrice = $product->price;
        return 0;
    }
}
