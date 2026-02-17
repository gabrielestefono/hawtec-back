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
        Schema::create(table: 'product_offers', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'product_id')->constrained()->cascadeOnDelete();
            $table->decimal(column: 'offer_price', total: 10, places: 2);
            $table->timestamp(column: 'starts_at')->nullable();
            $table->timestamp(column: 'ends_at')->nullable();
            $table->unsignedInteger(column: 'quantity_limit')->nullable();
            $table->unsignedInteger(column: 'quantity_sold')->default(value: 0);
            $table->timestamps();

            $table->index(columns: ['product_id', 'starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'product_offers');
    }
};
