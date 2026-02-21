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
        // Converter product_variants.price de decimal para integer (centavos)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('price_cents')->default(0)->after('price');
        });

        // Migrar dados: multiplicar decimal por 100
        \DB::statement('UPDATE product_variants SET price_cents = ROUND(CAST(price AS DECIMAL(12,4)) * 100)');

        // Dropar coluna antiga
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        // Renomear price_cents para price
        Schema::table('product_variants', function (Blueprint $table) {
            $table->renameColumn('price_cents', 'price');
        });

        // Converter product_offers.offer_price de decimal para integer (centavos)
        Schema::table('product_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_price_cents')->default(0)->after('offer_price');
        });

        // Migrar dados: multiplicar decimal por 100
        \DB::statement('UPDATE product_offers SET offer_price_cents = ROUND(CAST(offer_price AS DECIMAL(12,4)) * 100)');

        // Dropar coluna antiga
        Schema::table('product_offers', function (Blueprint $table) {
            $table->dropColumn('offer_price');
        });

        // Renomear offer_price_cents para offer_price
        Schema::table('product_offers', function (Blueprint $table) {
            $table->renameColumn('offer_price_cents', 'offer_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert product_variants - use safe column drop
        if (Schema::hasColumn('product_variants', 'price')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->renameColumn('price', 'price_cents');
            });
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 10, 2);
        });

        if (Schema::hasColumn('product_variants', 'price_cents')) {
            \DB::statement('UPDATE product_variants SET price = price_cents / 100');

            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('price_cents');
            });
        }

        // Revert product_offers
        if (Schema::hasColumn('product_offers', 'offer_price')) {
            Schema::table('product_offers', function (Blueprint $table) {
                $table->renameColumn('offer_price', 'offer_price_cents');
            });
        }

        Schema::table('product_offers', function (Blueprint $table) {
            $table->decimal('offer_price', 10, 2)->after('product_variant_id');
        });

        if (Schema::hasColumn('product_offers', 'offer_price_cents')) {
            \DB::statement('UPDATE product_offers SET offer_price = offer_price_cents / 100');

            Schema::table('product_offers', function (Blueprint $table) {
                $table->dropColumn('offer_price_cents');
            });
        }
    }
};
