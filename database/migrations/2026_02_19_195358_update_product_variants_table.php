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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['color', 'storage', 'ram', 'voltage']);
            $table->foreignId('color_id')->nullable()->constrained('product_colors')->nullOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained('storage_options')->nullOnDelete();
            $table->foreignId('ram_id')->nullable()->constrained('ram_options')->nullOnDelete();
            $table->enum('voltage', ['110v', '220v', '110/220v'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeignIdFor('product_colors');
            $table->dropForeignIdFor('storage_options');
            $table->dropForeignIdFor('ram_options');
            $table->dropColumn('voltage');
            $table->string('color')->nullable();
            $table->string('storage')->nullable();
            $table->string('ram')->nullable();
            $table->string('voltage')->nullable();
        });
    }
};
