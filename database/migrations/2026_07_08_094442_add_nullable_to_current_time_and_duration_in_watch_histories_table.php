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
            $table->float('current_time')->nullable()->change();
            $table->integer('duration')->after('current_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_histories', function (Blueprint $table) {
            $table->float('current_time')->nullable(false)->change();
            $table->integer('duration')->after('current_time')->nullable(false)->change();
        });
    }
};
