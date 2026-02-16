<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imageable_type' => Banner::class,
            'imageable_id' => Banner::factory(),
            'path' => fake()->imageUrl(width: 1200, height: 600, category: 'business', randomize: true),
            'alt' => fake()->optional()->sentence(nbWords: 4),
            'sort' => fake()->numberBetween(int1: 0, int2: 20),
            'is_primary' => fake()->boolean(chanceOfGettingTrue: 20),
        ];
    }
}
