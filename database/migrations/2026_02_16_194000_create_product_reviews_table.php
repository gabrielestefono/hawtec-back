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
        Schema::create(table: 'product_reviews', callback: function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'product_id')->constrained()->cascadeOnDelete();
            $table->foreignId(column: 'user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger(column: 'rating');
            $table->text(column: 'comment')->nullable();
            $table->timestamps();

            $table->unique(columns: ['product_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'product_reviews');
    }
};
