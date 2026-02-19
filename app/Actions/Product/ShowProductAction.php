<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowProductAction
{
    public function handle(Product $product): JsonResponse
    {
        $product->loadMissing(['images', 'category', 'variants.colorOption', 'variants.storageOption', 'variants.ramOption', 'badges', 'reviews.user', 'offers']);

        return response()->json(data: $product->toArray(), status: 200);
    }
}
