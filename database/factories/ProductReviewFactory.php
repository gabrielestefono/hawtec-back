<?php

namespace Database\Factories;

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
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence(6),
            'comment' => $this->faker->paragraph(),
            'verified' => $this->faker->boolean(70),
        ];
    }
}
