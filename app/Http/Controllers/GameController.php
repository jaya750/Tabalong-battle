<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Menampilkan halaman input nama pemain untuk mode perorangan.
     */
    public function single()
    {
        return view('single');
    }

    /**
     * Memulai permainan perorangan.
     */
    public function startSingle(Request $request)
    {
        $validated = $request->validate([
            'player_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
        ], [
            'player_name.required' => 'Nama pemain wajib diisi.',
            'player_name.string'   => 'Nama pemain harus berupa teks.',
            'player_name.min'      => 'Nama pemain minimal 2 karakter.',
            'player_name.max'      => 'Nama pemain maksimal 50 karakter.',
        ]);

        session([
            'game_mode'     => 'single',
            'player_1'      => $validated['player_name'],
            'player_2'      => null,
            'score_1'       => 0,
            'score_2'       => 0,
            'current_round' => 1,
            'used_cards'    => [],
            'selected_card' => null,
        ]);

        return redirect()->route('game.play');
    }

    /**
     * Menampilkan halaman permainan.
     */
    public function play()
    {
        if (!session()->has('game_mode')) {
            return redirect()->route('home');
        }

        $cards = config('cards.cards', []);
        $usedCards = session('used_cards', []);

        return view('game', [
            'mode'         => session('game_mode'),
            'player1'      => session('player_1'),
            'player2'      => session('player_2'),
            'score1'       => session('score_1', 0),
            'score2'       => session('score_2', 0),
            'currentRound' => session('current_round', 1),
            'cards'        => $cards,
            'usedCards'    => $usedCards,
            'selectedCard' => session('selected_card'),
        ]);
    }

    /**
     * Memilih kartu.
     */
    public function selectCard(Request $request)
    {
        if (!session()->has('game_mode')) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'card' => [
                'required',
                'string',
                'in:A,B,C,D,E,F,G,H,I,J,K,L',
            ],
        ]);

        $cardCode = $validated['card'];
        $usedCards = session('used_cards', []);

        /*
        |--------------------------------------------------------------------------
        | Cegah kartu digunakan kembali
        |--------------------------------------------------------------------------
        */
        if (in_array($cardCode, $usedCards, true)) {
            return back()->withErrors([
                'card' => 'Kartu tersebut sudah digunakan.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan kartu sebagai kartu yang sedang dipilih
        |--------------------------------------------------------------------------
        */
        session([
            'selected_card' => $cardCode,
        ]);

        return redirect()->route('game.play');
    }

    /**
     * Memproses jawaban untuk kartu yang sedang dipilih.
     */
    public function submitAnswer(Request $request)
    {
        if (!session()->has('game_mode') || !session()->has('selected_card')) {
            return redirect()->route('game.play');
        }

        $validated = $request->validate([
            'answer' => 'required|string',
        ]);

        $selectedCard = session('selected_card');
        $cards = config('cards.cards', []);

        $cardData = $cards[$selectedCard] ?? null;

        // Cek kebenaran jawaban jika data jawaban tersedia di config
        $isCorrect = false;
        if ($cardData && isset($cardData['answer'])) {
            $isCorrect = strtolower(trim($validated['answer'])) === strtolower(trim($cardData['answer']));
        }

        // Tambahkan skor jika jawaban benar
        if ($isCorrect) {
            session()->increment('score_1', 10);
        }

        // Masukkan kartu ke daftar yang sudah terpakai
        $usedCards = session('used_cards', []);
        $usedCards[] = $selectedCard;

        // Update session ronde & reset kartu terpilih
        session([
            'used_cards'    => $usedCards,
            'selected_card' => null,
            'current_round' => session('current_round', 1) + 1,
        ]);

        // Jika seluruh kartu sudah digunakan, selesai permainan
        if (count($usedCards) >= count($cards)) {
            return redirect()->route('game.finish');
        }

        return redirect()->route('game.play')->with('status', $isCorrect ? 'Jawaban Benar!' : 'Jawaban Salah!');
    }

    /**
     * Menampilkan halaman hasil/akhir permainan.
     */
    public function finish()
    {
        if (!session()->has('game_mode')) {
            return redirect()->route('home');
        }

        return view('finish', [
            'player1' => session('player_1'),
            'score1'  => session('score_1', 0),
        ]);
    }

    /**
     * Mereset dan menghapus session permainan.
     */
    public function reset()
    {
        session()->forget([
            'game_mode',
            'player_1',
            'player_2',
            'score_1',
            'score_2',
            'current_round',
            'used_cards',
            'selected_card',
        ]);

        return redirect()->route('home');
    }
}