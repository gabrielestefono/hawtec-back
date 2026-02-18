<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class TestProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::factory()
            ->has(ProductReview::factory()->count(5), 'reviews')
            ->has(Image::factory()->count(3), 'images')
            ->create();

        $this->command->info("Test product created: {$product->name} (slug: {$product->slug})");
    }
}
