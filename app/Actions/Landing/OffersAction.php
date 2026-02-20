<?php

namespace App\Actions\Landing;

use App\Helpers\ProductHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OffersAction
{
    public function handle(): Collection
    {
        $now = now();

        $products = Product::query()
            ->whereHas(relation: 'variants.offers', callback: fn (Builder $query): Builder => $query->active())
            ->with(relations: ['images', 'variants' => fn (Builder $query): Builder => $query->with(['offers' => fn (Builder $query): Builder => $query->active()]), 'category'])
            ->withCount(relations: 'reviews')
            ->withAvg(relation: 'reviews', column: 'rating')
            ->get();

        return $products->map(callback: function (Product $product): Product {
            $product->reviews_rating = ProductHelper::getReviewAverageRating(product: $product);

            return $product;
        });
    }
}
