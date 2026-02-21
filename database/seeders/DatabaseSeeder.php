<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 random users
        User::factory(3)->create();

        // Create test user
        User::factory()->create([
            'email' => 'teste@teste.com',
        ]);

        // Call banner seeder
        $this->call(BannerSeeder::class);

        // Call product category seeder
        $this->call(ProductCategorySeeder::class);

        // Call product seeder
        $this->call(ProductSeeder::class);

        // Call product variant seeder
        $this->call(ProductVariantSeeder::class);

        // Call spec type seeder
        $this->call(SpecTypeSeeder::class);

        // Call spec seeder
        $this->call(SpecSeeder::class);

        // Call product offer seeder
        $this->call(ProductOfferSeeder::class);

        // Call product badge seeder
        $this->call(ProductBadgeSeeder::class);

        // Call product review seeder
        $this->call(ProductReviewSeeder::class);

        // Call image seeder
        $this->call(ImageSeeder::class);
    }
}
