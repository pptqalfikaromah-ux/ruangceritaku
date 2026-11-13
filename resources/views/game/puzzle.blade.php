@extends($layout)

@section('title', 'Game Puzzle Gambar (5x5)')

@section('breadcrumb')
    <span class="current">Game Puzzle Gambar (5x5)</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-teal-subtle text-mint d-inline-flex flex-shrink-0 shadow-xs">
                        <i class="bi bi-puzzle-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-primary-teal text-white px-2.5 py-1 fs-xxs fw-bold rounded-pill">
                                🧩 Kotak 5x5 (25 Keping)
                            </span>
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                {{ $roleLabel }}
                            </span>
                        </div>
                        <h1 class="h4 fw-extrabold text-dark mb-1">Game Puzzle Gambar 5x5</h1>
                        <p class="text-muted fs-xs mb-0">Asah fokus, konsentrasi, dan relaksasi pikiran dengan menyusun 25 keping gambar menjadi utuh.</p>
                    </div>
                </div>

                @if($canManage)
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <button type="button" class="btn btn-mint text-white rounded-pill px-3.5 py-2 fw-bold shadow-xs fs-xs" data-bs-toggle="modal" data-bs-target="#modalUploadPuzzle">
                            <i class="bi bi-cloud-arrow-up-fill me-1.5"></i> Ganti Gambar Puzzle (Admin)
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Sukses / Error jika ada -->
    <x-alert />

    <!-- Main Game Area -->
    <div class="row g-3 g-lg-4">
        <!-- Kolom Kiri: Papan Puzzle Interaktif -->
        <div class="col-12 col-lg-8">
            <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                <!-- Status & Control Toolbar -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5 mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-light border">
                            <i class="bi bi-stopwatch text-mint fs-5"></i>
                            <div>
                                <span class="text-muted fs-xxs d-block" style="line-height: 1;">WAKTU</span>
                                <strong class="fs-xs text-dark font-monospace" id="timerDisplay">00:00</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill bg-light border">
                            <i class="bi bi-arrow-left-right text-purple fs-5"></i>
                            <div>
                                <span class="text-muted fs-xxs d-block" style="line-height: 1;">LANGKAH</span>
                                <strong class="fs-xs text-dark font-monospace" id="movesDisplay">0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-xs fw-bold" id="btnToggleNumbers" title="Tampilkan angka pada kepingan puzzle">
                            <i class="bi bi-123 me-1"></i> <span id="labelToggleNumbers">Tampilkan Nomor</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fs-xs fw-bold d-lg-none" data-bs-toggle="modal" data-bs-target="#modalPreviewImage" title="Lihat gambar utuh">
                            <i class="bi bi-image me-1"></i> Gambar Asli
                        </button>
                        <button type="button" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fs-xs fw-bold shadow-xs" id="btnShuffle">
                            <i class="bi bi-shuffle me-1"></i> Acak Ulang
                        </button>
                    </div>
                </div>

                <!-- Petunjuk Sentuh Singkat -->
                <div class="text-muted fs-xxs mb-3 text-center d-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-hand-index-thumb text-mint"></i>
                    <span>Ketuk/klik keping pertama, lalu ketuk keping kedua untuk menukar posisinya hingga tersusun rapi.</span>
                </div>

                <!-- Papan Puzzle (5x5 Grid) -->
                <div class="puzzle-container">
                    <div id="puzzleBoard" class="puzzle-board" role="grid" aria-label="Papan Puzzle Gambar 5x5">
                        <!-- 25 Tiles di-generate via JavaScript -->
                    </div>
                </div>

                <!-- Status Progress Penyelesaian -->
                <div class="mt-3 pt-2">
                    <div class="d-flex align-items-center justify-content-between fs-xxs text-muted mb-1">
                        <span>Ketepatan Posisi Keping</span>
                        <span id="progressPercent" class="fw-bold text-mint">0% (0 / 25 Keping)</span>
                    </div>
                    <div class="progress" style="height: 6px; border-radius: 10px;">
                        <div id="progressBar" class="progress-bar bg-mint" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Gambar Asli & Panduan Edukatif -->
        <div class="col-12 col-lg-4">
            <!-- Thumbnail Gambar Asli (Desktop) -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3 mb-3 d-none d-lg-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0 fs-xs d-flex align-items-center gap-1.5">
                        <i class="bi bi-image-fill text-mint"></i> Target Gambar Asli
                    </h6>
                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fs-xxs text-mint hover-teal" data-bs-toggle="modal" data-bs-target="#modalPreviewImage">
                        Perbesar <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
                <div class="rounded-3 overflow-hidden border bg-light position-relative" style="aspect-ratio: 1/1;">
                    <img src="{{ $puzzleImageUrl }}" alt="Target Gambar Puzzle" class="w-100 h-100" style="object-fit: cover;">
                </div>
                <div class="text-muted fs-xxs text-center mt-2">
                    Susun 25 keping puzzle agar menyerupai gambar di atas.
                </div>
            </div>

            <!-- Petunjuk Cara Bermain & Nilai Konseling -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3">
                <h6 class="fw-bold text-dark mb-2 fs-xs d-flex align-items-center gap-1.5">
                    <i class="bi bi-lightbulb-fill text-warning"></i> Manfaat &amp; Cara Bermain
                </h6>
                <ul class="list-unstyled space-y-2 mb-0 fs-xs text-muted">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-mint flex-shrink-0 mt-0.5"></i>
                        <span><strong>Menenangkan Pikiran:</strong> Fokus menyusun kepingan visual membantu meredakan stres dan kecemasan sejenak.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-mint flex-shrink-0 mt-0.5"></i>
                        <span><strong>Latih Kesabaran:</strong> Menyusun 25 keping melatih ketekunan dan kegigihan memecahkan masalah langkah demi langkah.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-mint flex-shrink-0 mt-0.5"></i>
                        <span><strong>Tombol Nomor:</strong> Jika kesulitan, aktifkan <em>"Tampilkan Nomor"</em> untuk memandu urutan kotak dari 1 sampai 25.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL 1: PREVIEW GAMBAR ASLI DIPERBESAR                 -->
