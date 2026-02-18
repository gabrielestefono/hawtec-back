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
            ->whereHas(relation: 'offers', callback: function (Builder $query) use ($now): void {
                $query
                    ->where(column: function (Builder $q) use ($now): void {
                        $q->whereNull(columns: 'starts_at')
                            ->orWhere(column: 'starts_at', operator: '<=', value: $now);
                    })
                    ->where(column: function (Builder $q) use ($now): void {
                        $q->whereNull(columns: 'ends_at')
                            ->orWhere(column: 'ends_at', operator: '>=', value: $now);
                    });
            })
            ->with(relations: ['images', 'offers', 'category'])
            ->withCount(relations: 'reviews')
            ->withAvg(relation: 'reviews', column: 'rating')
            ->get();

        return $products->map(callback: function (Product $product): Product {
            $product->reviews_rating = ProductHelper::getReviewAverageRating(product: $product);
            return $product;
        });
    }
}
