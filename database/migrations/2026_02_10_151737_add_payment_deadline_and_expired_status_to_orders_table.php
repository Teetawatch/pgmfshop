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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_deadline')->nullable()->after('notes');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','awaiting_payment','paid','processing','shipped','delivered','cancelled','expired') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','awaiting_payment','paid','processing','shipped','delivered','cancelled') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_deadline');
        });
    }
};
