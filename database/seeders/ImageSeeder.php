<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = Banner::all();

        foreach ($banners as $banner) {
            $imageCount = random_int(min: 1, max: 3);

            for ($i = 0; $i < $imageCount; $i++) {
                Image::factory()->create([
                    'imageable_type' => Banner::class,
                    'imageable_id' => $banner->id,
                    'is_primary' => $i === $imageCount - 1, // Último é principal
                ]);
            }
        }
    }
}
