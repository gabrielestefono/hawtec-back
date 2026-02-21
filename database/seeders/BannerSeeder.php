<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::factory(3)->create()->each(function (Banner $banner): void {
            $banner->images()->create([
                'path' => 'banners/logo.webp',
                'alt' => 'Banner ' . $banner->title,
                'sort' => 0,
                'is_primary' => true,
            ]);
        });
    }
}
