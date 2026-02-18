<?php

namespace App\Actions\Landing;

use App\Helpers\ProductHelper;
use App\Models\Product;
use Illuminate\Support\Collection;

class ProductAction
{
    public function handle(): Collection
    {
        $products = Product::query()
            ->where(column: 'stock_quantity', operator: '>', value: 0)
            ->with(relations: ['images', 'offers', 'category'])
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
