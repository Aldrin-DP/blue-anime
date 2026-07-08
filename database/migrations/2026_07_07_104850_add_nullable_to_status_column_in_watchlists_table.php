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
        Schema::table('watchlists', function (Blueprint $table) {
            $table->enum('status', ['watching', 'plan_to_watch', 'completed', 'dropped'])
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
         
            $table->enum('status', ['watching', 'plan_to_watch', 'completed', 'dropped'])
                ->nullable(false)
                ->change();

        });
    }
};
