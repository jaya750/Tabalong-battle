<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Game Kuis Rebutan - Kabupaten Tabalong</title>

    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* Header & Logo Section */
        header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            text-align: center;
            width: 100%;
            max-width: 900px;
        }

        .logo-tabalong {
            width: 65px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }

        .header-title h1 {
            font-size: 1.8rem;
            color: #38bdf8;
            letter-spacing: 1px;
        }

        .header-title p {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        /* Scoreboard & Info Section */
        .game-status-board {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 900px;
            background-color: var(--card-bg);
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .player-score {
            text-align: center;
        }

        .player-score.p1 { color: #3b82f6; }
        .player-score.p2 { color: #ef4444; }

        .score-val {
            font-size: 2rem;
            font-weight: bold;
        }

        .round-info {
            text-align: center;
            background: #334155;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Banner Informasi Pemilih Kartu */
        .card-section-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .card-section-header h2 {
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        /* Grid Kartu Game */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 15px;
            width: 100%;
            max-width: 900px;
            margin-bottom: 30px;
        }

        .card-form {
            width: 100%;
        }

        .game-card {
            width: 100%;
            height: 120px;
            background: linear-gradient(135deg, #0284c7, #0369a1);
            border: 2px solid #38bdf8;
            border-radius: 12px;
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .game-card:hover:not(:disabled) {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.4);
        }

        .game-card:disabled, .game-card.used {
            background: #334155;
            border-color: #475569;
            color: #64748b;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .card-used {
            position: absolute;
            font-size: 0.8rem;
            bottom: 8px;
            background: rgba(0,0,0,0.5);
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.hidden {
            display: none !important;
        }

        /* Container Soal/Kuis */
        .quiz-container {
            background-color: var(--card-bg);
            border: 1px solid #334155;
            width: 100%;
            max-width: 650px;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            text-align: center;
            position: relative;
        }

        /* Timer Section */
        .timer-box {
            font-size: 1.5rem;
            font-weight: bold;
            background: #0f172a;
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            border: 2px solid #38bdf8;
            margin-bottom: 15px;
        }

        #timer-display.warning {
            color: var(--danger);
            border-color: var(--danger);
            animation: pulse 0.5s infinite alternate;
        }

        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        .active-player-banner {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            min-height: 28px;
        }

        /* Tombol Rebutan (Buzzer) */
        .buzzer-wrapper {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .buzz-btn {
            flex: 1;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: background 0.2s;
        }

        .buzz-btn.p1 { background-color: #2563eb; }
        .buzz-btn.p1:hover { background-color: #1d4ed8; }
        .buzz-btn.p2 { background-color: #dc2626; }
        .buzz-btn.p2:hover { background-color: #b91c1c; }

        /* Opsi Jawaban */
        .question-text {
            font-size: 1.2rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .options-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }

        .option-btn {
            background-color: #334155;
            color: var(--text-light);
            border: 1px solid #475569;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 1rem;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-btn:hover:not(:disabled) {
            background-color: #475569;
            border-color: #38bdf8;
        }

        .option-btn.correct {
            background-color: var(--success) !important;
            border-color: var(--success) !important;
            color: white !important;
        }

        .option-btn.wrong {
            background-color: var(--danger) !important;
            border-color: var(--danger) !important;
            color: white !important;
        }

        .option-btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .quiz-feedback {
            font-size: 1.1rem;
            font-weight: bold;
            min-height: 30px;
        }

        /* Modal Hasil Akhir */
        .result-container {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .restart-btn {
            background-color: var(--success);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.2s;
        }

        .restart-btn:hover {
            background-color: #16a34a;
        }

        /* Responsive Mobile/Tablet */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                gap: 8px;
            }

            .logo-tabalong {
                width: 50px;
            }

            .header-title h1 {
                font-size: 1.4rem;
            }

            .buzzer-wrapper {
                flex-direction: column;
            }

            .cards-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>

    <!-- Header dengan Logo Tabalong -->
    <header>
        <!-- Ganti src ke lokasi file logo Tabalong kamu -->
        <img src="/images/logo-tabalong.png" alt="Logo Kabupaten Tabalong" class="logo-tabalong">
        <div class="header-title">
            <h1>KUIS REBUTAN TABALONG</h1>
            <p>Pemerintah Kabupaten Tabalong</p>
        </div>
    </header>

    <!-- Scoreboard & Progress Ronde -->
    <div class="game-status-board">
        <div class="player-score p1">
            <div id="p1-name-display">Pemain 1</div>
            <div class="score-val" id="score1-text">0</div>
        </div>
        
        <div class="round-info">
            <div>RONDE</div>
            <div id="round-display-text">1 / 7</div>
            <small style="color: #94a3b8;"><span id="used-cards-counter">0</span>/7 Kartu</small>
        </div>

        <div class="player-score p2">
            <div id="p2-name-display">Pemain 2</div>
            <div class="score-val" id="score2-text">0</div>
        </div>
    </div>

    <!-- Banner Informasi Giliran Memilih Kartu -->
    <div class="card-section-header">
        <h2>Giliran Pemain 1 untuk memilih kartu!</h2>
    </div>

    <!-- Grid Kartu Soal (A - G) -->
    <div class="cards-grid">
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="A">
                A
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="B">
                B
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="C">
                C
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="D">
                D
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="E">
                E
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="F">
                F
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
        <form class="card-form">
            <button type="submit" class="game-card" data-card-code="G">
                G
                <span class="card-used hidden">Terpakai</span>
            </button>
        </form>
    </div>

    <!-- Modal Kuis Soal -->
    <div id="quiz-modal" class="modal-overlay hidden">
        <div class="quiz-container">
            <div class="timer-box">
                ⏱️ <span id="timer-display">15</span> Detik
            </div>

            <div id="active-player-banner" class="active-player-banner">
                Tekan Tombol REBUT atau Gunakan Keyboard (A / L)!
            </div>

            <!-- Tombol Buzzer Rebutan -->
            <div id="buzzer-section" class="buzzer-wrapper">
                <button type="button" id="buzz-p1" class="buzz-btn p1">REBUT (Pemain 1 - Key A)</button>
                <button type="button" id="buzz-p2" class="buzz-btn p2">REBUT (Pemain 2 - Key L)</button>
            </div>

            <!-- Teks Soal & Pilihan Jawaban -->
            <div class="question-text" id="question-text">
                Memuat soal...
            </div>

            <div class="options-grid" id="options-container">
                <!-- Opsi A, B, C, D di-render via JS -->
            </div>

            <div id="quiz-feedback" class="quiz-feedback"></div>
        </div>
    </div>

    <!-- Modal Hasil Akhir (Game Over) -->
    <div id="result-modal" class="modal-overlay hidden">
        <div class="result-container">
            <h1 style="font-size: 2.2rem; margin-bottom: 10px;">PERMAINAN SELESAI</h1>
            <h2 id="winner-text" style="color: #38bdf8; margin-bottom: 15px;">🏆 Pemain 1 Menang!</h2>
            <p id="final-scores-text" style="font-size: 1.1rem; color: #cbd5e1;">Pemain 1: 300 Poin | Pemain 2: 200 Poin</p>
            
            <button type="button" id="restart-btn" class="restart-btn">🔄 Main Lagi</button>
        </div>
    </div>

    <!-- Global Config & JS Logic -->
    <script>
        window.gameConfig = {
            mode: 'multiplayer', // 'singleplayer' atau 'multiplayer'
            player1: 'Pemain 1',
            player2: 'Pemain 2',
            getQuestionUrl: '/api/get-question', // Route backend kamu
            checkAnswerUrl: '/api/check-answer',
            saveLeaderboardUrl: '/api/save-leaderboard',
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };
    </script>
    <script src="/js/game-kuis.js"></script>
</body>
</html>