<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'round_number',
        'card',
        'points',
        'question_id',
        'winner',
    ];

    protected $casts = [
        'round_number' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Relasi ke game.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Relasi ke pertanyaan.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}