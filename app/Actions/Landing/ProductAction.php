<?php

namespace App\Actions\Landing;

use App\Models\Product;
use App\Models\ProductBadge;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ProductAction
{
    public function handle(): Collection
    {
        return Product::query()
            ->with(
                relations: [
                    'images',
                    'variants' => function (HasMany $query): void {
                        $query->with(relations: ['offers']);
                    },
                    'category',
                    'badges',
                ]
            )
            ->get()
            ->map(
                callback: function (Product $product): Product {
                    $primaryImage = $product->images()
                        ->where(column: 'is_primary', operator: true)
                        ->first()
                        ?? $product->images()->first();

                    $product->setRelation(relation: 'image', value: $primaryImage);
                    unset($product->images);

                    // Pegar apenas a primeira oferta ativa de cada variante
                    $hasActiveOffer = false;
                    foreach ($product->variants as $variant) {
                        /**
                         * @var HasMany $offers
                         */
                        $offers = $variant->offers();
                        $firstActiveOffer = $offers->first();
                        $variant->setRelation(relation: 'offer', value: $firstActiveOffer);
                        unset($variant->offers);

                        if ($firstActiveOffer) {
                            $hasActiveOffer = true;
                        }
                    }

                    // Lógica de badge
                    if ($hasActiveOffer) {
                        // Criar badge de desconto dinamicamente
                        $discountBadge = new ProductBadge(
                            attributes: [
                                'product_id' => $product->id,
                                'badge_type' => 'discount',
                                'valid_from' => null,
                                'valid_until' => null,
                            ]
                        );
                        $product->setRelation(relation: 'badge', value: $discountBadge);
                    } else {
                        // Buscar primeira badge ativa
                        $activeBadge = $product->badges()
                            ->where(
                                column: function (HasMany $query): void {
                                    $query
                                        ->whereNull(columns: 'valid_from')
                                        ->orWhere(column: 'valid_from', operator: '<=', value: now());
                                }
                            )
                            ->where(
                                column: function (HasMany $query): void {
                                    $query
                                        ->whereNull(columns: 'valid_until')
                                        ->orWhere(column: 'valid_until', operator: '>=', value: now());
                                }
                            )
                            ->first();

                        $product->setRelation(relation: 'badge', value: $activeBadge);
                    }

                    unset($product->badges);

                    return $product;
                }
            );
    }
}
