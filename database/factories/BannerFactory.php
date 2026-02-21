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
            'title' => $this->faker->sentence(),
            'subtitle' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'button_label' => $this->faker->word(),
            'button_url' => $this->faker->url(),
            'is_active' => true,
            'sort' => $this->faker->numberBetween(0, 10),
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ];
    }
}
