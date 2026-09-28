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
        Schema::create('game_rounds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')
                ->constrained('games')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('round_number');

            $table->string('card');

            $table->unsignedInteger('points');

            $table->foreignId('question_id')
                ->nullable()
                ->constrained('questions')
                ->nullOnDelete();

            $table->string('winner')->nullable();

            $table->timestamps();

            $table->index([
                'game_id',
                'round_number',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_rounds');
    }
};