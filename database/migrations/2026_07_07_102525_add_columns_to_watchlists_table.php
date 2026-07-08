<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->enum('status', ['watching', 'plan_to_watch', 'completed', 'dropped']);
            $table->integer('progress')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'progress',
                'notes',
                'is_favorited'
            ]);
        });
    }
};