<!-- ======================================================= -->
<div class="modal fade" id="modalPreviewImage" tabindex="-1" aria-labelledby="modalPreviewImageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom py-2.5 px-3">
                <h6 class="modal-title fw-bold text-dark" id="modalPreviewImageLabel">
                    <i class="bi bi-image me-1.5 text-mint"></i> Target Gambar Asli Puzzle 5x5
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <img src="{{ $puzzleImageUrl }}" alt="Target Gambar Asli" class="img-fluid rounded-3 border shadow-xs" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer border-0 p-2 justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- MODAL 2: UPLOAD GAMBAR PUZZLE (KHUSUS ADMIN / GURU BK)  -->
<!-- ======================================================= -->
@if($canManage)
<div class="modal fade" id="modalUploadPuzzle" tabindex="-1" aria-labelledby="modalUploadPuzzleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-dark fs-6" id="modalUploadPuzzleLabel">
                    <i class="bi bi-cloud-arrow-up-fill text-mint me-1.5"></i> Ganti Gambar Puzzle (Admin &amp; BK)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            
            <form action="{{ route('panel.game-konseling.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted fs-xs mb-3">
                        Upload gambar baru untuk dijadikan materi puzzle gambar 5x5. Gambar ini akan langsung aktif dan dimainkan oleh seluruh Siswa, Wali Kelas, dan Guru BK.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark fs-xs">Pilih File Foto / Ilustrasi <span class="text-danger">*</span></label>
                        <input type="file" name="puzzle_image" id="inputPuzzleFile" class="form-control fs-xs" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif" required onchange="previewUploadImage(this)">
                        <div class="form-text fs-xxs text-muted mt-1">Disarankan gambar berbentuk persegi (rasio 1:1), format JPG/PNG/WEBP, maksimal 5 MB.</div>
                    </div>

                    <!-- Live Image Preview Container -->
                    <div id="uploadPreviewBox" class="p-2 border rounded-3 bg-light text-center d-none mb-3">
                        <span class="fs-xxs text-muted d-block mb-1">Pratinjau Gambar Baru:</span>
                        <img id="uploadPreviewImg" src="" alt="Pratinjau Gambar Baru" class="rounded-3 shadow-xs" style="max-height: 180px; max-width: 100%; object-fit: contain;">
                    </div>
                </div>

                <div class="modal-footer border-top bg-light p-3 d-flex justify-content-between">
                    <div>
                        @if($hasCustomImage)
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold fs-xs" onclick="if(confirm('Kembalikan ke gambar puzzle default bawaan sistem?')) document.getElementById('formResetPuzzle').submit();">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset ke Default
                            </button>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-sm rounded-pill px-3.5 border fw-bold fs-xs" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-mint text-white btn-sm rounded-pill px-4 fw-bold shadow-xs fs-xs">
                            <i class="bi bi-check2 me-1"></i> Simpan Gambar
                        </button>
                    </div>
                </div>
            </form>

            @if($hasCustomImage)
                <form id="formResetPuzzle" action="{{ route('panel.game-konseling.reset') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endif
        </div>
    </div>
