<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, atualizar produtos sem categoria para usar a primeira categoria disponível
        $firstCategoryId = DB::table('product_categories')->orderBy('id')->value('id');

        if ($firstCategoryId) {
            DB::table('products')
                ->whereNull('product_category_id')
                ->update(['product_category_id' => $firstCategoryId]);
        }

        // Remover a foreign key antiga
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
        });

        // Modificar a coluna para NOT NULL
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id')->nullable(false)->change();
        });

        // Recriar a foreign key sem SET NULL (usando RESTRICT)
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover a foreign key
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
        });

        // Modificar a coluna para nullable
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id')->nullable()->change();
        });

        // Recriar a foreign key original com SET NULL
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');
        });
    }
};
