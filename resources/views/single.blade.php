<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mode Perorangan - Tabalong Speed Sorter</title>

    <!-- Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ==================================================
           RESET & VARIABLE SYSTEM (THEMA KABUPATEN TABALONG)
           ================================================== */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* TABALONG OFFICIAL COLOR PALETTE */
            --tabalong-green: #0b6b57;
            --tabalong-green-dark: #06483c;
            --tabalong-green-light: #e3f4ed;
            --tabalong-green-glow: rgba(11, 107, 87, 0.25);

            --tabalong-gold: #d7a62a;
            --tabalong-gold-dark: #b98212;
            --tabalong-gold-light: #fff7dc;
            --tabalong-gold-glow: rgba(215, 166, 42, 0.3);

            --tabalong-blue: #1f6f8b;
            --tabalong-blue-light: #e8f5f8;

            --tabalong-red: #c73e3e;
            --tabalong-red-light: #fff0f0;

            /* NEUTRAL & UI TONES */
            --dark: #10231f;
            --text: #29423b;
            --muted: #667a73;
            --white: #ffffff;
            --light: #f5f9f7;
            --border: #d8e5df;
            --border-gold: rgba(215, 166, 42, 0.45);

            /* SHADOWS & RADII */
            --radius-lg: 28px;
            --radius-md: 18px;
            --radius-sm: 12px;
            --shadow-main: 0 25px 60px rgba(6, 72, 60, 0.08);
            --shadow-card: 0 10px 25px rgba(6, 72, 60, 0.06);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', Arial, Helvetica, sans-serif;
            background: linear-gradient(
                135deg,
                #edf8f3 0%,
                #f8fafc 50%,
                #fff8e6 100%
            );
            background-attachment: fixed;
            color: var(--dark);
            min-height: 100vh;
        }

        /* ==================================================
           SETUP PAGE CONTAINER & WATERMARK
           ================================================== */
        .setup-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .setup-container {
            width: 100%;
            max-width: 580px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: var(--shadow-main);
            position: relative;
            overflow: hidden;
        }

        /* Watermark Logo Tabalong Transparan */
        .setup-container::before {
            content: "";
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 180px;
            height: 180px;
            background-image: url('/images/logo-tabalong.png');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.05;
            pointer-events: none;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            transition: var(--transition);
        }

        .back-button:hover {
            color: var(--tabalong-green);
            transform: translateX(-3px);
        }

        .setup-header {
            text-align: center;
            margin-top: 25px;
            margin-bottom: 30px;
            position: relative;
            z-index: 2;
        }

        .setup-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 16px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
        }

        .single-icon {
            background: var(--tabalong-green-light);
            color: var(--tabalong-green);
            border: 2px solid var(--border-gold);
            box-shadow: 0 8px 20px var(--tabalong-green-glow);
        }

        .setup-label {
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
            color: var(--tabalong-green);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .setup-header h1 {
            font-size: 32px;
            letter-spacing: -1px;
            font-weight: 900;
            margin-bottom: 8px;
            color: var(--dark);
        }

        .setup-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        /* ==================================================
           FORM & INPUTS
           ================================================== */
        .player-form {
            width: 100%;
            position: relative;
            z-index: 2;
        }

        .form-group {
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            height: 54px;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 18px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            color: var(--dark);
            background: var(--white);
            transition: var(--transition);
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        .form-group input:focus {
            border-color: var(--tabalong-green);
            box-shadow: 0 0 0 4px var(--tabalong-green-light);
        }

        .card-error {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: var(--radius-sm);
            background: var(--tabalong-red-light);
            color: var(--tabalong-red);
            border: 1px solid #fecaca;
            font-size: 12px;
            font-weight: 700;
        }

        .card-error ul {
            list-style-type: none;
        }

        /* ==================================================
           BUTTONS
           ================================================== */
        .start-button {
            width: 100%;
            min-height: 58px;
            border: none;
            border-radius: var(--radius-sm);
            margin-top: 24px;
            padding: 0 24px;
            background: linear-gradient(135deg, var(--tabalong-green), var(--tabalong-green-dark));
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 22px var(--tabalong-green-glow);
            transition: var(--transition);
            font-family: inherit;
        }

        .start-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px var(--tabalong-green-glow);
        }

        .start-button strong {
            font-size: 20px;
        }

        /* ==================================================
           RESPONSIVE DESIGN (MOBILE MAX 600PX)
           ================================================== */
        @media (max-width: 600px) {
            .setup-page {
                padding: 12px;
            }

            .setup-container {
                padding: 24px 18px;
                border-radius: 20px;
            }

            .setup-header {
                margin-top: 20px;
                margin-bottom: 24px;
            }

            .setup-icon {
                width: 60px;
                height: 60px;
                border-radius: 16px;
                font-size: 26px;
            }

            .setup-header h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

    <div class="setup-page">
        <div class="setup-container">
            
            <!-- Tombol Kembali -->
            <a href="{{ route('home') }}" class="back-button">
                <span>←</span>
                <span>Kembali ke Lobby</span>
            </a>

            <!-- Header Halaman -->
            <div class="setup-header">
                <div class="setup-icon single-icon">
                    👤
                </div>
                <div class="setup-label">MODE PERORANGAN</div>
                <h1>Persiapan Main</h1>
                <p>Masukkan nama Anda untuk mencatat skor dan mulai petualangan edukasi budaya Tabalong.</p>
            </div>

            <!-- Pesan Error Validasi -->
            @if ($errors->any())
                <div class="card-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>⚠️ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Start Single Player -->
            <form action="{{ route('game.single.start') }}" method="POST" class="player-form">
                @csrf

                <div class="form-group">
                    <label for="player_name">NAMA PEMAIN</label>
                    <input 
                        type="text" 
                        id="player_name" 
                        name="player_name" 
                        value="{{ old('player_name') }}"
                        placeholder="Masukkan nama Anda..." 
                        required 
                        minlength="2"
                        maxlength="50"
                        autofocus
                    >
                </div>

                <button type="submit" class="start-button">
                    <span>Mulai Permainan</span>
                    <strong>→</strong>
                </button>
            </form>

        </div>
    </div>

</body>
</html>