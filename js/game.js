document.addEventListener("DOMContentLoaded", function () {
    const config = window.gameConfig || {};
    
    // State Game
    let currentScore1 = 0;
    let currentScore2 = 0;
    let currentRound = 1;
    const maxRounds = 7;
    let usedCardsCount = 0;
    let usedQuestionIds = [];
    
    let currentQuestion = null;
    let activePlayer = null;
    let chooserPlayer = 'P1';
    let timerInterval = null;
    let timeLeft = 15;
    let currentActiveButton = null;
    let isAnswering = false;

    // STEP 8: Sound Effects (Web Audio API / Audio Elements)
 
    function playSound(name) {
        if (sounds[name]) {
            sounds[name].currentTime = 0;
            sounds[name].play().catch(() => {}); // Catch autoplay restrictions
        }
    }

    // DOM Elements
    const quizModal = document.getElementById('quiz-modal');
    const resultModal = document.getElementById('result-modal');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const feedbackText = document.getElementById('quiz-feedback');
    const timerDisplay = document.getElementById('timer-display');
    const buzzerSection = document.getElementById('buzzer-section');
    const activePlayerBanner = document.getElementById('active-player-banner');
    
    const score1Text = document.getElementById('score1-text');
    const score2Text = document.getElementById('score2-text');
    const roundText = document.getElementById('round-display-text');
    const usedCardCountText = document.getElementById('used-cards-counter');
    const restartBtn = document.getElementById('restart-btn');

    function updateChooserUI() {
        const chooserName = chooserPlayer === 'P1' ? (config.player1 || 'Pemain 1') : (config.player2 || 'Pemain 2');
        const infoBanner = document.querySelector('.card-section-header h2');
        if (infoBanner) {
            infoBanner.textContent = `Giliran [ ${chooserName} ] untuk memilih kartu!`;
            infoBanner.style.color = chooserPlayer === 'P1' ? '#3b82f6' : '#ef4444';
        }
    }

    // Intercept form submit kartu
    document.querySelectorAll('.card-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('.game-card');
            if (!btn || btn.disabled) return;
            
            currentActiveButton = btn;
            const cardCode = btn.getAttribute('data-card-code') || (this.querySelector('input[name="card"]')?.value);
            if (cardCode) fetchQuestion(cardCode);
        });
    });

    // Listener Tombol Rebutan
    document.getElementById('buzz-p1')?.addEventListener('click', () => setTurn('P1'));
    document.getElementById('buzz-p2')?.addEventListener('click', () => setTurn('P2'));

    // Keyboard Shortcut (P1 = A, P2 = L)
    window.addEventListener('keydown', function(e) {
        if (!quizModal || quizModal.classList.contains('hidden') || activePlayer !== null) return;

        const key = e.key.toLowerCase();
        if (key === 'a') {
            e.preventDefault();
            setTurn('P1');
        } else if (key === 'l' && config.mode === 'multiplayer') {
            e.preventDefault();
            setTurn('P2');
        }
    });

    function fetchQuestion(cardCode) {
        const usedIdsString = usedQuestionIds.join(',');
        const url = `${config.getQuestionUrl}?card=${encodeURIComponent(cardCode)}&used_ids=${encodeURIComponent(usedIdsString)}`;

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error("Gagal mengambil data");
                return res.json();
            })
            .then(res => {
                if (res.status === 'success' || res.id || res.data) {
                                const data = res.data || res;
                    currentQuestion = data;
                    if (data.id && !usedQuestionIds.includes(data.id)) {
                        usedQuestionIds.push(data.id);
                    }
                    openQuizModal(data);
                } else {
                    alert("Gagal memuat soal.");
                }
            })
            .catch(err => console.error("Error:", err));
    }

    function openQuizModal(question) {
        feedbackText.textContent = "";
        optionsContainer.innerHTML = "";
        activePlayer = null;
        isAnswering = false;
        quizModal.classList.remove('hidden');

        questionText.textContent = question.question || question.question_text;

        const options = question.options || {
            'A': question.option_a, 'B': question.option_b,
            'C': question.option_c, 'D': question.option_d
        };

        if (config.mode === 'multiplayer') {
            buzzerSection.classList.remove('hidden');
            activePlayerBanner.textContent = "Tekan Tombol REBUT atau Gunakan Keyboard (A / L)!";
            activePlayerBanner.style.color = "#38bdf8";
            renderDisabledOptions(options);
            startTimer(true); 
        } else {
            buzzerSection.classList.add('hidden');
            activePlayer = 'P1';
            activePlayerBanner.textContent = `Pemain: ${config.player1 || 'Pemain 1'}`;
            renderOptions(options);
            startTimer(false);
        }
    }

    function setTurn(player) {
        if (activePlayer !== null) return;
        activePlayer = player;
        
        playSound('buzzer'); // STEP 8: Sound effect buzzer
        
        const playerName = player === 'P1' ? (config.player1 || 'P1') : (config.player2 || 'P2');
        activePlayerBanner.textContent = `⚡ ${playerName} BERHAK MENJAWAB!`;
        activePlayerBanner.style.color = player === 'P1' ? '#3b82f6' : '#ef4444';
        
        buzzerSection.classList.add('hidden');
        renderOptions(currentQuestion.options || {
            'A': currentQuestion.option_a, 'B': currentQuestion.option_b,
            'C': currentQuestion.option_c, 'D': currentQuestion.option_d
        });
        
        startTimer(false);
    }

    function renderDisabledOptions(options) {
        optionsContainer.innerHTML = "";
        Object.keys(options).forEach(key => {
            if (options[key]) {
                const btn = document.createElement('button');
                btn.className = 'option-btn disabled';
                btn.disabled = true;
                btn.textContent = `${key}. ${options[key]}`;
                optionsContainer.appendChild(btn);
            }
        });
    }

    function renderOptions(options) {
        optionsContainer.innerHTML = "";
        Object.keys(options).forEach(key => {
            if (options[key]) {
                const btn = document.createElement('button');
                btn.className = 'option-btn';
                btn.type = 'button';
                btn.textContent = `${key}. ${options[key]}`;
                btn.onclick = () => submitAnswer(key, btn);
                optionsContainer.appendChild(btn);
            }
        });
    }

    function disableAnswerButtons(disabled) {
        document.querySelectorAll('.option-btn').forEach(btn => btn.disabled = disabled);
    }

    // STEP 8: Timer & Animasi
    function startTimer(isBuzzerPhase = false) {
        clearInterval(timerInterval);
        timeLeft = 15;
        if (timerDisplay) {
            timerDisplay.textContent = timeLeft;
            timerDisplay.classList.remove('warning');
        }

        timerInterval = setInterval(() => {
            timeLeft--;
            if (timerDisplay) {
                timerDisplay.textContent = timeLeft;
                if (timeLeft <= 5) {
                    timerDisplay.classList.add('warning'); // STEP 8: Animasi/Warna Timer
                    playSound('tick');
                }
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                disableAnswerButtons(true);
                playSound('wrong');
                
                if (isBuzzerPhase) {
                    feedbackText.textContent = "⏱️ Tidak ada yang merebut!";
                } else {
                    feedbackText.textContent = "⏱️ Waktu Menjawab Habis!";
                }
                feedbackText.style.color = "#ef4444";

                switchChooserToOpponent();
                setTimeout(finishTurn, 1500);
            }
        }, 1000);
    }

    function switchChooserToOpponent() {
        if (config.mode === 'multiplayer') {
            if (activePlayer === 'P1') {
                chooserPlayer = 'P2';
            } else if (activePlayer === 'P2') {
                chooserPlayer = 'P1';
            } else {
                chooserPlayer = chooserPlayer === 'P1' ? 'P2' : 'P1';
            }
        }
    }

    // STEP 7: Penambahan Poin & Efek Jawaban
    function submitAnswer(selectedOption, selectedBtn) {
        if (isAnswering) return;
        isAnswering = true;

        clearInterval(timerInterval);
        disableAnswerButtons(true);

        fetch(config.checkAnswerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken
            },
            body: JSON.stringify({
                question_id: currentQuestion.id,
                answer: selectedOption
            })
        })
        .then(res => res.json())
        .then(data => {
            const isCorrect = data.is_correct;
            const points = data.points || currentQuestion.points || 100;

            if (isCorrect) {
                playSound('correct'); // STEP 8: Sound Effect
                selectedBtn.classList.add('correct');
                feedbackText.textContent = `✅ Benar! (+${points} Poin)`;
                feedbackText.style.color = "#22c55e";

                if (activePlayer === 'P1') {
                    currentScore1 += points;
                    if (score1Text) score1Text.textContent = currentScore1;
                    chooserPlayer = 'P1';
                } else if (activePlayer === 'P2') {
                    currentScore2 += points;
                    if (score2Text) score2Text.textContent = currentScore2;
                    chooserPlayer = 'P2';
                }
            } else {
                playSound('wrong'); // STEP 8: Sound Effect
                selectedBtn.classList.add('wrong');
                feedbackText.textContent = `❌ Salah! Jawaban benar: ${data.correct_answer || '-'}`;
                feedbackText.style.color = "#ef4444";

                switchChooserToOpponent();
            }

            setTimeout(finishTurn, 2000);
        })
        .catch(err => {
            console.error(err);
            setTimeout(finishTurn, 1000);
        });
    }

    function finishTurn() {
        clearInterval(timerInterval);
        quizModal.classList.add('hidden');

        if (currentActiveButton) {
            currentActiveButton.disabled = true;
            currentActiveButton.classList.add('used');
            
            const usedSpan = currentActiveButton.querySelector('.card-used');
            if (usedSpan) usedSpan.classList.remove('hidden');

            usedCardsCount++;
            if (usedCardCountText) usedCardCountText.textContent = usedCardsCount;
        }

        if (usedCardsCount >= maxRounds) {
            showWinner();
        } else {
            currentRound++;
            if (roundText) roundText.textContent = `${currentRound} / ${maxRounds}`;
            updateChooserUI();
        }
    }

    // STEP 7 & 8: Halaman Hasil & Save Leaderboard
    function showWinner() {
        playSound('win');
        const winnerText = document.getElementById('winner-text');
        const finalScoresText = document.getElementById('final-scores-text');

        const p1Name = config.player1 || 'Pemain 1';
        const p2Name = config.player2 || 'Pemain 2';

        if (config.mode === 'multiplayer') {
            if (currentScore1 > currentScore2) {
                if (winnerText) winnerText.textContent = `🏆 ${p1Name} MENANG!`;
            } else if (currentScore2 > currentScore1) {
                if (winnerText) winnerText.textContent = `🏆 ${p2Name} MENANG!`;
            } else {
                if (winnerText) winnerText.textContent = "🤝 HASIL SERI!";
            }
            if (finalScoresText) finalScoresText.textContent = `${p1Name}: ${currentScore1} Poin | ${p2Name}: ${currentScore2} Poin`;
        } else {
            if (winnerText) winnerText.textContent = `SKOR AKHIR: ${currentScore1} POIN`;
            if (finalScoresText) finalScoresText.textContent = `Selamat ${p1Name}, Anda menyelesaikan ${maxRounds} ronde!`;
        }

        // Opsional: Kirim skor ke Leaderboard Backend
        if (config.saveLeaderboardUrl) {
            fetch(config.saveLeaderboardUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken
                },
                body: JSON.stringify({ score1: currentScore1, score2: currentScore2 })
            }).catch(err => console.error("Leaderboard Save Error:", err));
        }

        if (resultModal) resultModal.classList.remove('hidden');
    }

    // STEP 8: Reset / Play Again
    if (restartBtn) {
        restartBtn.addEventListener('click', function() {
            currentScore1 = 0;
            currentScore2 = 0;
            currentRound = 1;
            usedCardsCount = 0;
            usedQuestionIds = [];
            chooserPlayer = 'P1';

            if (score1Text) score1Text.textContent = '0';
            if (score2Text) score2Text.textContent = '0';
            if (roundText) roundText.textContent = `1 / ${maxRounds}`;
            if (usedCardCountText) usedCardCountText.textContent = '0';

            document.querySelectorAll('.game-card').forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('used');
                const usedSpan = btn.querySelector('.card-used');
                if (usedSpan) usedSpan.classList.add('hidden');
            });

            resultModal.classList.add('hidden');
            updateChooserUI();
        });
    }

    function initGame() {
        updateChooserUI();
        const cardA = document.querySelector('.game-card[data-card-code="A"]');
        if (cardA && !cardA.classList.contains('used')) {
            setTimeout(() => {
                currentActiveButton = cardA;
                fetchQuestion('A');
            }, 500);
        }
    }

    initGame();
});