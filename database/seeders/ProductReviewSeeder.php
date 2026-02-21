<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        Product::all()->each(function (Product $product) use ($users): void {
            $reviewCount = fake()->numberBetween(2, min(4, $users->count()));
            $selectedUsers = $users->random($reviewCount);
            
            foreach ($selectedUsers as $user) {
                // Get a random variant from this product
                $variant = $product->variants()->inRandomOrder()->first();
                
                if ($variant) {
                    $product->reviews()->create([
                        'user_id' => $user->id,
                        'product_variant_id' => $variant->id,
                        'rating' => fake()->numberBetween(1, 5),
                        'title' => fake()->sentence(6),
                        'comment' => fake()->paragraph(),
                        'verified' => fake()->boolean(70),
                    ]);
                }
            }
        });
    }
}
