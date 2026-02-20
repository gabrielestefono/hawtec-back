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
        if (! Schema::hasTable('product_variants')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'color')) {
                $table->dropColumn(['color', 'storage', 'ram', 'voltage']);
            }

            if (! Schema::hasColumn('product_variants', 'color_id')) {
                $table->foreignId('color_id')->nullable()->constrained('product_colors')->nullOnDelete();
            }

            if (! Schema::hasColumn('product_variants', 'storage_id')) {
                $table->foreignId('storage_id')->nullable()->constrained('storage_options')->nullOnDelete();
            }

            if (! Schema::hasColumn('product_variants', 'ram_id')) {
                $table->foreignId('ram_id')->nullable()->constrained('ram_options')->nullOnDelete();
            }

            if (! Schema::hasColumn('product_variants', 'voltage')) {
                $table->enum('voltage', ['110v', '220v', '110/220v'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('product_variants')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'color_id')) {
                $table->dropConstrainedForeignId('color_id');
            }

            if (Schema::hasColumn('product_variants', 'storage_id')) {
                $table->dropConstrainedForeignId('storage_id');
            }

            if (Schema::hasColumn('product_variants', 'ram_id')) {
                $table->dropConstrainedForeignId('ram_id');
            }

            if (Schema::hasColumn('product_variants', 'voltage')) {
                $table->dropColumn('voltage');
            }

            if (! Schema::hasColumn('product_variants', 'color')) {
                $table->string('color')->nullable();
                $table->string('storage')->nullable();
                $table->string('ram')->nullable();
                $table->string('voltage')->nullable();
            }
        });
    }
};
