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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('home_team')->nullable();
            $table->integer('away_team')->nullable();
            $table->integer('week_id')->nullable();
            $table->integer('home_team_goal')->default(0);
            $table->integer('away_team_goal')->default(0);
            $table->boolean('status')->default(0)->comment('0 for not played match and 1 for played');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
