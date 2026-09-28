<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Multiplayer - Tabalong Battle</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/game.css') }}"
    >

</head>


<body>


<main class="setup-page">


    <section class="setup-container multiplayer-setup">


        {{-- BACK --}}

        <a
            href="{{ route('home') }}"
            class="back-button"
        >
            ← Kembali
        </a>


        {{-- HEADER --}}

        <div class="setup-header">

            <div class="setup-icon battle-icon">
                ⚔️
            </div>

            <p class="setup-label multiplayer-label">
                MODE MULTIPLAYER
            </p>

            <h1>
                Siapa yang bertanding?
            </h1>

            <p>
                Masukkan nama kedua pemain untuk memulai Battle.
            </p>

        </div>


        {{-- FORM --}}

        <form
            action="{{ route('game.multiplayer.start') }}"
            method="POST"
            class="player-form"
        >

            @csrf


            {{-- PLAYER 1 --}}

            <div class="player-input-card player-one">

                <div class="player-number">
                    01
                </div>


                <div class="form-group">

                    <label for="player_1">
                        PLAYER 1
                    </label>


                    <input
                        type="text"
                        id="player_1"
                        name="player_1"
                        value="{{ old('player_1') }}"
                        placeholder="Nama Player 1..."
                        maxlength="50"
                        autocomplete="off"
                        autofocus
                        required
                    >


                    @error('player_1')

                        <span class="form-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

            </div>


            {{-- VS --}}

            <div class="versus">
                VS
            </div>


            {{-- PLAYER 2 --}}

            <div class="player-input-card player-two">

                <div class="player-number">
                    02
                </div>


                <div class="form-group">

                    <label for="player_2">
                        PLAYER 2
                    </label>


                    <input
                        type="text"
                        id="player_2"
                        name="player_2"
                        value="{{ old('player_2') }}"
                        placeholder="Nama Player 2..."
                        maxlength="50"
                        autocomplete="off"
                        required
                    >


                    @error('player_2')

                        <span class="form-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

            </div>


            {{-- BUTTON --}}

            <button
                type="submit"
                class="start-button multiplayer-start"
            >

                <span>
                    MULAI BATTLE
                </span>

                <strong>
                    ⚔
                </strong>

            </button>


        </form>


        {{-- INFO --}}

        <div class="setup-info">

            <div>

                <strong>
                    5
                </strong>

                <span>
                    ROUND
                </span>

            </div>


            <div>

                <strong>
                    12
                </strong>

                <span>
                    KARTU
                </span>

            </div>


            <div>

                <strong>
                    2
                </strong>

                <span>
                    PEMAIN
                </span>

            </div>

        </div>


    </section>


</main>


</body>

</html>