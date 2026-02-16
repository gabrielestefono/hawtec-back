<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(nb: 3, asText: true),
            'description' => fake()->optional()->sentence(nbWords: 6),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 99, max: 9999),
            'badge' => fake()->optional()->randomElement(array: ['new', 'sale', 'hot']),
            'stock_quantity' => fake()->numberBetween(int1: 0, int2: 200),
        ];
    }
}
