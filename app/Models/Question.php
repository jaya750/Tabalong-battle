<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'card',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'category',
        'difficulty',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function gameRounds()
    {
        return $this->hasMany(GameRound::class);
    }
}