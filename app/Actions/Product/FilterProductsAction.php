<?php

namespace App\Actions\Product;

use App\Http\Requests\Api\FilterProductsRequest;
use App\Models\ProductBadge;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class FilterProductsAction
{
    /**
     * Apply search filter.
     */
    protected function applySearch(Builder $query, ?string $search): void
    {
        if (! $search) {
            return;
        }

        $query->whereHas(relation: 'product', callback: function (Builder $query) use ($search): void {
            $query->where(column: 'name', operator: 'like', value: "%{$search}%")
                ->orWhere(column: 'description', operator: 'like', value: "%{$search}%");
        });
    }

    /**
     * Apply categories filter.
     *
     * @param  array<int>|null  $categories
     */
    protected function applyCategories(Builder $query, ?array $categories): void
    {
        if (! $categories || empty($categories)) {
            return;
        }

        $query->whereHas(relation: 'product', callback: fn (Builder $query): Builder => $query->whereIn(column: 'product_category_id', values: $categories));
    }

    /**
     * Apply price range filter.
     */
    protected function applyPriceRange(Builder $query, ?float $priceMin, ?float $priceMax): void
    {
        if ($priceMin !== null || $priceMax !== null) {
            $now = now();

            $offerPriceSubquery = 'SELECT offer_price FROM product_offers WHERE product_offers.product_variant_id = product_variants.id AND (product_offers.starts_at IS NULL OR product_offers.starts_at <= ?) AND (product_offers.ends_at IS NULL OR product_offers.ends_at >= ?) AND (product_offers.quantity_limit IS NULL OR product_offers.quantity_sold < product_offers.quantity_limit) ORDER BY product_offers.id DESC LIMIT 1';
            $effectivePriceExpression = "COALESCE(({$offerPriceSubquery}), product_variants.price)";

            if ($priceMin !== null) {
                $query->whereRaw(sql: "{$effectivePriceExpression} >= ?", bindings: [$now, $now, $priceMin]);
            }
            if ($priceMax !== null) {
                $query->whereRaw(sql: "{$effectivePriceExpression} <= ?", bindings: [$now, $now, $priceMax]);
            }
        }
    }

    /**
     * Apply ratings filter.
     *
     * @param  array<int>|null  $ratings
     */
    protected function applyRatings(Builder $query, ?array $ratings): void
    {
        if (! $ratings || empty($ratings)) {
            return;
        }

        $ratingsPlaceholders = implode(separator: ',', array: $ratings);

        $query->whereRaw(
            sql: "FLOOR(COALESCE((SELECT AVG(rating) FROM product_reviews WHERE product_reviews.product_variant_id = product_variants.id), 0)) IN ({$ratingsPlaceholders})"
        );
    }

    /**
     * Apply only offers filter.
     */
    protected function applyOnlyOffers(Builder $query, ?bool $onlyOffers): void
    {
        if (! $onlyOffers) {
            return;
        }

        $query->whereHas(relation: 'offers', callback: fn (Builder $query): mixed => $query->active());
    }

    /**
     * Apply in stock filter.
     */
    protected function applyInStock(Builder $query, ?bool $inStock): void
    {
        if (! $inStock) {
            return;
        }

        $query->where(column: 'stock_quantity', operator: '>', value: 0);
    }

    /**
     * Apply sorting.
     */
    protected function applySorting(Builder $query, string $sortBy): void
    {
        match ($sortBy) {
            'newest' => $this->sortByNewest(query: $query),
            'biggest_discount' => $this->sortByBiggestDiscount(query: $query),
            'most_reviewed' => $this->sortByMostReviewed(query: $query),
            'best_rating' => $this->sortByBestRating(query: $query),
            'highest_price' => $this->sortByHighestPrice(query: $query),
            'lowest_price' => $this->sortByLowestPrice(query: $query),
            'most_relevant' => $this->sortByMostRelevant(query: $query),
            default => $this->sortByMostRelevant(query: $query),
        };
    }

    /**
     * Sort by newest (badge = 'Lançamento' first, then by created_at desc).
     */
    protected function sortByNewest(Builder $query): void
    {
        $query->orderByRaw(sql: '(SELECT products.created_at FROM products WHERE products.id = product_variants.product_id) DESC')
            ->orderBy(column: 'product_variants.created_at', direction: 'desc');
    }

    /**
     * Sort by biggest discount.
     */
    protected function sortByBiggestDiscount(Builder $query): void
    {
        $now = now();

        $query->orderByRaw(
            sql: 'COALESCE((SELECT MAX((product_variants.price - product_offers.offer_price) / product_variants.price * 100) FROM product_offers WHERE product_offers.product_variant_id = product_variants.id AND (product_offers.starts_at IS NULL OR product_offers.starts_at <= ?) AND (product_offers.ends_at IS NULL OR product_offers.ends_at >= ?) AND (product_offers.quantity_limit IS NULL OR product_offers.quantity_sold < product_offers.quantity_limit)), 0) DESC',
            bindings: [$now, $now]
        )
            ->orderByRaw(sql: '(SELECT products.created_at FROM products WHERE products.id = product_variants.product_id) DESC');
    }

    /**
     * Sort by most reviewed.
     */
    protected function sortByMostReviewed(Builder $query): void
    {
        $query->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderByRaw(sql: '(SELECT products.created_at FROM products WHERE products.id = product_variants.product_id) DESC');
    }

    /**
     * Sort by best rating.
     */
    protected function sortByBestRating(Builder $query): void
    {
        $query->orderBy(column: 'reviews_avg_rating', direction: 'desc')
            ->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderByRaw(sql: '(SELECT products.created_at FROM products WHERE products.id = product_variants.product_id) DESC');
    }

    /**
     * Sort by highest price (considering active offers).
     */
    protected function sortByHighestPrice(Builder $query): void
    {
        $now = now();

        $query->orderByRaw(
            sql: 'COALESCE((SELECT COALESCE((SELECT offer_price FROM product_offers WHERE product_offers.product_variant_id = product_variants.id AND (product_offers.starts_at IS NULL OR product_offers.starts_at <= ?) AND (product_offers.ends_at IS NULL OR product_offers.ends_at >= ?) AND (product_offers.quantity_limit IS NULL OR product_offers.quantity_sold < product_offers.quantity_limit) ORDER BY product_offers.id DESC LIMIT 1), product_variants.price)), 0) DESC',
            bindings: [$now, $now]
        );
    }

    /**
     * Sort by lowest price (considering active offers).
     */
    protected function sortByLowestPrice(Builder $query): void
    {
        $now = now();

        $query->orderByRaw(
            sql: 'COALESCE((SELECT COALESCE((SELECT offer_price FROM product_offers WHERE product_offers.product_variant_id = product_variants.id AND (product_offers.starts_at IS NULL OR product_offers.starts_at <= ?) AND (product_offers.ends_at IS NULL OR product_offers.ends_at >= ?) AND (product_offers.quantity_limit IS NULL OR product_offers.quantity_sold < product_offers.quantity_limit) ORDER BY product_offers.id DESC LIMIT 1), product_variants.price)), 0) ASC',
            bindings: [$now, $now]
        );
    }

    /**
     * Sort by most relevant (combination of factors).
     *
     * Critério: produtos com ofertas ativas, melhor avaliados, mais reviews e mais recentes.
     */
    protected function sortByMostRelevant(Builder $query): void
    {
        $now = now();

        $query->orderByRaw(
            sql: 'CASE WHEN EXISTS (SELECT 1 FROM product_offers WHERE product_offers.product_variant_id = product_variants.id AND (product_offers.starts_at IS NULL OR product_offers.starts_at <= ?) AND (product_offers.ends_at IS NULL OR product_offers.ends_at >= ?) AND (product_offers.quantity_limit IS NULL OR product_offers.quantity_sold < product_offers.quantity_limit)) THEN 1 ELSE 0 END DESC',
            bindings: [$now, $now]
        )
            ->orderBy(column: 'reviews_avg_rating', direction: 'desc')
            ->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderByRaw(sql: '(SELECT products.created_at FROM products WHERE products.id = product_variants.product_id) DESC');
    }

    public function handle(FilterProductsRequest $request): JsonResponse
    {
        /**
         * @var array{
         *      search?: string|null,
         *      categories?: array<int>|null,
         *      price_min?: float|null,
         *      price_max?: float|null,
         *      ratings?: array<int>|null,
         *      only_offers?: bool|null,
         *      in_stock?: bool|null,
         *      sort_by?: string|null,
         *      per_page?: int|null,
         *      page?: int|null,
         * } $filters
         */
        $filters = $request->validated();

        $query = ProductVariant::query()
            ->with(
                relations: [
                    'product',
                    'product.images',
                    'product.badges',
                    'offers',
                ]
            )
            ->withCount(relations: 'reviews')
            ->withAvg(relation: 'reviews', column: 'rating');

        // Aplicar filtros
        $this->applySearch(query: $query, search: $filters['search'] ?? null);
        $this->applyCategories(query: $query, categories: $filters['categories'] ?? null);
        $this->applyPriceRange(query: $query, priceMin: $filters['price_min'] ?? null, priceMax: $filters['price_max'] ?? null);
        $this->applyRatings(query: $query, ratings: $filters['ratings'] ?? null);
        $this->applyOnlyOffers(query: $query, onlyOffers: $filters['only_offers'] ?? null);
        $this->applyInStock(query: $query, inStock: $filters['in_stock'] ?? null);

        // Aplicar ordenação
        $this->applySorting(query: $query, sortBy: $filters['sort_by'] ?? 'most_relevant');

        // Paginação
        $perPage = $filters['per_page'] ?? 15;

        $paginatedQuery = $query->paginate(perPage: $perPage);

        $paginatedQuery->getCollection()->transform(callback: function (ProductVariant $variant): ProductVariant {
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

        return response()->json(data: [
            'message' => 'Produtos recuperados com sucesso',
            'status' => 'success',
            'data' => [
                'products' => $paginatedQuery,
            ],
        ]);
    }
}
