<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::pluck('id')->toArray();

        Product::factory(50)->create([
            'product_category_id' => fn() => $categories[array_rand($categories)],
        ]);
    }
}
