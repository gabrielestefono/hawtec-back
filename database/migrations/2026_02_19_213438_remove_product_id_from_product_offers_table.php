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
        Schema::table('product_offers', function (Blueprint $table) {
            if (Schema::hasColumn('product_offers', 'product_id')) {
                $table->dropConstrainedForeignId('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_offers', function (Blueprint $table) {
            if (! Schema::hasColumn('product_offers', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            }
        });
    }
};
