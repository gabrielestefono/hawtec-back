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
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('description');
            $table->string('sku')->nullable()->unique()->after('brand');
            $table->text('long_description')->nullable()->after('sku');
            $table->json('colors')->nullable()->after('long_description');
            $table->json('specs')->nullable()->after('colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'sku', 'long_description', 'colors', 'specs']);
        });
    }
};
