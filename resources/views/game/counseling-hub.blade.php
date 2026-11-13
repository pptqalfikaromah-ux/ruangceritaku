@extends($layout)

@section('title', 'Ruang Rasa & Game Konseling')
@section('page_title', 'Ruang Rasa & Game Konseling')
@section('breadcrumb')
    <span class="current">Ruang Rasa & Game Konseling</span>
@endsection

@push('styles')
<style>
    .game-tab-btn {
        border-radius: 50rem;
        padding: 0.6rem 1.4rem;
        font-weight: 700;
        font-size: 0.88rem;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .game-tab-btn:hover {
        border-color: #0d9488;
        color: #0d9488;
        background: #f0fdfa;
    }
    .game-tab-btn.active {
        background: #0d9488 !important;
        border-color: #0d9488 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.28);
    }

    /* 3D Flip Card Effect */
    .card-perspective {
        perspective: 1200px;
        width: 100%;
        max-width: 480px;
        height: 320px;
        margin: 0 auto;
    }
    .card-flip-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.65s cubic-bezier(0.4, 0.2, 0.2, 1);
        transform-style: preserve-3d;
        cursor: pointer;
    }
    .card-flip-inner.is-flipped {
        transform: rotateY(180deg);
    }
    .card-face {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 1.8rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 2px solid rgba(255, 255, 255, 0.8);
    }
    .card-front {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff;
    }
    .card-back {
        background: #ffffff;
        color: #1e293b;
        transform: rotateY(180deg);
        border: 2px solid #e2e8f0;
    }

    /* Floating Bubble Styles */
    .bubble-canvas-area {
        position: relative;
        width: 100%;
        min-height: 380px;
        background: radial-gradient(circle at 50% 50%, #f0fdfa 0%, #e2e8f0 100%);
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid #cbd5e1;
    }
    .floating-bubble {
        position: absolute;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 12px;
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        user-select: none;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12), inset 0 2px 6px rgba(255,255,255,0.8);
        animation: floatBubble 5s ease-in-out infinite alternate;
        transition: transform 0.15s ease;
    }
    .floating-bubble:hover {
        transform: scale(1.08);
    }
    @keyframes floatBubble {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-16px) rotate(3deg); }
        100% { transform: translateY(6px) rotate(-3deg); }
    }

    /* Zen Breathing Circle */
    .breath-circle-wrapper {
        position: relative;
        width: 220px;
        height: 220px;
        margin: 20px auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .breath-circle {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d9488, #34d399);
        box-shadow: 0 0 35px rgba(13, 148, 136, 0.45);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 800;
        transition: transform 4s ease-in-out;
    }
    .breath-expanding {
        transform: scale(1.6);
        box-shadow: 0 0 60px rgba(52, 211, 153, 0.75);
    }
    .breath-contracting {
        transform: scale(1);
        box-shadow: 0 0 20px rgba(13, 148, 136, 0.35);
    }

    /* Dilemma Choices */
    .choice-card-btn {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        text-align: left;
        width: 100%;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .choice-card-btn:hover {
        border-color: #0d9488;
        background: #f0fdfa;
        transform: translateX(4px);
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                        <i class="bi bi-controller fs-2"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Media Konseling Interaktif
                            </span>
                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                {{ $roleLabel }}
                            </span>
                        </div>
                        <h1 class="h4 fw-extrabold text-dark mb-0.5">Ruang Rasa & Game Konseling</h1>
                        <p class="text-muted fs-xs mb-0">Eksplorasi emosi, cairkan suasana (*icebreaking*), dan latih empati melalui media kartu dan permainan relaksasi.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-xs fw-semibold" id="btnToggleSoundGame">
                        <i class="bi bi-volume-up-fill me-1" id="soundIconGame"></i> <span id="soundStatusGame">Suara: On</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills Antar Modul Game -->
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-3">
        <button type="button" class="game-tab-btn active" data-tab="tabCards">
            <i class="bi bi-card-text"></i> 🎴 Kartu Ruang Rasa
        </button>
        <button type="button" class="game-tab-btn" data-tab="tabBubbles">
            <i class="bi bi-droplet-half"></i> 🫧 Gelembung Beban & Rileks
        </button>
        <button type="button" class="game-tab-btn" data-tab="tabDilemmas">
            <i class="bi bi-compass"></i> 🧭 Pilihan Bijak (Empati)
        </button>
    </div>

    <!-- ======================================================== -->
    <!-- MODUL 1: KARTU RUANG RASA (3D CONVERSATION CARDS)        -->
    <!-- ======================================================== -->
    <div id="tabCards" class="game-content-panel">
        <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4 text-center">
            <!-- Filter Kategori Kartu -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-1.5 mb-4">
                <button type="button" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1 fs-xxs fw-bold filter-card-btn active" data-cat="all">
                    🌟 Semua Kategori
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1 fs-xxs fw-bold filter-card-btn" data-cat="feelings">
                    💛 Emosi & Perasaan
                </button>
                <button type="button" class="btn btn-sm btn-outline-teal text-mint rounded-pill px-3 py-1 fs-xxs fw-bold filter-card-btn" data-cat="social">
                    🤝 Pertemanan & Empati
                </button>
                <button type="button" class="btn btn-sm btn-outline-purple text-purple rounded-pill px-3 py-1 fs-xxs fw-bold filter-card-btn" data-cat="goals">
                    🎯 Mimpi & Harapan
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger text-danger rounded-pill px-3 py-1 fs-xxs fw-bold filter-card-btn" data-cat="coping">
                    🛡️ Ketahanan & Solusi
                </button>
            </div>

            <!-- Area Kartu 3D Flip -->
            <div class="card-perspective mb-3">
                <div class="card-flip-inner" id="cardElement">
                    <!-- Sisi Depan Kartu -->
                    <div class="card-face card-front">
                        <div class="p-3 rounded-circle bg-white text-mint d-inline-flex mb-3 shadow-xs">
                            <i class="bi bi-chat-heart-fill fs-1"></i>
                        </div>
                        <h4 class="fw-extrabold mb-1">Kartu Ruang Rasa</h4>
                        <p class="fs-xs opacity-75 mb-3">Refleksi Diri & Bincang Santai Konseling</p>
                        <span class="badge bg-white text-dark px-3 py-1.5 rounded-pill fs-xxs fw-bold shadow-xs">
                            👉 Klik kartu ini untuk membuka pertanyaan
                        </span>
                    </div>

                    <!-- Sisi Belakang Kartu (Pertanyaan) -->
                    <div class="card-face card-back">
                        <span class="badge px-3 py-1 rounded-pill fs-xxs fw-bold mb-3 shadow-xs" id="cardCategoryBadge" style="background:#0d9488; color:#fff;">
                            💛 Emosi Diri
                        </span>
                        <h5 class="fw-extrabold text-dark px-2 mb-3" id="cardQuestionText" style="line-height: 1.55; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                            Kapan terakhir kali kamu merasa sangat bangga pada dirimu sendiri? Apa penyebabnya?
                        </h5>
                        <p class="text-muted fs-xxs fst-italic mb-0">
                            "Renungkan sejenak pertanyaan ini dan bagikan jawaban tulusmu."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Kontrol Kartu -->
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 pt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-xs fw-semibold" id="btnFlipAgain">
                    <i class="bi bi-arrow-repeat me-1"></i> Balik Kartu
                </button>
                <button type="button" class="btn btn-mint text-white btn-sm rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs" id="btnShuffleCard">
                    <i class="bi bi-shuffle me-1"></i> Kocok Kartu Berikutnya
                </button>
            </div>
            <div class="text-muted fs-xxs mt-2" id="cardCounterText">
                Menampilkan kumpulan pertanyaan kartu refleksi psikologis.
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODUL 2: GELEMBUNG BEBAN & RUANG TENANG (WORRY POP & ZEN) -->
    <!-- ======================================================== -->
    <div id="tabBubbles" class="game-content-panel d-none">
        <div class="row g-3">
            <!-- Kolom Kiri: Letuskan Beban Pikiran -->
            <div class="col-12 col-lg-7">
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-dark mb-0 fs-sm">
                            <i class="bi bi-droplet-half me-1 text-mint"></i> Letuskan Gelembung Beban (Worry Pop)
                        </h6>
                        <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-xxs" id="bubbleCountBadge">
                            0 Gelembung
                        </span>
                    </div>
                    <p class="text-muted fs-xxs mb-3">
                        Tuliskan kecemasan, rasa takut, atau beban yang sedang mengganjal. Klik gelembungnya untuk meletuskannya dan melepaskan beban tersebut!
                    </p>

                    <!-- Input Tiup Gelembung -->
                    <div class="input-group input-group-sm mb-2.5">
                        <input type="text" id="bubbleTextInput" class="form-control fs-xs" placeholder="Ketik beban pikiranmu (contoh: Takut gagal ujian, sedih diejek)...">
                        <button type="button" class="btn btn-mint text-white fw-bold px-3" id="btnAddBubble">
                            <i class="bi bi-plus-circle me-1"></i> Tiup Gelembung
                        </button>
                    </div>

                    <!-- Presets Cepat -->
                    <div class="d-flex flex-wrap align-items-center gap-1 mb-3">
                        <span class="text-muted fs-xxs">Contoh cepat:</span>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0.5 fs-xxs preset-bubble-btn">Cemas Ujian</button>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0.5 fs-xxs preset-bubble-btn">Takut Bikin Kecewa</button>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0.5 fs-xxs preset-bubble-btn">Merasa Sendirian</button>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0.5 fs-xxs preset-bubble-btn">Lelah Tugas</button>
                    </div>

                    <!-- Arena Gelembung Mengambang -->
                    <div class="bubble-canvas-area p-3" id="bubbleArena">
                        <div class="h-100 d-flex align-items-center justify-content-center text-muted fs-xs" id="emptyBubbleNotice">
                            <div>
                                <i class="bi bi-cloud-sun fs-1 text-mint d-block mb-1"></i>
                                Belum ada gelembung beban pikiran. Ketik di atas untuk mulai melepaskan bebanmu!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Panduan Napas Zen (4-7-8) -->
            <div class="col-12 col-lg-5">
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4 text-center h-100 d-flex flex-column justify-content-center">
                    <h6 class="fw-bold text-dark mb-1 fs-sm">
                        <i class="bi bi-lungs-fill me-1 text-mint"></i> Panduan Napas Rileks (Zen Breath)
                    </h6>
                    <p class="text-muted fs-xxs mb-2">Ikuti lingkaran untuk menenangkan detak jantung dan meredakan stres.</p>

                    <div class="breath-circle-wrapper">
                        <div class="breath-circle" id="breathCircle">
                            <span class="fs-6 fw-extrabold" id="breathInstruction">Tarik Napas</span>
                            <span class="fs-xxs opacity-75" id="breathTimer">4 detik</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2 pt-2">
                        <button type="button" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-1.5 fs-xs fw-bold shadow-xs" id="btnToggleBreath">
                            <i class="bi bi-play-fill me-1" id="breathIcon"></i> Mulai Latihan Napas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODUL 3: DILEMA CERITA (EMPATHY & DECISION QUEST)         -->
    <!-- ======================================================== -->
    <div id="tabDilemmas" class="game-content-panel d-none">
        <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 pb-2 border-bottom">
                <div>
                    <h6 class="fw-bold text-dark mb-0 fs-sm">
                        <i class="bi bi-compass-fill me-1 text-mint"></i> Simulasi Pilihan Bijak: Kasus Nyata di Sekolah
                    </h6>
                    <p class="text-muted fs-xxs mb-0">Baca skenario dilema di bawah ini dan pilih respon tindakan yang menurutmu paling bijak.</p>
                </div>

                <div class="d-flex align-items-center gap-1.5">
                    @foreach($dilemmas as $index => $dil)
                        <button type="button" class="btn btn-sm {{ $loop->first ? 'btn-mint text-white' : 'btn-light border text-dark' }} rounded-pill px-3 py-1 fs-xxs fw-bold dilemma-nav-btn" data-index="{{ $index }}">
                            Kasus {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Kontainer Skenario Kasus -->
            <div id="dilemmaContainer">
                @foreach($dilemmas as $index => $dil)
                    <div class="dilemma-item {{ $loop->first ? '' : 'd-none' }}" id="dilemmaItem-{{ $index }}">
                        <!-- Judul & Narasi -->
                        <div class="p-3.5 rounded-3 bg-light border mb-3">
                            <span class="badge bg-mint text-white rounded-pill px-2.5 py-0.5 fs-xxs fw-bold mb-1.5">Skenario Kasus #{{ $index + 1 }}</span>
                            <h5 class="fw-extrabold text-dark mb-2 fs-6">{{ $dil['title'] }}</h5>
                            <p class="text-dark fs-xs mb-0" style="line-height: 1.65;">
                                "{{ $dil['scenario'] }}"
                            </p>
                        </div>

                        <!-- Opsi Pilihan Respon -->
                        <h6 class="fw-bold text-dark fs-xs mb-2">Apa yang akan kamu lakukan?</h6>
                        <div class="space-y-2 mb-3">
                            @foreach($dil['choices'] as $cIndex => $choice)
                                <button type="button" class="choice-card-btn choice-trigger" 
                                        data-feedback="{{ $choice['feedback'] }}" 
                                        data-badge="{{ $choice['badge'] }}">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <span class="badge bg-light text-dark border rounded-circle flex-shrink-0 mt-0.5" style="width: 24px; height: 24px; line-height: 18px; text-align: center;">
                                            {{ chr(65 + $cIndex) }}
                                        </span>
                                        <span class="fs-xs text-dark fw-semibold" style="line-height: 1.5;">
                                            {{ $choice['text'] }}
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Feedback Box (Muncul setelah memilih) -->
                        <div class="feedback-box p-3 rounded-3 border d-none" style="background:#f0fdfa; border-color:#0d9488 !important;">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <span class="badge bg-mint text-white rounded-pill px-2.5 py-1 fs-xxs fw-bold feedback-badge"></span>
                                <strong class="text-dark fs-xs">Refleksi Pilihanmu:</strong>
                            </div>
                            <p class="text-dark fs-xs mb-0 feedback-text" style="line-height: 1.6;"></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // Sound FX Web Audio API
    // ----------------------------------------------------
    let soundEnabled = true;
    let audioCtx = null;
    function initAudio() {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
    }
    function playTone(freq, duration, type = 'sine') {
        if (!soundEnabled) return;
        try {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch (e) {}
    }
    function playPop() {
        if (!soundEnabled) return;
        try {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.frequency.setValueAtTime(800, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(200, audioCtx.currentTime + 0.08);
            gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.08);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.08);
        } catch (e) {}
    }

    // Toggle Sound
    const btnSound = document.getElementById('btnToggleSoundGame');
    const soundIcon = document.getElementById('soundIconGame');
    const soundStatus = document.getElementById('soundStatusGame');
    btnSound.addEventListener('click', function () {
        soundEnabled = !soundEnabled;
        if (soundEnabled) {
            soundIcon.className = 'bi bi-volume-up-fill me-1';
            soundStatus.textContent = 'Suara: On';
            btnSound.classList.replace('btn-outline-danger', 'btn-outline-secondary');
        } else {
            soundIcon.className = 'bi bi-volume-mute-fill me-1';
            soundStatus.textContent = 'Suara: Off';
            btnSound.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        }
    });

    // ----------------------------------------------------
    // Tab Navigation
    // ----------------------------------------------------
    const tabButtons = document.querySelectorAll('.game-tab-btn');
    const tabPanels = document.querySelectorAll('.game-content-panel');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-tab');
            tabPanels.forEach(panel => {
                if (panel.id === targetId) {
                    panel.classList.remove('d-none');
                } else {
                    panel.classList.add('d-none');
                }
            });
            playTone(440, 0.06);
        });
    });

    // ----------------------------------------------------
    // MODUL 1: KARTU RUANG RASA
    // ----------------------------------------------------
    const cardDecks = @json($cardDecks);
    let currentCategory = 'all';
    let currentDeckCards = [];
    let currentCardIndex = 0;

    const cardElement = document.getElementById('cardElement');
    const cardQuestionText = document.getElementById('cardQuestionText');
    const cardCategoryBadge = document.getElementById('cardCategoryBadge');
    const btnShuffleCard = document.getElementById('btnShuffleCard');
    const btnFlipAgain = document.getElementById('btnFlipAgain');
    const filterCardBtns = document.querySelectorAll('.filter-card-btn');

    function buildCurrentDeck() {
        currentDeckCards = [];
        if (currentCategory === 'all') {
            Object.keys(cardDecks).forEach(cat => {
                cardDecks[cat].cards.forEach(q => {
                    currentDeckCards.push({
                        category: cat,
                        badge: cardDecks[cat].badge,
                        color: cardDecks[cat].color,
                        question: q
                    });
                });
            });
        } else if (cardDecks[currentCategory]) {
            cardDecks[currentCategory].cards.forEach(q => {
                currentDeckCards.push({
                    category: currentCategory,
                    badge: cardDecks[currentCategory].badge,
                    color: cardDecks[currentCategory].color,
                    question: q
                });
            });
        }
        // Acak kartu
        for (let i = currentDeckCards.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [currentDeckCards[i], currentDeckCards[j]] = [currentDeckCards[j], currentDeckCards[i]];
        }
        currentCardIndex = 0;
    }

    function showCard(index) {
        if (currentDeckCards.length === 0) return;
        const item = currentDeckCards[index % currentDeckCards.length];
        cardQuestionText.textContent = item.question;
        cardCategoryBadge.textContent = item.badge;
        cardCategoryBadge.style.backgroundColor = item.color;
    }

    cardElement.addEventListener('click', function () {
        this.classList.toggle('is-flipped');
        playTone(587.33, 0.12); // D5
    });

    btnFlipAgain.addEventListener('click', function () {
        cardElement.classList.toggle('is-flipped');
        playTone(587.33, 0.12);
    });

    btnShuffleCard.addEventListener('click', function () {
        cardElement.classList.remove('is-flipped');
        setTimeout(() => {
            currentCardIndex++;
            showCard(currentCardIndex);
            setTimeout(() => {
                cardElement.classList.add('is-flipped');
                playTone(659.25, 0.15); // E5
            }, 250);
        }, 300);
    });

    filterCardBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterCardBtns.forEach(b => {
                b.classList.remove('btn-mint', 'text-white', 'active');
                if (!b.classList.contains('btn-outline-warning') && !b.classList.contains('btn-outline-teal') &&
                    !b.classList.contains('btn-outline-purple') && !b.classList.contains('btn-outline-danger')) {
                    b.classList.add('btn-light', 'text-dark');
                }
            });
            this.classList.add('btn-mint', 'text-white', 'active');
            currentCategory = this.getAttribute('data-cat');
            buildCurrentDeck();
            showCard(0);
            cardElement.classList.remove('is-flipped');
            playTone(523.25, 0.08);
        });
    });

    buildCurrentDeck();
    showCard(0);

    // ----------------------------------------------------
    // MODUL 2: GELEMBUNG BEBAN (WORRY POP)
    // ----------------------------------------------------
    const bubbleArena = document.getElementById('bubbleArena');
    const bubbleInput = document.getElementById('bubbleTextInput');
    const btnAddBubble = document.getElementById('btnAddBubble');
    const emptyNotice = document.getElementById('emptyBubbleNotice');
    const bubbleCountBadge = document.getElementById('bubbleCountBadge');
    const presetBtns = document.querySelectorAll('.preset-bubble-btn');

    let bubbleList = [];
    const bubbleGradients = [
        'linear-gradient(135deg, rgba(13,148,136,0.85), rgba(45,212,191,0.85))',
        'linear-gradient(135deg, rgba(239,68,68,0.85), rgba(248,113,113,0.85))',
        'linear-gradient(135deg, rgba(139,92,246,0.85), rgba(192,132,252,0.85))',
        'linear-gradient(135deg, rgba(245,158,11,0.85), rgba(251,191,36,0.85))',
        'linear-gradient(135deg, rgba(59,130,246,0.85), rgba(96,165,250,0.85))'
    ];

    function addBubble(text) {
        if (!text || text.trim() === '') return;
        if (emptyNotice) emptyNotice.classList.add('d-none');

        const bubble = document.createElement('div');
        bubble.className = 'floating-bubble text-white';
        bubble.textContent = text.trim();

        // Ukuran dinamis dan posisi acak
        const size = Math.floor(80 + Math.random() * 40);
        bubble.style.width = size + 'px';
        bubble.style.height = size + 'px';
        bubble.style.background = bubbleGradients[Math.floor(Math.random() * bubbleGradients.length)];

        const maxX = Math.max(bubbleArena.clientWidth - size - 20, 20);
        const maxY = Math.max(bubbleArena.clientHeight - size - 20, 20);
        bubble.style.left = Math.floor(Math.random() * maxX) + 'px';
        bubble.style.top = Math.floor(Math.random() * maxY) + 'px';

        bubble.addEventListener('click', function () {
            popBubble(this, text);
        });

        bubbleArena.appendChild(bubble);
        bubbleList.push(bubble);
        updateBubbleCount();
        playTone(600, 0.08);
    }

    function popBubble(element, text) {
        playPop();
        // Animasi pop
        element.style.transform = 'scale(1.4)';
        element.style.opacity = '0';
        setTimeout(() => {
            element.remove();
            bubbleList = bubbleList.filter(b => b !== element);
            updateBubbleCount();
            if (bubbleList.length === 0 && emptyNotice) {
                emptyNotice.classList.remove('d-none');
            }
        }, 150);

        // Notifikasi afirmasi
        alert('✨ Beban "' + text + '" telah kamu lepaskan!\nTarik napas dalam-dalam, kamu lebih tangguh dari rasa cemasmu.');
    }

    function updateBubbleCount() {
        bubbleCountBadge.textContent = bubbleList.length + ' Gelembung';
    }

    btnAddBubble.addEventListener('click', function () {
        addBubble(bubbleInput.value);
        bubbleInput.value = '';
    });
    bubbleInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            addBubble(this.value);
            this.value = '';
        }
    });

    presetBtns.forEach(p => {
        p.addEventListener('click', function () {
            addBubble(this.textContent);
        });
    });

    // ----------------------------------------------------
    // MODUL 2: PANDUAN NAPAS ZEN (ZEN BREATH 4-7-8)
    // ----------------------------------------------------
    const breathCircle = document.getElementById('breathCircle');
    const breathInstruction = document.getElementById('breathInstruction');
    const breathTimer = document.getElementById('breathTimer');
    const btnToggleBreath = document.getElementById('btnToggleBreath');
    const breathIcon = document.getElementById('breathIcon');

    let breathInterval = null;
    let isBreathingActive = false;

    function runBreathCycle() {
        // Fase 1: Tarik napas 4 detik
        breathInstruction.textContent = 'Tarik Napas';
        breathTimer.textContent = '4 detik...';
        breathCircle.classList.remove('breath-contracting');
        breathCircle.classList.add('breath-expanding');
        playTone(392, 0.2); // G4

        setTimeout(() => {
            if (!isBreathingActive) return;
            // Fase 2: Tahan 7 detik
            breathInstruction.textContent = 'Tahan Napas';
            breathTimer.textContent = '7 detik...';
            playTone(440, 0.2); // A4

            setTimeout(() => {
                if (!isBreathingActive) return;
                // Fase 3: Hembuskan 8 detik
                breathInstruction.textContent = 'Hembuskan';
                breathTimer.textContent = '8 detik perlahan...';
                breathCircle.classList.remove('breath-expanding');
                breathCircle.classList.add('breath-contracting');
                playTone(329.63, 0.3); // E4
            }, 7000);
        }, 4000);
    }

    btnToggleBreath.addEventListener('click', function () {
        isBreathingActive = !isBreathingActive;
        if (isBreathingActive) {
            btnToggleBreath.innerHTML = '<i class="bi bi-pause-fill me-1"></i> Hentikan Latihan Napas';
            btnToggleBreath.classList.replace('btn-mint', 'btn-outline-danger');
            runBreathCycle();
            breathInterval = setInterval(runBreathCycle, 19000);
        } else {
            clearInterval(breathInterval);
            breathCircle.classList.remove('breath-expanding', 'breath-contracting');
            breathInstruction.textContent = 'Tarik Napas';
            breathTimer.textContent = 'Siap mulai';
            btnToggleBreath.innerHTML = '<i class="bi bi-play-fill me-1"></i> Mulai Latihan Napas';
            btnToggleBreath.classList.replace('btn-outline-danger', 'btn-mint');
        }
    });

    // ----------------------------------------------------
    // MODUL 3: DILEMA CERITA (CHOICE & EMPATHY)
    // ----------------------------------------------------
    const dilemmaNavBtns = document.querySelectorAll('.dilemma-nav-btn');
    const dilemmaItems = document.querySelectorAll('.dilemma-item');

    dilemmaNavBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            dilemmaNavBtns.forEach(b => {
                b.classList.remove('btn-mint', 'text-white');
                b.classList.add('btn-light', 'border', 'text-dark');
            });
            this.classList.remove('btn-light', 'border', 'text-dark');
            this.classList.add('btn-mint', 'text-white');

            const idx = this.getAttribute('data-index');
            dilemmaItems.forEach((item, i) => {
                if (i.toString() === idx) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
            playTone(493.88, 0.08);
        });
    });

    const choiceTriggers = document.querySelectorAll('.choice-trigger');
    choiceTriggers.forEach(btn => {
        btn.addEventListener('click', function () {
            const parent = this.closest('.dilemma-item');
            const feedbackBox = parent.querySelector('.feedback-box');
            const feedbackBadge = parent.querySelector('.feedback-badge');
            const feedbackText = parent.querySelector('.feedback-text');

            feedbackBadge.textContent = this.getAttribute('data-badge');
            feedbackText.textContent = this.getAttribute('data-feedback');
            feedbackBox.classList.remove('d-none');

            playTone(523.25, 0.15);
        });
    });
});
</script>
@endpush

