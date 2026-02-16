<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(nbWords: 3),
            'subtitle' => fake()->optional()->sentence(nbWords: 6),
            'description' => fake()->optional()->paragraph(),
            'button_label' => fake()->optional()->words(nb: 2, asText: true),
            'button_url' => fake()->optional()->url(),
            'is_active' => fake()->boolean(chanceOfGettingTrue: 80),
            'sort' => fake()->numberBetween(int1: 0, int2: 100),
            'starts_at' => fake()->optional()->dateTimeBetween(startDate: '-1 month', endDate: '+1 month'),
            'ends_at' => fake()->optional()->dateTimeBetween(startDate: '+1 month', endDate: '+3 months'),
        ];
    }
}
