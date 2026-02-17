<?php

namespace App\Actions\Landing;

use App\Helpers\ImageHelper;
use App\Models\Banner;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

        $banners->map(callback: function (Banner $banner): Banner {
            $banner->images->map(callback: function ($image): void {
                $image->url = ImageHelper::getImageUrl(path: $image->path);
            });

            return $banner;
        });

        return $banners;
    }
}
