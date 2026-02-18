<?php

namespace App\Http\Resources;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rating = 0;
        if ($this->relationLoaded('reviews')) {
            $rating = round($this->reviews->avg('rating'), 2) ?: 0;
        } else {
            $rating = $this->reviews()->avg('rating') ?: 0;
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'longDescription' => $this->long_description,
            'price' => (float) $this->current_price,
            'originalPrice' => (float) $this->price,
            'discountPercent' => $this->discount_percentage,
            'images' => $this->images->map(fn ($image) => ImageHelper::getImageUrl($image->path))->toArray(),
            'rating' => (float) $rating,
            'reviewCount' => $this->relationLoaded('reviews') ? $this->reviews->count() : $this->reviews()->count(),
            'badge' => $this->badge,
            'category' => $this->category?->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'inStock' => $this->stock_quantity > 0,
            'stockCount' => $this->stock_quantity,
            'colors' => $this->colors ?? [],
            'specs' => $this->specs ?? [],
            'reviews' => ReviewResource::collection($this->reviews),
        ];
    }
}
