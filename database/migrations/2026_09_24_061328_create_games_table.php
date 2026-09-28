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

            $table->string('player_1');

            $table->string('player_2')->nullable();

            $table->unsignedInteger('score_1')->default(0);

            $table->unsignedInteger('score_2')->default(0);

            $table->string('winner')->nullable();

            $table->unsignedTinyInteger('total_round')->default(0);

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