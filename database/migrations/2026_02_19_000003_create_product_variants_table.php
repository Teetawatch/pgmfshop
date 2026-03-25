<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'size', 'color'], 'product_variant_unique');
            $table->index('product_id');
        });

        // Add variant_id to order_items so we can track which variant was purchased
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
        });

        // Add variant_id to stock_movements for variant-level audit trail
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('variant_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('variant_id');
        });

        Schema::dropIfExists('product_variants');
    }
};
