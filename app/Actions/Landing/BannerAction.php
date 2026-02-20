<?php

namespace App\Actions\Landing;

use App\Models\Banner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BannerAction
{
    public function handle(): Collection
    {
        $now = now();

        $banners = Banner::query()
            ->where(column: 'is_active', operator: '=', value: true)
            ->where(column: function (Builder $query) use ($now): void {
                $query->whereNull(columns: 'starts_at')
                    ->orWhere(column: 'starts_at', operator: '<=', value: $now);
            })
            ->where(column: function (Builder $query) use ($now): void {
                $query->whereNull(columns: 'ends_at')
                    ->orWhere(column: 'ends_at', operator: '>=', value: $now);
            })
            ->orderBy(column: 'sort')
            ->with(relations: 'images')
            ->get();

        return $banners->filter(function (Banner $banner) {
            return $banner->images()->exists();
        })->map(function (Banner $banner) {
            $primaryImage = $banner->images()
                ->where(column: 'is_primary', operator: true)
                ->latest(column: 'updated_at')
                ->first()
                ?? $banner->images()->latest(column: 'updated_at')->first();

            $banner->setRelation(relation: 'image', value: $primaryImage);
            unset($banner->images);

            return $banner;
        })->values();
    }
}
