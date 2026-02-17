<?php

namespace App\Actions\Landing;

use App\Helpers\ImageHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

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

        $products->map(callback: function (Product $product): Product {
            $product->images->map(callback: function ($image): void {
                $image->url = ImageHelper::getImageUrl(path: $image->path);
            });

            return $product;
        });

        return $products;
    }
}