</div>
@endif

<!-- ======================================================= -->
<!-- MODAL 3: PERAYAAN KEMENANGAN / PUZZLE SOLVED           -->
<!-- ======================================================= -->
<div class="modal fade" id="modalVictory" tabindex="-1" aria-labelledby="modalVictoryLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
            <div class="mb-3">
                <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 80px; height: 80px;">
                    <i class="bi bi-trophy-fill" style="font-size: 2.5rem; color: #16a34a;"></i>
                </div>
            </div>
            
            <h4 class="fw-extrabold text-dark mb-1">Hebat Sekali! 🎉</h4>
            <p class="text-muted fs-xs mb-3">Kamu berhasil menyelesaikan seluruh 25 keping Puzzle Gambar 5x5 dengan sempurna!</p>

            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <span class="text-muted fs-xxs d-block">WAKTU PENYELESAIAN</span>
                        <strong class="fs-5 text-dark font-monospace" id="victoryTime">00:00</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2.5 rounded-3 bg-light border">
                        <span class="text-muted fs-xxs d-block">TOTAL LANGKAH</span>
                        <strong class="fs-5 text-dark font-monospace" id="victoryMoves">0</strong>
                    </div>
                </div>
            </div>

            <div class="rounded-3 overflow-hidden border mb-4 shadow-xs" style="max-height: 200px;">
                <img src="{{ $puzzleImageUrl }}" alt="Puzzle Selesai" class="w-100 h-100" style="object-fit: contain;">
            </div>

            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fs-xs fw-bold" data-bs-dismiss="modal">
                    Tutup
                </button>
                <button type="button" class="btn btn-mint text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs" id="btnPlayAgain">
                    <i class="bi bi-play-circle-fill me-1.5"></i> Main Lagi (Acak Baru)
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ======================================================= */
/* PUZZLE 5X5 STYLES & RESPONSIVE GRID                     */
/* ======================================================= */
.puzzle-container {
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
}

.puzzle-board {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(5, 1fr);
    gap: 4px;
    background: #e2e8f0;
    padding: 6px;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    aspect-ratio: 1 / 1;
    user-select: none;
    touch-action: manipulation;
}

.puzzle-tile {
    position: relative;
    border-radius: 8px;
    background-size: 500% 500%;
    background-repeat: no-repeat;
    cursor: pointer;
    transition: transform 0.16s cubic-bezier(.4,0,.2,1), box-shadow 0.16s ease, border-color 0.16s ease;
    border: 2px solid rgba(255, 255, 255, 0.85);
    box-shadow: 0 2px 5px rgba(15, 23, 42, 0.08);
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    overflow: hidden;
}

.puzzle-tile:hover {
    transform: scale(1.03);
    box-shadow: 0 4px 10px rgba(13, 148, 136, 0.3);
}

.puzzle-tile.selected {
    transform: scale(1.08);
    border: 3px solid #0d9488 !important;
    box-shadow: 0 0 18px rgba(13, 148, 136, 0.7) !important;
    z-index: 10;
    animation: pulse-teal 1.2s infinite;
}

@keyframes pulse-teal {
    0% { box-shadow: 0 0 0 0 rgba(13, 148, 136, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(13, 148, 136, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 148, 136, 0); }
}

.puzzle-tile.correct-spot::after {
    content: '✓';
    position: absolute;
    bottom: 2px;
    right: 3px;
    background: rgba(34, 197, 94, 0.88);
    color: #ffffff;
    font-size: 0.6rem;
    font-weight: 900;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    pointer-events: none;
}

.tile-number {
    position: absolute;
    top: 2px;
    left: 2px;
    background: rgba(15, 23, 42, 0.75);
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 1px 4px;
    border-radius: 4px;
    pointer-events: none;
    backdrop-filter: blur(2px);
    line-height: 1.2;
}

/* Canvas Confetti */
.confetti-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
    z-index: 99999;
}
</style>

