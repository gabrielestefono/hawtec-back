<?php

namespace App\Actions\Landing;

use App\Helpers\ImageHelper;
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

        return $products->map(callback: function (Product $product): array {
            $product->images->map(callback: function ($image): void {
                $image->url = ImageHelper::getImageUrl(path: $image->path);
            });

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'has_offer' => $product->has_offer,
                'discount_percentage' => $product->discount_percentage,
                'badge' => $product->badge,
                'stock_quantity' => $product->stock_quantity,
                'images' => $product->images,
                'category' => $product->category,
                'reviews_count' => $product->reviews_count,
                'reviews_avg_rating' => $product->reviews_avg_rating ? round($product->reviews_avg_rating, 1) : null,
            ];
        });
    }
}
