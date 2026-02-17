<?php

namespace App\Actions\Landing;

use App\Enums\OrderStatus;
use App\Helpers\ImageHelper;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BestsellerAction
{
    public function handle(): Collection
    {
        $productIds = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', OrderStatus::Completed->value)
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->pluck('product_id');

        if ($productIds->isEmpty()) {
            return collect([]);
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->with(['images', 'offers', 'category'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get()
            ->sortBy(function ($product) use ($productIds) {
                return $productIds->search($product->id);
            });

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
        })->values();
    }
}
