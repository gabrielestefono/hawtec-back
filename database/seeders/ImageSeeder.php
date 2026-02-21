<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::all()->each(function (Banner $banner): void {
            $banner->images()->create([
                'path' => 'banners/logo.webp',
                'alt' => 'Banner ' . $banner->title,
                'sort' => 0,
                'is_primary' => true,
            ]);
        });

        Product::all()->each(function (Product $product): void {
            $imageCount = fake()->numberBetween(1, 4);
            for ($i = 0; $i < $imageCount; $i++) {
                $product->images()->create([
                    'path' => 'banners/logo.webp',
                    'alt' => $product->name,
                    'sort' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        });
    }
}
