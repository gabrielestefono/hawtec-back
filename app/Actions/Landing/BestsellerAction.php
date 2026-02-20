<?php

namespace App\Actions\Landing;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class BestsellerAction
{
    public function handle(): Collection
    {
        // Retorna variantes ordenadas por vendas (quando houver dados de pedidos)
        return ProductVariant::query()
            ->with(
                relations: [
                    'product' => function ($query): void {
                        $query->with(['images', 'category', 'badges']);
                    },
                    'offers',
                ]
            )
            ->orderByRaw('RAND()')
            ->limit(8)
            ->get()
            ->map(
                callback: function (ProductVariant $variant): ProductVariant {
                    $product = $variant->product;

                    $primaryImage = $product->images()
                        ->where(column: 'is_primary', operator: true)
                        ->first()
                        ?? $product->images()->first();

                    $firstActiveOffer = $variant->offers()->first();

                    if ($firstActiveOffer) {
                        $badge = new \App\Models\ProductBadge(
                            attributes: [
                                'product_id' => $product->id,
                                'badge_type' => 'discount',
                                'valid_from' => null,
                                'valid_until' => null,
                            ]
                        );
                    } else {
                        $badge = $product->badges()
                            ->where(
                                column: function ($query): void {
                                    $query
                                        ->whereNull(columns: 'valid_from')
                                        ->orWhere(column: 'valid_from', operator: '<=', value: now());
                                }
                            )
                            ->where(
                                column: function ($query): void {
                                    $query
                                        ->whereNull(columns: 'valid_until')
                                        ->orWhere(column: 'valid_until', operator: '>=', value: now());
                                }
                            )
                            ->first();
                    }

                    $variant->product_name = $product->name;
                    $variant->product_brand = $product->brand;
                    $variant->product_image = $primaryImage;
                    $variant->badge = $badge;
                    $variant->offer = $firstActiveOffer;

                    unset($variant->product);

                    return $variant;
                }
            );
    }
}
