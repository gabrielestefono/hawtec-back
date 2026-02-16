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
        Schema::create(table: 'banners', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'title');
            $table->string(column: 'subtitle')->nullable();
            $table->text(column: 'description')->nullable();
            $table->string(column: 'button_label')->nullable();
            $table->string(column: 'button_url')->nullable();
            $table->boolean(column: 'is_active')->default(value: true);
            $table->unsignedInteger(column: 'sort')->default(value: 0);
            $table->timestamp(column: 'starts_at')->nullable();
            $table->timestamp(column: 'ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'banners');
    }
};
