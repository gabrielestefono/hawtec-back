<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::all()->each(function (Product $product): void {
            $variantCount = fake()->numberBetween(2, 5);
            for ($i = 0; $i < $variantCount; $i++) {
                $product->variants()->create(
                    ProductVariant::factory()->make()->toArray()
                );
            }
        });
    }
}
