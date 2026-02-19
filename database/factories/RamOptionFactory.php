<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RamOption>
 */
class RamOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rams = [
            ['name' => '4GB', 'value_gb' => 4],
            ['name' => '6GB', 'value_gb' => 6],
            ['name' => '8GB', 'value_gb' => 8],
            ['name' => '12GB', 'value_gb' => 12],
            ['name' => '16GB', 'value_gb' => 16],
            ['name' => '32GB', 'value_gb' => 32],
        ];

        $ram = fake()->randomElement($rams);

        return [
            'name' => $ram['name'],
            'value_gb' => $ram['value_gb'],
        ];
    }
}
