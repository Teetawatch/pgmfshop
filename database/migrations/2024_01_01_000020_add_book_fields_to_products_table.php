<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('publisher')->nullable()->after('description');
            $table->json('genres')->nullable()->after('publisher');
            $table->json('authors')->nullable()->after('genres');
            $table->integer('pages')->nullable()->after('authors');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['publisher', 'genres', 'authors', 'pages']);
        });
    }
};
