<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Game - Tabalong Battle
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/game.css') }}"
    >

    <style>
        .hidden { display: none !important; }
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.85); display: flex; align-items: center;
            justify-content: center; z-index: 9999;
        }
        .modal-content {
            background: #1e293b; color: #fff; padding: 25px; border-radius: 12px;
            max-width: 600px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .buzzer-container { display: flex; gap: 15px; justify-content: center; margin-top: 15px; }
        .btn-buzz {
            padding: 12px 24px; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;
            font-size: 16px; transition: transform 0.1s ease; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px;
        }
        .btn-buzz-p1 { background-color: #3b82f6; color: white; }
        .btn-buzz-p2 { background-color: #ef4444; color: white; }
        .btn-buzz:active { transform: scale(0.95); }
        .key-hint { font-size: 11px; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px; }
        
        .options-list { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
        .option-btn {
            padding: 12px; background: #334155; color: white; border: 1px solid #475569;
            border-radius: 8px; cursor: pointer; text-align: left; font-size: 15px;
        }
        .option-btn:hover:not(:disabled) { background: #475569; }
        .option-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        .option-btn.correct { background-color: #22c55e !important; color: white; }
        .option-btn.wrong { background-color: #ef4444 !important; color: white; }
        .timer-badge { font-size: 20px; font-weight: bold; color: #f59e0b; margin-bottom: 10px; }
        .question-title { font-size: 18px; font-weight: 600; margin: 15px 0; line-height: 1.4; color: #f8fafc; }
    </style>

</head>


<body>


<main class="game-page">


    <section class="game-container">


        {{-- ==================================================
             TOP BAR
        ================================================== --}}

        <div class="game-topbar">


            <div class="game-brand">

                <img
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWIRiZfGmZvCU5kU2zZG3AE-W40tJgAV5YJzMO9Ab87w&s=10"
                    alt="Logo Kabupaten Tabalong"
                    class="brand-logo-img"
                >

                <div class="game-brand-text">
                    <p class="game-label">
                        TABALONG BATTLE
                    </p>

                        <h1>
                        {{ $mode === 'multiplayer' ? 'BATTLE' : 'PERORANGAN' }}
                    </h1>
                </div>

            </div>


            <div class="round-display">

                <span>
                    ROUND
                </span>

                <strong id="round-display-text">
                    {{ $currentRound }} / 5
                </strong>

            </div>


        </div>


        {{-- ==================================================
             SCORE
        ================================================== --}}

        <div class="score-container">


            <div class="score-card">

                <span class="score-player">
                    {{ $player1 }}
                </span>

                <strong id="score1-text">
                    {{ $score1 }}
                </strong>

                <small>
                    POIN
                </small>

            </div>


            @if($mode === 'multiplayer')

                <div class="score-vs">
                    VS
                </div>


                <div class="score-card score-player-two">

                    <span class="score-player">
                        {{ $player2 }}
                    </span>

                    <strong id="score2-text">
                        {{ $score2 }}
                    </strong>

                    <small>
                        POIN
                    </small>

                </div>

            @endif


        </div>


        {{-- ==================================================
             SELECTED CARD
        ================================================== --}}

        @if($selectedCard)

            @php
                $selectedCardData = $cards[$selectedCard];
            @endphp

            <div class="selected-card-banner">

                <div>

                    <span>
                        KARTU TERPILIH
                    </span>

                    <strong>
                        Kartu {{ $selectedCard }}
                    </strong>

                </div>


                <div class="selected-card-points">

                    <strong>
                        {{ $selectedCardData['points'] }}
                    </strong>

                    <span>
                        POIN
                    </span>

                </div>

            </div>

        @endif


        {{-- ==================================================
             CARD SECTION
        ================================================== --}}

        <div class="card-section">


            <div class="card-section-header">

                <div>

                    <p class="game-label">
                        PILIH KARTU
                    </p>

                    <h2>
                        Pilih kartu untuk mendapatkan soal
                    </h2>

                </div>


                <div class="card-counter">

                    <strong id="used-cards-counter">
                        {{ count($usedCards) }}
                    </strong>

                    <span>
                        / 12
                    </span>

                    <small>
                        KARTU TERPAKAI
                    </small>

                </div>

            </div>


            @if($errors->has('card'))

                <div class="card-error">

                    {{ $errors->first('card') }}

                </div>

            @endif


            {{-- ==================================================
                 12 CARDS
            ================================================== --}}

            <div class="cards-grid">


                @foreach($cards as $card)

                    @php
                        $isUsed = in_array(
                            $card['code'],
                            $usedCards,
                            true
                        );

                        $isSelected =
                            $selectedCard === $card['code'];
                    @endphp


                    <form
                        action="{{ route('game.card.select') }}"
                        method="POST"
                        class="card-form"
                    >

                        @csrf


                        <input
                            type="hidden"
                            name="card"
                            value="{{ $card['code'] }}"
                        >


                        <button
                            type="submit"
                            class="game-card
                                {{ $isUsed ? 'used' : '' }}
                                {{ $isSelected ? 'selected' : '' }}"
                            {{ $isUsed ? 'disabled' : '' }}
                            data-card-code="{{ $card['code'] }}"
                        >


                            <span class="card-code">
                                {{ $card['code'] }}
                            </span>


                            <span class="card-points">
                                {{ $card['points'] }}
                            </span>


                            <span class="card-point-label">
                                POIN
                            </span>


                            <span class="card-difficulty">
                                {{ $card['difficulty'] }}
                            </span>


                            <span class="card-used {{ $isUsed ? '' : 'hidden' }}">
                                ✓ TERPAKAI
                            </span>


                            @if(!$isUsed && $isSelected)

                                <span class="card-selected-tag">
                                    TERPILIH
                                </span>

                            @endif


                        </button>

                    </form>

                @endforeach


            </div>


        </div>


        {{-- ==================================================
             EXIT
        ================================================== --}}

        <a
            href="{{ route('home') }}"
            class="exit-game"
        >
            ← Kembali ke Lobby
        </a>


        {{-- ==================================================
             MODAL SOAL & REBUTAN (URUTAN: SOAL -> PILIHAN GANDA -> TOMBOL REBUT)
        ================================================== --}}
        <div id="quiz-modal" class="modal-overlay hidden">
            <div class="modal-content">
                
                {{-- 1. Timer & Info Banner --}}
                <div class="timer-badge">⏱️ <span id="timer-display">15</span>s</div>
                <div id="active-player-banner" style="margin-bottom: 10px; font-weight: bold; color: #38bdf8; font-size: 14px;"></div>

                {{-- 2. SOAL --}}
                <h3 id="question-text" class="question-title">Memuat pertanyaan...</h3>
                
                {{-- 3. PILIHAN GANDA (A, B, C, D) --}}
                <div id="options-container" class="options-list"></div>

                {{-- Feedback Hasil Jawaban --}}
                <div id="quiz-feedback" class="feedback-message" style="margin-top: 15px; font-weight: bold; font-size: 16px;"></div>

                {{-- 4. TOMBOL REBUT (DI BAGIAN BANGAH) --}}
                <div id="buzzer-section" class="{{ $mode === 'multiplayer' ? '' : 'hidden' }}" style="margin-top: 20px;">
                    <div class="buzzer-container">
                        <button type="button" class="btn-buzz btn-buzz-p1" id="buzz-p1">
                            <span>⚡ REBUT: {{ $player1 }}</span>
                            <span class="key-hint">Tekan Tombol</span>
                        </button>
                        
                        @if($mode === 'multiplayer')
                            <button type="button" class="btn-buzz btn-buzz-p2" id="buzz-p2">
                                <span>⚡ REBUT: {{ $player2 }}</span>
                                <span class="key-hint">Tekan Tombol</span>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- ==================================================
             MODAL HASIL AKHIR / WINNER
        ================================================== --}}
        <div id="result-modal" class="modal-overlay hidden">
            <div class="modal-content">
                <h2>🎉 PERMAINAN SELESAI 🎉</h2>
                <h1 id="winner-text" style="color: #f59e0b; margin: 20px 0;"></h1>
                <p id="final-scores-text"></p>
                <br>
                <a href="{{ route('home') }}" class="btn-buzz btn-buzz-p1" style="text-decoration: none; display: inline-block;">Kembali ke Lobby</a>
            </div>
        </div>

    </section>


</main>

<script>
    window.gameConfig = {
        mode: "{{ $mode }}",
        player1: "{{ $player1 }}",
        player2: "{{ $player2 ?? '' }}",
        csrfToken: "{{ csrf_token() }}",
        getQuestionUrl: "/api/questions/random",
        checkAnswerUrl: "/api/questions/check"
    };
</script>
<script src="{{ asset('js/game.js') }}"></script>

</body>

</html>