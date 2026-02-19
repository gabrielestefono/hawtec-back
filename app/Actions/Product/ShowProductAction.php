<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowProductAction
{
    public function handle(Product $product): JsonResponse
    {
        $product->loadMissing(['images', 'category', 'reviews.user', 'colors', 'specs']);

        return response()->json(data: $product->toArray(), status: 200);
    }
}
