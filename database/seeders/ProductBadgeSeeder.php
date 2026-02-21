<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductBadge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badgeTypes = ['new', 'promotion', 'popular', 'bestseller', 'limited', 'exclusive'];

        Product::all()->each(function (Product $product) use ($badgeTypes): void {
            // Every product gets 1-2 badges
            $selectedTypes = fake()->randomElements($badgeTypes, fake()->numberBetween(1, 2));
            
            foreach ($selectedTypes as $type) {
                $product->badges()->create([
                    'badge_type' => $type,
                    'valid_from' => now(),
                    'valid_until' => now()->addMonth(),
                ]);
            }
        });
    }
}
