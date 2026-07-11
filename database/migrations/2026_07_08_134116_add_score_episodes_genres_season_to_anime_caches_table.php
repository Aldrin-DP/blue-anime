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
        Schema::table('anime_caches', function (Blueprint $table) {
            $table->integer('score')->nullable();
            $table->integer('episodes')->nullable();
            $table->string('season')->nullable();
            $table->json('genres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_caches', function (Blueprint $table) {
            $table->dropColumn([
                'score',
                'episodes',
                'season',
                'genres'
            ]);
        });
    }
};