@push('scripts')
<script>
(function() {
    const GRID_SIZE = 5;
    const TOTAL_TILES = GRID_SIZE * GRID_SIZE; // 25
    const PUZZLE_IMG_URL = @json($puzzleImageUrl);

    let tiles = []; // array of 25 numbers: tiles[pos] = pieceId
    let selectedPos = null;
    let moves = 0;
    let timerSeconds = 0;
    let timerInterval = null;
    let isTimerRunning = false;
    let isSolved = false;
    let showNumbers = false;

    const boardEl = document.getElementById('puzzleBoard');
    const movesEl = document.getElementById('movesDisplay');
    const timerEl = document.getElementById('timerDisplay');
    const progressPercentEl = document.getElementById('progressPercent');
    const progressBarEl = document.getElementById('progressBar');
    const btnShuffle = document.getElementById('btnShuffle');
    const btnToggleNumbers = document.getElementById('btnToggleNumbers');
    const labelToggleNumbers = document.getElementById('labelToggleNumbers');
    const btnPlayAgain = document.getElementById('btnPlayAgain');

    // Initialize Game
    function initGame() {
        stopTimer();
        timerSeconds = 0;
        moves = 0;
        isSolved = false;
        selectedPos = null;
        updateTimerDisplay();
        updateMovesDisplay();

        // Target array: 0, 1, 2, ..., 24
        tiles = Array.from({ length: TOTAL_TILES }, (_, i) => i);

        // Shuffle tiles
        shuffleTiles();

        renderBoard();
        updateProgress();
    }

    // Shuffle tiles ensuring it's not solved
    function shuffleTiles() {
        do {
            for (let i = tiles.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [tiles[i], tiles[j]] = [tiles[j], tiles[i]];
            }
        } while (checkIfSolved());
    }

    // Render Board
    function renderBoard() {
        boardEl.innerHTML = '';

        tiles.forEach((pieceId, pos) => {
            const tile = document.createElement('div');
            tile.className = 'puzzle-tile';
            tile.dataset.pos = pos;
            tile.dataset.piece = pieceId;

            // Compute background position for pieceId (0..24)
            // 5 columns, 5 rows -> col: pieceId % 5, row: Math.floor(pieceId / 5)
            // Percentage: col * 25%, row * 25% (step is 100 / (5 - 1) = 25%)
            const col = pieceId % GRID_SIZE;
            const row = Math.floor(pieceId / GRID_SIZE);
            const posX = col * 25;
            const posY = row * 25;

            tile.style.backgroundImage = `url('${PUZZLE_IMG_URL}')`;
            tile.style.backgroundPosition = `${posX}% ${posY}%`;

            if (pieceId === pos) {
                tile.classList.add('correct-spot');
            }

            if (selectedPos === pos) {
                tile.classList.add('selected');
            }

            // Tile number helper (1-based: 1..25)
            if (showNumbers) {
                const numBadge = document.createElement('span');
                numBadge.className = 'tile-number';
                numBadge.textContent = pieceId + 1;
                tile.appendChild(numBadge);
            }

            tile.addEventListener('click', () => onTileClick(pos));
            boardEl.appendChild(tile);
        });
    }

    // Tile Click Handler
    function onTileClick(pos) {
        if (isSolved) return;

        // Start timer on first move
        if (!isTimerRunning) {
            startTimer();
        }

        if (selectedPos === null) {
            // Select first tile
            selectedPos = pos;
            renderBoard();
        } else if (selectedPos === pos) {
            // Deselect tile
            selectedPos = null;
            renderBoard();
        } else {
            // Swap tiles
            const pos1 = selectedPos;
            const pos2 = pos;

            [tiles[pos1], tiles[pos2]] = [tiles[pos2], tiles[pos1]];
            selectedPos = null;
            moves++;
            updateMovesDisplay();

            renderBoard();
            updateProgress();

            // Play gentle swap tick
            playTickSound();

            // Check if game won
            if (checkIfSolved()) {
                handleVictory();
            }
        }
    }

    // Check if puzzle is solved
    function checkIfSolved() {
        for (let i = 0; i < TOTAL_TILES; i++) {
            if (tiles[i] !== i) return false;
        }
        return true;
    }

    // Update Progress
    function updateProgress() {
        let correctCount = 0;
        for (let i = 0; i < TOTAL_TILES; i++) {
            if (tiles[i] === i) correctCount++;
        }
        const percent = Math.round((correctCount / TOTAL_TILES) * 100);
        progressPercentEl.textContent = `${percent}% (${correctCount} / 25 Keping)`;
        progressBarEl.style.width = `${percent}%`;
        progressBarEl.setAttribute('aria-valuenow', percent);
    }

    // Handle Victory
    function handleVictory() {
        isSolved = true;
        stopTimer();

        // Play victory chime
        playVictorySound();

        // Launch celebratory confetti
        launchConfetti();

        // Set victory modal data
        document.getElementById('victoryTime').textContent = timerEl.textContent;
        document.getElementById('victoryMoves').textContent = moves;

        setTimeout(() => {
            const victoryModal = new bootstrap.Modal(document.getElementById('modalVictory'));
            victoryModal.show();
        }, 350);
    }

    // Timer Controls
    function startTimer() {
        if (isTimerRunning) return;
        isTimerRunning = true;
        timerInterval = setInterval(() => {
            timerSeconds++;
            updateTimerDisplay();
        }, 1000);
    }

    function stopTimer() {
        isTimerRunning = false;
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }

    function updateTimerDisplay() {
        const mins = Math.floor(timerSeconds / 60);
        const secs = timerSeconds % 60;
        timerEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    function updateMovesDisplay() {
        movesEl.textContent = moves;
    }

    // Audio Synthesizer (Web Audio API - No external files needed)
    function playTickSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(480, ctx.currentTime);
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08);
            osc.start();
            osc.stop(ctx.currentTime + 0.08);
        } catch(e) {}
    }

    function playVictorySound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            notes.forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + idx * 0.12);
                gain.gain.setValueAtTime(0.18, ctx.currentTime + idx * 0.12);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + idx * 0.12 + 0.35);
                osc.start(ctx.currentTime + idx * 0.12);
                osc.stop(ctx.currentTime + idx * 0.12 + 0.35);
            });
        } catch(e) {}
    }

    // Confetti Generator
    function launchConfetti() {
        const canvas = document.createElement('canvas');
        canvas.className = 'confetti-canvas';
        document.body.appendChild(canvas);
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors = ['#0d9488', '#14b8a6', '#f59e0b', '#ec4899', '#3b82f6', '#8b5cf6'];
        const particles = Array.from({ length: 90 }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height - canvas.height,
            size: Math.random() * 8 + 4,
            speedY: Math.random() * 4 + 2,
            speedX: Math.random() * 3 - 1.5,
            color: colors[Math.floor(Math.random() * colors.length)],
            rotation: Math.random() * 360,
            rotationSpeed: Math.random() * 8 - 4
        }));

        let animationFrame;
        const startTime = Date.now();

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.y += p.speedY;
                p.x += p.speedX;
                p.rotation += p.rotationSpeed;

                ctx.save();
                ctx.translate(p.x, p.y);
                ctx.rotate((p.rotation * Math.PI) / 180);
                ctx.fillStyle = p.color;
                ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
                ctx.restore();
            });

            if (Date.now() - startTime < 4000) {
                animationFrame = requestAnimationFrame(draw);
            } else {
                cancelAnimationFrame(animationFrame);
                canvas.remove();
            }
        }
        draw();
    }

    // Event Listeners
    btnShuffle.addEventListener('click', initGame);

    btnToggleNumbers.addEventListener('click', () => {
        showNumbers = !showNumbers;
        labelToggleNumbers.textContent = showNumbers ? 'Sembunyikan Nomor' : 'Tampilkan Nomor';
        btnToggleNumbers.classList.toggle('btn-mint', showNumbers);
        btnToggleNumbers.classList.toggle('text-white', showNumbers);
        btnToggleNumbers.classList.toggle('btn-outline-secondary', !showNumbers);
        renderBoard();
    });

    btnPlayAgain.addEventListener('click', () => {
        const victoryModalEl = document.getElementById('modalVictory');
        const modalInstance = bootstrap.Modal.getInstance(victoryModalEl);
        if (modalInstance) modalInstance.hide();
        initGame();
    });

    // Start on Page Load
    initGame();
})();

// Preview Upload Image in Modal
function previewUploadImage(input) {
    const previewBox = document.getElementById('uploadPreviewBox');
    const previewImg = document.getElementById('uploadPreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewBox.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        previewBox.classList.add('d-none');
    }
}
</script>
@endpush
@endsection
