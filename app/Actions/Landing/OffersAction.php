<?php

namespace App\Actions\Landing;

use App\Models\ProductBadge;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class OffersAction
{
    public function handle(): Collection
    {
        return ProductVariant::query()
            ->whereHas('offers')
            ->with(['product', 'product.images', 'product.badges', 'offers'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get()
            ->map(function (ProductVariant $variant): ProductVariant {
                $now = now();
                $product = $variant->product;

                if ($product) {
                    $images = $product->images;

                    $primaryImage = $images
                        ->where('is_primary', true)
                        ->sortByDesc('id')
                        ->first();

                    $lastImage = $images
                        ->sortByDesc('id')
                        ->first();

                    $product->image = $primaryImage ?? $lastImage;

                    unset($product->images);
                }

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

                return $variant;
            });
    }
}
