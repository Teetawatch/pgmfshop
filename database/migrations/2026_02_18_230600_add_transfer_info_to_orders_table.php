<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('transfer_date')->nullable()->after('payment_slip');
            $table->string('transfer_time', 5)->nullable()->after('transfer_date');
            $table->decimal('transfer_amount', 10, 2)->nullable()->after('transfer_time');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transfer_date', 'transfer_time', 'transfer_amount']);
        });
    }
};
