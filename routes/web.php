<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MultiplayerController;
use App\Http\Controllers\QuestionController;

// Route untuk API Game
Route::get('/api/questions/random', [QuestionController::class, 'getRandomQuestion'])->name('api.questions.random');
Route::post('/api/questions/check', [QuestionController::class, 'checkAnswer'])->name('api.questions.check');

/*
|--------------------------------------------------------------------------
| Lobby
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('lobby');
})->name('home');


/*
|--------------------------------------------------------------------------
| Mode Perorangan
|--------------------------------------------------------------------------
*/

Route::get('/game/single', [
    GameController::class,
    'single'
])->name('game.single');


Route::post('/game/single/start', [
    GameController::class,
    'startSingle'
])->name('game.single.start');


/*
|--------------------------------------------------------------------------
| Mode Multiplayer
|--------------------------------------------------------------------------
*/

Route::get('/game/multiplayer', [
    MultiplayerController::class,
    'index'
])->name('game.multiplayer');


Route::post('/game/multiplayer/start', [
    MultiplayerController::class,
    'start'
])->name('game.multiplayer.start');


/*
|--------------------------------------------------------------------------
| Game
|--------------------------------------------------------------------------
*/

Route::get('/game/play', [
    GameController::class,
    'play'
])->name('game.play');


/*
|--------------------------------------------------------------------------
| Card
|--------------------------------------------------------------------------
*/

Route::post('/game/card/select', [
    GameController::class,
    'selectCard'
])->name('game.card.select');