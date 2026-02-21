<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductOffer>
 */
class ProductOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'offer_price' => $this->faker->numberBetween(500, 30000),
            'starts_at' => now(),
            'ends_at' => now()->addWeeks(2),
            'quantity_limit' => $this->faker->numberBetween(10, 100),
            'quantity_sold' => 0,
        ];
    }
}
