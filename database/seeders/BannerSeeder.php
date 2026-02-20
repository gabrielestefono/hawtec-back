<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::factory()
            ->count(count: 5)
            ->create(attributes: [
                'is_active' => true,
                'starts_at' => now()->subDays(days: 10),
                'ends_at' => now()->addDays(days: 30),
            ]);

        Banner::factory()
            ->count(count: 2)
            ->create(attributes: [
                'is_active' => true,
                'starts_at' => now()->addDays(days: 5),
                'ends_at' => now()->addDays(days: 45),
            ]);

        Banner::factory()
            ->count(count: 2)
            ->create(attributes: [
                'is_active' => false,
            ]);
    }
}
