<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->foreignId('color_id')->nullable()->constrained('product_colors')->nullOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained('storage_options')->nullOnDelete();
            $table->foreignId('ram_id')->nullable()->constrained('ram_options')->nullOnDelete();
            $table->enum('voltage', ['110v', '220v', '110/220v'])->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity');
            $table->timestamps();

            $table->index('product_id');
            $table->index('color_id');
            $table->index('storage_id');
            $table->index('ram_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
