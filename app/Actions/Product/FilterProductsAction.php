<?php

namespace App\Actions\Product;

use App\Helpers\ImageHelper;
use App\Http\Requests\Api\FilterProductsRequest;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        $query->where(function (Builder $query) use ($search): void {
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

        $query->whereIn(column: 'product_category_id', values: $categories);
    }

    /**
     * Apply price range filter.
     */
    protected function applyPriceRange(Builder $query, ?float $priceMin, ?float $priceMax): void
    {
        // if ($priceMin !== null) {
        //     // Considera o preço original do produto
        //     $query->where(column: function (Builder $query) use ($priceMin): void {
        //         // Produtos sem oferta
        //         $query->where(column: function (Builder $q) use ($priceMin): void {
        //             $q->whereDoesntHave(relation: 'offers', callback: fn(Builder $q): mixed => $q->active())
        //                 ->where(column: 'price', operator: '>=', value: $priceMin);
        //         })
        //             // Produtos com oferta
        //             ->orWhereHas(relation: 'offers', callback: function (Builder $q) use ($priceMin): void {
        //                 $q->active()->where('offer_price', '>=', $priceMin);
        //             });
        //     });
        // }

        // if ($priceMax !== null) {
        //     $query->where(column: function (Builder $query) use ($priceMax): void {
        //         // Produtos sem oferta
        //         $query->where(column: function (Builder $q) use ($priceMax): void {
        //             $q->whereDoesntHave(relation: 'offers', callback: fn(Builder $q): mixed => $q->active())
        //                 ->where(column: 'price', operator: '<=', value: $priceMax);
        //         })
        //             // Produtos com oferta
        //             ->orWhereHas(relation: 'offers', callback: function (Builder $q) use ($priceMax): void {
        //                 $q->active()->where('offer_price', '<=', $priceMax);
        //             });
        //     });
        // }
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

        $query->whereHas(relation: 'reviews', callback: function (Builder $query) use ($ratings): void {
            $query->selectRaw(expression: 'product_id, AVG(rating) as avg_rating')
                ->groupBy(groups: 'product_id')
                ->havingRaw(sql: 'FLOOR(AVG(rating)) IN (' . implode(separator: ',', array: $ratings) . ')');
        });
    }

    /**
     * Apply only offers filter.
     */
    protected function applyOnlyOffers(Builder $query, ?bool $onlyOffers): void
    {
        if (! $onlyOffers) {
            return;
        }

        $query->whereHas(relation: 'offers', callback: fn(Builder $query): mixed => $query->active());
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
        $query->orderByRaw(sql: "CASE WHEN badge = 'Lançamento' THEN 0 ELSE 1 END")
            ->orderBy(column: 'created_at', direction: 'desc');
    }

    /**
     * Sort by biggest discount.
     */
    protected function sortByBiggestDiscount(Builder $query): void
    {
        // Produtos com ofertas ativas primeiro, ordenados por % de desconto
        $query->leftJoin(table: 'product_offers', first: function ($join): void {
            $join->on('products.id', '=', 'product_offers.product_id')
                ->where(function ($query): void {
                    $query->whereNull('product_offers.starts_at')
                        ->orWhere('product_offers.starts_at', '<=', now());
                })
                ->where(function ($query): void {
                    $query->whereNull('product_offers.ends_at')
                        ->orWhere('product_offers.ends_at', '>=', now());
                });
        })
            ->selectRaw(expression: 'products.*, 
                CASE 
                    WHEN product_offers.offer_price IS NOT NULL 
                    THEN ((products.price - product_offers.offer_price) / products.price * 100) 
                    ELSE 0 
                END as discount_percentage')
            ->orderBy(column: 'discount_percentage', direction: 'desc')
            ->orderBy(column: 'products.created_at', direction: 'desc');
    }

    /**
     * Sort by most reviewed.
     */
    protected function sortByMostReviewed(Builder $query): void
    {
        $query->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderBy(column: 'created_at', direction: 'desc');
    }

    /**
     * Sort by best rating.
     */
    protected function sortByBestRating(Builder $query): void
    {
        $query->orderBy(column: 'reviews_avg_rating', direction: 'desc')
            ->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderBy(column: 'created_at', direction: 'desc');
    }

    /**
     * Sort by highest price (considering active offers).
     */
    protected function sortByHighestPrice(Builder $query): void
    {
        $query->leftJoin(table: 'product_offers', first: function ($join): void {
            $join->on('products.id', '=', 'product_offers.product_id')
                ->where(function ($query): void {
                    $query->whereNull('product_offers.starts_at')
                        ->orWhere('product_offers.starts_at', '<=', now());
                })
                ->where(function ($query): void {
                    $query->whereNull('product_offers.ends_at')
                        ->orWhere('product_offers.ends_at', '>=', now());
                });
        })
            ->selectRaw(expression: 'products.*, 
                COALESCE(product_offers.offer_price, products.price) as current_price')
            ->orderBy(column: 'current_price', direction: 'desc');
    }

    /**
     * Sort by lowest price (considering active offers).
     */
    protected function sortByLowestPrice(Builder $query): void
    {
        $query->leftJoin(table: 'product_offers', first: function ($join): void {
            $join->on('products.id', '=', 'product_offers.product_id')
                ->where(function ($query): void {
                    $query->whereNull('product_offers.starts_at')
                        ->orWhere('product_offers.starts_at', '<=', now());
                })
                ->where(function ($query): void {
                    $query->whereNull('product_offers.ends_at')
                        ->orWhere('product_offers.ends_at', '>=', now());
                });
        })
            ->selectRaw(expression: 'products.*, 
                COALESCE(product_offers.offer_price, products.price) as current_price')
            ->orderBy(column: 'current_price', direction: 'asc');
    }

    /**
     * Sort by most relevant (combination of factors).
     *
     * Critério: produtos com ofertas ativas, melhor avaliados, mais reviews e mais recentes.
     */
    protected function sortByMostRelevant(Builder $query): void
    {
        $query->leftJoin(table: 'product_offers', first: function ($join): void {
            $join->on('products.id', '=', 'product_offers.product_id')
                ->where(function ($query): void {
                    $query->whereNull('product_offers.starts_at')
                        ->orWhere('product_offers.starts_at', '<=', now());
                })
                ->where(function ($query): void {
                    $query->whereNull('product_offers.ends_at')
                        ->orWhere('product_offers.ends_at', '>=', now());
                });
        })
            ->selectRaw(expression: 'products.*, 
                (CASE WHEN product_offers.id IS NOT NULL THEN 1 ELSE 0 END) as has_active_offer')
            ->orderBy(column: 'has_active_offer', direction: 'desc')
            ->orderBy(column: 'reviews_avg_rating', direction: 'desc')
            ->orderBy(column: 'reviews_count', direction: 'desc')
            ->orderBy(column: 'products.created_at', direction: 'desc');
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

        $query = Product::query()
            ->with(
                relations: [
                    'category:id,name',
                    'images',
                    'offers' => fn(HasMany $query): mixed => $query->active(),
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

        $paginatedQuery->getCollection()->transform(callback: function (Product $product): Product {
            $product->images->map(callback: function (Image $image): Image {
                $image->url = ImageHelper::getImageUrl(path: $image->path);

                return $image;
            });
            $product->reviews_avg_rating = $product->reviews_avg_rating ? round(num: $product->reviews_avg_rating, precision: 1) : null;

            return $product;
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
