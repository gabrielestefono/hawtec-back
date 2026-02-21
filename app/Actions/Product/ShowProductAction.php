<?php

namespace App\Actions\Product;

use App\Models\ProductBadge;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;

class ShowProductAction
{
    public function handle(ProductVariant $variant): JsonResponse
    {
        $variant->loadMissing(['offers', 'product.category', 'product.images', 'product.badges', 'reviews.user', 'specs.specType']);

        $variant->loadCount('reviews');
        $variant->loadAvg('reviews', 'rating');

        $now = now();
        $product = $variant->product;

        $offer = $variant->offers
            ->filter(function ($offer) use ($now): bool {
                $startsAt = $offer->starts_at;
                $endsAt = $offer->ends_at;

                $withinDateRange =
                    ($startsAt === null || $startsAt <= $now)
                    && ($endsAt === null || $endsAt >= $now);

                $hasQuantity =
                    $offer->quantity_limit === null
                    || $offer->quantity_sold < $offer->quantity_limit;

                return $withinDateRange && $hasQuantity;
            })
            ->sortByDesc('id')
            ->first();

        $variant->offer = $offer;

        if ($offer) {
            $badge = new ProductBadge([
                'product_id' => $product?->id,
                'badge_type' => 'discount',
                'valid_from' => null,
                'valid_until' => null,
            ]);

            $price = $variant->price;
            $offerPrice = $offer->offer_price;

            $discountPercentage = null;

            if ($price !== null && $price > 0 && $offerPrice !== null) {
                $discount = (($price - $offerPrice) / $price) * 100;
                $discountPercentage = max(0, (int) round($discount));
            }

            $badge->discountPercentage = $discountPercentage;
        } else {
            $badge = $product?->badges
                ->filter(function (ProductBadge $badge) use ($now): bool {
                    $validFrom = $badge->valid_from;
                    $validUntil = $badge->valid_until;

                    $withinDateRange =
                        ($validFrom === null || $validFrom <= $now)
                        && ($validUntil === null || $validUntil >= $now);

                    return $withinDateRange;
                })
                ->sortByDesc('id')
                ->first();
        }

        $variant->badge = $badge;

        $variant->reviews_avg_rating = $variant->reviews_avg_rating
            ? (int) round($variant->reviews_avg_rating)
            : null;

        return response()->json(data: $variant->toArray(), status: 200);
    }
}
