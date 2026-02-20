<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class ShowProductAction
{
    public function handle(Product $product): JsonResponse
    {
        $product->loadMissing([
            'images',
            'category',
            'variants' => fn (Builder $query): Builder => $query->with([
                'colorOption',
                'storageOption',
                'ramOption',
                'offers' => fn (Builder $query): Builder => $query->active(),
            ]),
            'badges',
            'reviews.user',
        ]);

        return response()->json(data: $product->toArray(), status: 200);
    }
}
