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
        // Adicionar slug em product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'slug')) {
                $table->string('slug')->nullable();
            }
        });

        // Remover slug de products
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });
    }
};
