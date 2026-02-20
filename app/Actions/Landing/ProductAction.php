<?php

namespace App\Actions\Landing;

use App\Helpers\ProductHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductAction
{
    public function handle(): Collection
    {
        $products = Product::query()
            ->whereHas(relation: 'variants', callback: fn (Builder $query): Builder => $query->where(column: 'stock_quantity', operator: '>', value: 0))
            ->with(relations: ['images', 'variants' => fn (Builder $query): Builder => $query->with(['offers' => fn (Builder $query): Builder => $query->active()]), 'category'])
            ->withCount(relations: 'reviews')
            ->withAvg(relation: 'reviews', column: 'rating')
            ->get();

        return $products->map(callback: function (Product $product): Product {
            $product->reviews_rating = ProductHelper::getReviewAverageRating(product: $product);
            $product->reviews_avg_rating = (int) $product->reviews_avg_rating;

            return $product;
        });
    }
}
