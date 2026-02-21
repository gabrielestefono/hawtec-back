<?php

namespace Database\Factories;

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
            'badge_type' => $this->faker->randomElement(['new', 'promotion', 'popular', 'bestseller', 'limited', 'exclusive']),
            'valid_from' => now(),
            'valid_until' => now()->addMonth(),
        ];
    }
}
