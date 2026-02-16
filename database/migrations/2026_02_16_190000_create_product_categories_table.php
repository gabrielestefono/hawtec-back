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
        Schema::create(table: 'product_categories', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'name');
            $table->text(column: 'description')->nullable();
            $table->string(column: 'icon', length: 120)->default(value: 'heroicon-o-tag');
            $table->string(column: 'href')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'product_categories');
    }
};
