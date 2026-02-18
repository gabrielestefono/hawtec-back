<?php

namespace App\Actions\Landing;

use App\Enums\OrderStatus;
use App\Helpers\ImageHelper;
use App\Helpers\ProductHelper;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BestsellerAction
{
    public function handle(): Collection
    {
        $productIds = DB::table(table: 'order_items')
            ->join(table: 'orders', first: 'orders.id', operator: '=', second: 'order_items.order_id')
            ->where(column: 'orders.status', operator: OrderStatus::Completed->value)
            ->select('order_items.product_id', DB::raw(value: 'SUM(order_items.quantity) as total_sold'))
            ->groupBy(groups: 'order_items.product_id')
            ->orderByDesc(column: 'total_sold')
            ->limit(value: 8)
            ->pluck(column: 'product_id');

        if ($productIds->isEmpty()) {
            return collect(value: []);
        }

        $products = Product::query()
            ->whereIn(column: 'id', values: $productIds)
            ->with(relations: ['images', 'offers', 'category'])
            ->withCount(relations: 'reviews')
            ->withAvg(relation: 'reviews', column: 'rating')
            ->get()
            ->sortBy(callback: fn(Product $product): string|int|false => $productIds->search(value: $product->id));

        return $products->map(callback: function (Product $product): Product {
            $product->reviews_rating = ProductHelper::getReviewAverageRating(product: $product);
            return $product;
        });
    }
}
