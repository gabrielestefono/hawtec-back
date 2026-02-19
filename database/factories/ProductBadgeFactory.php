<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductBadge>
 */
class ProductBadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'badge_type' => fake()->randomElement(['new', 'promotion', 'popular', 'bestseller', 'limited', 'exclusive']),
            'valid_from' => fake()->optional()->dateTimeThisMonth(),
            'valid_until' => fake()->optional()->dateTimeThisMonth('+1 month'),
        ];
    }
}
