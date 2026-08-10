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
            $table->integer('next_episode')->nullable();
            $table->bigInteger('next_episode_airing_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_caches', function (Blueprint $table) {
            $table->dropColumn([
                'next_episode',
                'next_episode_airing_at'
            ]);
        });
    }
};
