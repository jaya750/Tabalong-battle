<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tabalong Battle</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/game.css') }}"
    >
</head>

<body>

    <main class="lobby">

        <section class="lobby-container">

            {{-- LOGO --}}
            <div class="game-logo">

                <img
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWIRiZfGmZvCU5kU2zZG3AE-W40tJgAV5YJzMO9Ab87w&s=10"
                    alt="Logo Kabupaten Tabalong"
                    class="brand-logo-img"
                >

                <div>
                    <p class="logo-small">
                        HUT KABUPATEN TABALONG
                    </p>

                    <h1>
                        TABALONG
                        <span>BATTLE</span>
                    </h1>
                </div>

            </div>

            {{-- CONTENT --}}
            <div class="lobby-content">

                <p class="welcome-label">
                    SELAMAT DATANG
                </p>

                <h2>
                    Uji Pengetahuanmu
                    <br>
                    Tentang Tabalong
                </h2>

                <p class="description">
                    Tantang dirimu dan temanmu dalam permainan
                    kuis interaktif tentang sejarah, budaya,
                    geografis, wisata, dan pengetahuan
                    Kabupaten Tabalong.
                </p>

                {{-- MODE --}}
                <div class="mode-title">
                    PILIH MODE PERMAINAN
                </div>

                <div class="mode-container">

                    {{-- PERORANGAN --}}
                    <a
                        href="{{ route('game.single') }}"
                        class="mode-card"
                    >
                        <div class="mode-icon">
                            👤
                        </div>

                        <div class="mode-info">
                            <h3>
                                PERORANGAN
                            </h3>

                            <p>
                                Main sendiri dan uji
                                pengetahuanmu.
                            </p>
                        </div>

                        <div class="mode-arrow">
                            →
                        </div>
                    </a>

                    {{-- MULTIPLAYER --}}
                    <a
                        href="{{ route('game.multiplayer') }}"
                        class="mode-card multiplayer-card"
                    >
                        <div class="mode-icon">
                            ⚔️
                        </div>

                        <div class="mode-info">
                            <h3>
                                MULTIPLAYER
                            </h3>

                            <p>
                                Tantang temanmu dalam
                                Battle 2 pemain.
                            </p>
                        </div>

                        <div class="mode-arrow">
                            →
                        </div>
                    </a>

                </div>

                {{-- INFO GAME --}}
                <div class="game-info">
                  
                </div>

            </div>

        </section>

    </main>

</body>

</html>
