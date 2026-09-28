<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_1',
        'player_2',
        'score_1',
        'score_2',
        'winner',
        'total_round',
    ];

    protected $casts = [
        'score_1' => 'integer',
        'score_2' => 'integer',
        'total_round' => 'integer',
    ];

    /**
     * Relasi ke semua round dalam game.
     */
    public function rounds()
    {
        return $this->hasMany(GameRound::class);
    }
}