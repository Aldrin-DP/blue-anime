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
            $table->text('description')->nullable();
            $table->string('romaji_title')->nullable();
            $table->string('status')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('season_year')->nullable();
            $table->string('country_of_origin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_caches', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'romaji_title',
                'status',
                'popularity',
                'season_year',
                'country_of_origin'
            ]);
        });
    }
};
