<?php

namespace Database\Factories;

use App\Models\SpecType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spec>
 */
class SpecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'spec_type_id' => SpecType::factory(),
            'value' => fake()->word(),
        ];
    }
}
