<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MultiplayerController extends Controller
{
    /**
     * Menampilkan halaman input nama dua pemain.
     */
    public function index()
    {
        return view('multiplayer');
    }

    /**
     * Memulai permainan multiplayer.
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'player_1' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],

            'player_2' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
        ], [
            'player_1.required' => 'Nama Player 1 wajib diisi.',
            'player_1.string' => 'Nama Player 1 harus berupa teks.',
            'player_1.min' => 'Nama Player 1 minimal 2 karakter.',
            'player_1.max' => 'Nama Player 1 maksimal 50 karakter.',

            'player_2.required' => 'Nama Player 2 wajib diisi.',
            'player_2.string' => 'Nama Player 2 harus berupa teks.',
            'player_2.min' => 'Nama Player 2 minimal 2 karakter.',
            'player_2.max' => 'Nama Player 2 maksimal 50 karakter.',
        ]);

        if (
            strtolower(trim($validated['player_1'])) ===
            strtolower(trim($validated['player_2']))
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'player_2' => 'Nama Player 1 dan Player 2 harus berbeda.',
                ]);
        }

        session([
            'game_mode' => 'multiplayer',

            'player_1' => $validated['player_1'],

            'player_2' => $validated['player_2'],

            'score_1' => 0,

            'score_2' => 0,

            'current_round' => 1,

            'card_picker' => null,
        ]);

        return redirect()->route('game.play');
    }
}