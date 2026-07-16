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
        Schema::table('watch_histories', function (Blueprint $table) {
            $table->boolean('hidden_from_continue_watching')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_histories', function (Blueprint $table) {
            $table->dropColumn('hidden_from_continue_watching');
        });
    }
};
