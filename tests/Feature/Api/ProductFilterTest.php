<?php

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOffer;
use App\Models\ProductReview;
use App\Models\User;

test('can list products', function (): void {
    Product::factory()->count(5)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'products' => [
                    'data',
                    'current_page',
                    'per_page',
                    'total',
                ],
            ],
        ]);
});

test('can search products by name', function (): void {
    Product::factory()->create(['name' => 'Notebook Dell']);
    Product::factory()->create(['name' => 'Mouse Logitech']);

    $response = $this->getJson('/api/products?search=Dell');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(1);
    expect($response->json('data.products.data.0.name'))->toContain('Dell');
});

test('can filter products by category', function (): void {
    $category1 = ProductCategory::factory()->create(['name' => 'Eletrônicos']);
    $category2 = ProductCategory::factory()->create(['name' => 'Informática']);

    Product::factory()->count(3)->create(['product_category_id' => $category1->id]);
    Product::factory()->count(2)->create(['product_category_id' => $category2->id]);

    $response = $this->getJson("/api/products?categories[]={$category1->id}");

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(3);
});

test('can filter products by multiple categories', function (): void {
    $category1 = ProductCategory::factory()->create();
    $category2 = ProductCategory::factory()->create();

    Product::factory()->count(2)->create(['product_category_id' => $category1->id]);
    Product::factory()->count(3)->create(['product_category_id' => $category2->id]);

    $response = $this->getJson("/api/products?categories[]={$category1->id}&categories[]={$category2->id}");

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(5);
});

test('can filter products by price range', function (): void {
    Product::factory()->create(['price' => 100]);
    Product::factory()->create(['price' => 500]);
    Product::factory()->create(['price' => 1000]);

    $response = $this->getJson('/api/products?price_min=200&price_max=800');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(1);
});

test('can filter only products with offers', function (): void {
    $productWithOffer = Product::factory()->create(['price' => 1000]);
    ProductOffer::factory()->create([
        'product_id' => $productWithOffer->id,
        'offer_price' => 800,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    Product::factory()->count(3)->create();

    $response = $this->getJson('/api/products?only_offers=1');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(1);
});

test('can filter only products in stock', function (): void {
    Product::factory()->count(3)->create(['stock_quantity' => 10]);
    Product::factory()->count(2)->create(['stock_quantity' => 0]);

    $response = $this->getJson('/api/products?in_stock=1');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(3);
});

test('can filter products by rating', function (): void {
    $user = User::factory()->create();

    $product5Stars = Product::factory()->create();
    ProductReview::factory()->count(3)->create([
        'product_id' => $product5Stars->id,
        'user_id' => $user->id,
        'rating' => 5,
    ]);

    $product3Stars = Product::factory()->create();
    ProductReview::factory()->count(2)->create([
        'product_id' => $product3Stars->id,
        'user_id' => $user->id,
        'rating' => 3,
    ]);

    $response = $this->getJson('/api/products?ratings[]=5');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(1);
});

test('can sort products by newest', function (): void {
    Product::factory()->create(['badge' => 'Lançamento', 'created_at' => now()->subDays(2)]);
    Product::factory()->create(['badge' => null, 'created_at' => now()->subDay()]);
    Product::factory()->create(['badge' => 'Lançamento', 'created_at' => now()->subDays(3)]);

    $response = $this->getJson('/api/products?sort_by=newest');

    $response->assertStatus(200);
    $products = $response->json('data.products.data');

    // Produtos com badge "Lançamento" devem vir primeiro
    expect($products[0]['badge'])->toBe('Lançamento');
    expect($products[1]['badge'])->toBe('Lançamento');
});

test('can sort products by biggest discount', function (): void {
    $product1 = Product::factory()->create(['price' => 1000]);
    ProductOffer::factory()->create([
        'product_id' => $product1->id,
        'offer_price' => 500, // 50% desconto
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $product2 = Product::factory()->create(['price' => 1000]);
    ProductOffer::factory()->create([
        'product_id' => $product2->id,
        'offer_price' => 700, // 30% desconto
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $response = $this->getJson('/api/products?sort_by=biggest_discount');

    $response->assertStatus(200);
    $products = $response->json('data.products.data');

    expect($products[0]['id'])->toBe($product1->id);
    expect($products[1]['id'])->toBe($product2->id);
});

test('can sort products by lowest price', function (): void {
    Product::factory()->create(['price' => 1000]);
    Product::factory()->create(['price' => 500]);
    Product::factory()->create(['price' => 1500]);

    $response = $this->getJson('/api/products?sort_by=lowest_price');

    $response->assertStatus(200);
    $products = $response->json('data.products.data');

    expect((float) $products[0]['price'])->toBeLessThanOrEqual((float) $products[1]['price']);
});

test('can paginate products', function (): void {
    Product::factory()->count(25)->create();

    $response = $this->getJson('/api/products?per_page=10');

    $response->assertStatus(200);
    expect($response->json('data.products.data'))->toHaveCount(10);
    expect($response->json('data.products.total'))->toBe(25);
});

test('validates invalid category id', function (): void {
    $response = $this->getJson('/api/products?categories[]=99999');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['categories.0']);
});

test('validates price range', function (): void {
    $response = $this->getJson('/api/products?price_min=1000&price_max=500');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['price_max']);
});

test('validates invalid sort option', function (): void {
    $response = $this->getJson('/api/products?sort_by=invalid_option');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sort_by']);
});
