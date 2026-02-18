<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReview>
 */
class ProductReviewFactory extends Factory
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
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(int1: 1, int2: 5),
            'title' => fake()->optional()->sentence(nbWords: 6),
            'comment' => fake()->optional()->sentence(nbWords: 12),
            'verified' => fake()->boolean(chanceOfGettingTrue: 70),
        ];
    }
}
