<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type', 20)->default('book')->after('category_id');
            $table->json('sizes')->nullable()->after('pages');
            $table->json('colors')->nullable()->after('sizes');
            $table->string('material')->nullable()->after('colors');
            $table->decimal('weight', 8, 2)->nullable()->after('material');
            $table->string('sku')->nullable()->after('weight');

            $table->index('product_type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_type']);
            $table->dropColumn(['product_type', 'sizes', 'colors', 'material', 'weight', 'sku']);
        });
    }
};
