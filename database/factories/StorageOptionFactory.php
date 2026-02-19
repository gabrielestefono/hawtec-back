<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StorageOption>
 */
class StorageOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storages = [
            ['name' => '64GB', 'value_gb' => 64],
            ['name' => '128GB', 'value_gb' => 128],
            ['name' => '256GB', 'value_gb' => 256],
            ['name' => '512GB', 'value_gb' => 512],
            ['name' => '1TB', 'value_gb' => 1024],
        ];

        $storage = fake()->randomElement($storages);

        return [
            'name' => $storage['name'],
            'value_gb' => $storage['value_gb'],
        ];
    }
}
