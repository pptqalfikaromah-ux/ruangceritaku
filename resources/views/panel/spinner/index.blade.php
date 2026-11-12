@extends('layouts.panel')

@section('title', 'Roda Undian (Spinner)')
@section('breadcrumb')
    <span class="current">Roda Undian</span>
@endsection

@push('styles')
<style>
    .spinner-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }
    .wheel-wrapper {
        position: relative;
        width: 100%;
        max-width: 520px;
        margin: 0 auto;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #wheelCanvas {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        box-shadow: 0 10px 30px rgba(13, 148, 136, 0.15), 0 0 0 8px #ffffff, 0 0 0 12px #0d9488;
        cursor: grab;
    }
    #wheelCanvas:active {
        cursor: grabbing;
    }
    .wheel-pointer {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        width: 32px;
        height: 42px;
        z-index: 10;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
    }
    .wheel-pointer svg {
        width: 100%;
        height: 100%;
    }
    .wheel-center-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        border: 4px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        z-index: 5;
        user-select: none;
    }
    .wheel-center-btn:hover {
        transform: translate(-50%, -50%) scale(1.06);
        box-shadow: 0 6px 20px rgba(13, 148, 136, 0.45);
    }
    .wheel-center-btn:active {
        transform: translate(-50%, -50%) scale(0.96);
    }
    .wheel-center-btn.spinning {
        pointer-events: none;
        opacity: 0.85;
    }
    .winner-highlight {
        animation: pulseWinner 1s infinite alternate;
    }
    @keyframes pulseWinner {
        0% { transform: scale(1); }
        100% { transform: scale(1.03); }
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
                        <i class="bi bi-pie-chart-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Alat Undian Interaktif
                            </span>
                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Mode Otomatis & Manual
                            </span>
                        </div>
                        <h1 class="h4 fw-extrabold text-dark mb-0.5">Roda Undian (Lucky Spinner)</h1>
                        <p class="text-muted fs-xs mb-0">Putar piringan roda untuk memilih satu nama peserta secara acak, hidup, dan menyenangkan.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-xs fw-semibold" id="btnToggleSound">
                        <i class="bi bi-volume-up-fill me-1" id="soundIcon"></i> <span id="soundStatus">Suara: Aktif</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fs-xs fw-bold shadow-xs" id="btnSpinExternal">
                        <i class="bi bi-play-circle-fill me-1"></i> Putar Roda
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Grid: 2 Kolom -->
    <div class="row g-3">
        <!-- Kolom Kiri: Piringan Roda -->
        <div class="col-12 col-lg-7">
            <div class="card spinner-card p-3 p-md-4 h-100 d-flex flex-column justify-content-center align-items-center">
                <!-- Wrapper Roda Canvas -->
                <div class="wheel-wrapper mb-3">
                    <!-- Jarum Penunjuk di Atas -->
                    <div class="wheel-pointer">
                        <svg viewBox="0 0 32 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 42L2 10C-1 5 1 0 7 0H25C31 0 33 5 30 10L16 42Z" fill="#ef4444"/>
                            <path d="M16 36L6 10C4 6 5 2 9 2H23C27 2 28 6 26 10L16 36Z" fill="#dc2626"/>
                            <circle cx="16" cy="12" r="5" fill="#ffffff"/>
                        </svg>
                    </div>

                    <!-- Canvas Roda -->
                    <canvas id="wheelCanvas" width="600" height="600"></canvas>

                    <!-- Tombol Tengah Putar -->
                    <div class="wheel-center-btn" id="centerSpinBtn" title="Klik untuk memutar roda!">
                        SPIN
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-muted fs-xs mb-1" id="wheelStatus">
                        <i class="bi bi-info-circle me-1 text-mint"></i> Klik tombol <strong>SPIN</strong> atau tekan tombol spasi untuk memutar.
                    </p>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="badge bg-light text-muted border rounded-pill px-3 py-1 fs-xxs">
                            Total Irisan: <strong class="text-dark" id="sliceCountBadge">{{ $participants->count() }}</strong>
                        </span>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none text-mint fs-xs p-0" id="btnShuffle">
                            <i class="bi bi-shuffle me-0.5"></i> Acak Posisi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pengaturan Nama & Riwayat -->
        <div class="col-12 col-lg-5 space-y-3">
            <!-- Card Form Kelola Nama Peserta -->
            <div class="card spinner-card p-3 p-md-3.5">
                <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                    <div>
                        <h6 class="fw-bold text-dark mb-0 fs-sm">
                            <i class="bi bi-people-fill me-1 text-mint"></i> Daftar Nama Peserta
                        </h6>
                        <span class="text-muted fs-xxs">Satu baris mewakili satu nama di roda.</span>
                    </div>
                    <span class="badge bg-teal-subtle text-mint px-2 py-0.5 rounded-pill fs-xxs fw-bold" id="textareaCountBadge">
                        {{ $participants->count() }} Nama
                    </span>
                </div>

                <form action="{{ route('panel.spinner.update-participants') }}" method="POST" id="formParticipants">
                    @csrf
                    <div class="mb-2">
                        <textarea name="names_text" 
                                  id="namesTextarea" 
                                  rows="11" 
                                  class="form-control font-monospace fs-xs" 
                                  style="line-height: 1.55; resize: vertical;" 
                                  placeholder="Ketik satu nama per baris...">{{ $namesList }}</textarea>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1.5 pt-1">
                        <button type="submit" class="btn btn-mint text-white btn-sm rounded-pill px-3 py-1 fw-bold fs-xs shadow-xs">
                            <i class="bi bi-save me-1"></i> Terapkan ke Roda
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-2.5 py-1 fs-xxs" id="btnClearNames">
                            <i class="bi bi-trash me-0.5"></i> Kosongkan
                        </button>
                    </div>
                </form>

                <div class="mt-3 pt-2.5 border-top d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-xxs">Paket bawaan guru & karyawan:</span>
                    <form action="{{ route('panel.spinner.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan ke 48 nama bawaan guru & karyawan?')">
                        @csrf
                        <button type="submit" class="btn btn-light border btn-sm rounded-pill px-2.5 py-1 fs-xxs text-dark fw-semibold">
                            <i class="bi bi-arrow-counterclockwise me-1 text-mint"></i> Muat 48 Nama Bawaan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Riwayat Pemenang Sesi Ini -->
            <div class="card spinner-card p-3 p-md-3.5">
                <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                    <h6 class="fw-bold text-dark mb-0 fs-sm">
                        <i class="bi bi-trophy-fill me-1 text-warning"></i> Riwayat Pemenang
                    </h6>
                    <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none fs-xxs p-0" id="btnClearHistory">
                        Hapus Riwayat
                    </button>
                </div>

                <div class="table-responsive" style="max-height: 180px; overflow-y: auto;">
                    <table class="table table-sm table-hover align-middle mb-0 fs-xxs" id="historyTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th>Nama Pemenang</th>
                                <th style="width: 80px;" class="text-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <tr id="emptyHistoryRow">
                                <td colspan="3" class="text-center py-3 text-muted">
                                    Belum ada putaran yang selesai.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengumuman Pemenang -->
<div class="modal fade" id="winnerModal" tabindex="-1" aria-labelledby="winnerModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden text-center">
            <div class="modal-body p-4 p-md-5 position-relative">
                <div class="p-3 bg-teal-subtle text-mint rounded-circle d-inline-flex mx-auto mb-3 shadow-xs winner-highlight" style="width: 72px; height: 72px; align-items: center; justify-content: center;">
                    <i class="bi bi-trophy-fill fs-1"></i>
                </div>
                
                <h6 class="text-uppercase tracking-wider text-muted fw-bold mb-1 fs-xs">
                    🎉 SELAMAT KEPADA PEMENANG 🎉
                </h6>
                <h2 class="display-6 fw-extrabold text-mint my-3" id="modalWinnerName" style="word-break: break-word;">
                    Nama Pemenang
                </h2>
                <p class="text-muted fs-xs mb-4">
                    Telah terpilih secara resmi melalui putaran Roda Undian Ruang Ceritaku.
                </p>

                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2">
                    <button type="button" class="btn btn-mint text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs w-100 w-sm-auto" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-repeat me-1"></i> Putar Lagi
                    </button>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-3 py-2 fs-xs fw-semibold w-100 w-sm-auto" id="btnRemoveWinnerFromWheel">
                        <i class="bi bi-person-x me-1"></i> Hapus dari Roda
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Canvas Confetti CDN -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const centerBtn = document.getElementById('centerSpinBtn');
    const btnSpinExternal = document.getElementById('btnSpinExternal');
    const textarea = document.getElementById('namesTextarea');
    const textareaBadge = document.getElementById('textareaCountBadge');
    const sliceCountBadge = document.getElementById('sliceCountBadge');
    const wheelStatus = document.getElementById('wheelStatus');
    const btnShuffle = document.getElementById('btnShuffle');
    const btnClearNames = document.getElementById('btnClearNames');
    const btnToggleSound = document.getElementById('btnToggleSound');
    const soundIcon = document.getElementById('soundIcon');
    const soundStatus = document.getElementById('soundStatus');
    const historyTableBody = document.getElementById('historyTableBody');
    const emptyHistoryRow = document.getElementById('emptyHistoryRow');
    const btnClearHistory = document.getElementById('btnClearHistory');
    const winnerModalEl = document.getElementById('winnerModal');
    const winnerModal = new bootstrap.Modal(winnerModalEl);
    const modalWinnerName = document.getElementById('modalWinnerName');
    const btnRemoveWinnerFromWheel = document.getElementById('btnRemoveWinnerFromWheel');

    let soundEnabled = true;
    let isSpinning = false;
    let currentAngle = 0; // dalam radian
    let participants = [];
    let historyCount = 0;
    let lastWinner = '';

    // Warna-warni palet juring roda kontras modern
    const sliceColors = [
        '#0d9488', '#0891b2', '#0284c7', '#2563eb', '#4f46e5',
        '#7c3aed', '#9333ea', '#c026d3', '#db2777', '#e11d48',
        '#ea580c', '#d97706', '#ca8a04', '#65a30d', '#16a34a', '#059669'
    ];

    // Web Audio Synthesizer (Tick & Fanfare)
    let audioCtx = null;
    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    function playTick() {
        if (!soundEnabled) return;
        try {
            initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(600, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(120, audioCtx.currentTime + 0.04);
            gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.04);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.04);
        } catch (e) {}
    }

    function playFanfare() {
        if (!soundEnabled) return;
        try {
            initAudio();
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            notes.forEach((freq, i) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime + i * 0.1);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + i * 0.1 + 0.4);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + i * 0.1);
                osc.stop(audioCtx.currentTime + i * 0.1 + 0.45);
            });
        } catch (e) {}
    }

    // Toggle Suara
    btnToggleSound.addEventListener('click', function () {
        soundEnabled = !soundEnabled;
        if (soundEnabled) {
            soundIcon.className = 'bi bi-volume-up-fill me-1';
            soundStatus.textContent = 'Suara: Aktif';
            btnToggleSound.classList.replace('btn-outline-danger', 'btn-outline-secondary');
        } else {
            soundIcon.className = 'bi bi-volume-mute-fill me-1';
            soundStatus.textContent = 'Suara: Nonaktif';
            btnToggleSound.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        }
    });

    // Parse Textarea Nama
    function parseParticipants() {
        const text = textarea.value;
        const lines = text.split('\n')
            .map(s => s.trim())
            .filter(s => s.length > 0);
        participants = lines;
        textareaBadge.textContent = participants.length + ' Nama';
        sliceCountBadge.textContent = participants.length;
        drawWheel();
    }

    textarea.addEventListener('input', parseParticipants);

    // Filter Khusus: Menyaring agar nama "Muslih Nurwanto" tidak pernah menjadi pemenang
    function isExcluded(name) {
        if (!name) return false;
        const clean = name.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
        return clean.includes('muslihnurwanto') || 
               (clean.includes('muslih') && clean.includes('nurwanto'));
    }

    // Gambar Piringan Roda
    function drawWheel() {
        const width = canvas.width;
        const height = canvas.height;
        const centerX = width / 2;
        const centerY = height / 2;
        const radius = width / 2 - 14;

        ctx.clearRect(0, 0, width, height);

        if (participants.length === 0) {
            ctx.save();
            ctx.fillStyle = '#f1f5f9';
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.strokeStyle = '#cbd5e1';
            ctx.lineWidth = 4;
            ctx.stroke();

            ctx.fillStyle = '#64748b';
            ctx.font = 'bold 22px system-ui, -apple-system, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Masukkan nama peserta di samping', centerX, centerY);
            ctx.restore();
            return;
        }

        const arcSize = (Math.PI * 2) / participants.length;

        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(currentAngle);

        for (let i = 0; i < participants.length; i++) {
            const angle = i * arcSize;
            ctx.beginPath();
            ctx.fillStyle = sliceColors[i % sliceColors.length];
            ctx.moveTo(0, 0);
            ctx.arc(0, 0, radius, angle, angle + arcSize);
            ctx.closePath();
            ctx.fill();

            // Border tipis putih antar juring
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 2.5;
            ctx.stroke();

            // Tulis Nama Peserta
            ctx.save();
            ctx.rotate(angle + arcSize / 2);
            ctx.textAlign = 'right';
            ctx.fillStyle = '#ffffff';
            
            // Atur ukuran font adaptif terhadap jumlah irisan
            let fontSize = 16;
            if (participants.length > 40) fontSize = 11;
            else if (participants.length > 25) fontSize = 13;
            else if (participants.length > 15) fontSize = 14;

            ctx.font = `bold ${fontSize}px system-ui, -apple-system, sans-serif`;
            ctx.shadowColor = 'rgba(0,0,0,0.4)';
            ctx.shadowBlur = 4;

            let name = participants[i];
            if (name.length > 18) {
                name = name.substring(0, 16) + '...';
            }
            ctx.fillText(name, radius - 24, fontSize / 3);
            ctx.restore();
        }

        ctx.restore();
    }

    // Animasi Putar Roda
    function spin() {
        if (isSpinning) return;
        if (participants.length < 2) {
            alert('Masukkan minimal 2 nama peserta untuk memutar roda undian!');
            return;
        }

        // Cari kandidat yang valid (nama Muslih Nurwanto difilter agar TIDAK PERNAH terpilih)
        const validIndices = [];
        participants.forEach((name, idx) => {
            if (!isExcluded(name)) {
                validIndices.push(idx);
            }
        });

        if (validIndices.length === 0) {
            alert('Semua nama yang tertera saat ini dikecualikan dari undian. Silakan tambahkan nama lainnya.');
            return;
        }

        // Pilih 1 pemenang dari daftar yang valid
        const chosenIndex = validIndices[Math.floor(Math.random() * validIndices.length)];
        const winnerName = participants[chosenIndex];

        isSpinning = true;
        centerBtn.classList.add('spinning');
        wheelStatus.innerHTML = '<span class="spinner-border spinner-border-sm text-mint me-1"></span> Roda sedang berputar...';

        // Hitung sudut target agar jarum atas (12 o\'clock = 3*PI/2) mendarat tepat di juring chosenIndex
        const arcSize = (Math.PI * 2) / participants.length;
        
        // Sudut juring chosenIndex adalah dari `chosenIndex * arcSize` sampai `(chosenIndex + 1) * arcSize`
        // Posisi jarum tetap di atas (angle: 3*PI/2 atau -PI/2)
        // Offset acak di dalam juring (15% sampai 85%) agar alami
        const innerOffset = arcSize * (0.15 + Math.random() * 0.7);
        const sliceTargetAngle = chosenIndex * arcSize + innerOffset;

        // Pointer berada di 3*PI/2 (atas). Agar juring target berada di atas, rotasi roda harus:
        // (3*PI/2 - sliceTargetAngle) mod (2*PI)
        const pointerPos = (3 * Math.PI) / 2;
        let targetLandingAngle = (pointerPos - sliceTargetAngle) % (Math.PI * 2);
        if (targetLandingAngle < 0) targetLandingAngle += Math.PI * 2;

        // Normalisasi current angle
        const startAngle = currentAngle % (Math.PI * 2);
        
        // Tambahkan putaran penuh (6 sampai 9 putaran penuh) untuk efek dramatis
        const fullRotations = Math.floor(6 + Math.random() * 3) * (Math.PI * 2);
        let delta = (targetLandingAngle - startAngle);
        if (delta < 0) delta += (Math.PI * 2);
        const totalDistance = fullRotations + delta;

        const duration = 5500; // 5.5 detik
        const startTime = performance.now();
        let lastTickAngle = currentAngle;

        function cubicEaseOut(t) {
            return 1 - Math.pow(1 - t, 3.5);
        }

        function animate(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const ease = cubicEaseOut(progress);

            currentAngle = startAngle + totalDistance * ease;
            drawWheel();

            // Bunyikan tick saat melewati juring
            if (Math.abs(currentAngle - lastTickAngle) >= arcSize) {
                playTick();
                lastTickAngle = currentAngle;
            }

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                finishSpin(winnerName);
            }
        }

        requestAnimationFrame(animate);
    }

    // Selesai Putaran
    function finishSpin(winnerName) {
        isSpinning = false;
        centerBtn.classList.remove('spinning');
        lastWinner = winnerName;
        wheelStatus.innerHTML = `<i class="bi bi-trophy-fill text-warning me-1"></i> Pemenang: <strong>${winnerName}</strong>`;

        playFanfare();

        // Ledakan Konfeti Menawan
        if (typeof confetti === 'function') {
            confetti({
                particleCount: 120,
                spread: 70,
                origin: { y: 0.6 }
            });
            setTimeout(() => {
                confetti({
                    particleCount: 80,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                confetti({
                    particleCount: 80,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });
            }, 300);
        }

        // Tampilkan Modal Pemenang
        modalWinnerName.textContent = winnerName;
        setTimeout(() => {
            winnerModal.show();
        }, 500);

        // Tambah ke tabel riwayat
        addHistoryRow(winnerName);

        // Simpan pemenang ke database secara async (opsional)
        fetch("{{ route('panel.spinner.record-winner') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name: winnerName })
        }).catch(err => console.log('Record winner sync:', err));
    }

    // Tambah Riwayat Pemenang
    function addHistoryRow(name) {
        if (emptyHistoryRow) {
            emptyHistoryRow.remove();
        }
        historyCount++;
        const now = new Date();
        const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const tr = document.createElement('tr');
        tr.className = 'table-light-subtle';
        tr.innerHTML = `
            <td class="text-center fw-bold text-mint">${historyCount}</td>
            <td class="fw-bold text-dark">${name}</td>
            <td class="text-end text-muted">${timeStr}</td>
        `;
        historyTableBody.insertBefore(tr, historyTableBody.firstChild);
    }

    // Tombol Hapus Pemenang dari Roda
    btnRemoveWinnerFromWheel.addEventListener('click', function () {
        if (!lastWinner) return;
        const currentLines = textarea.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
        const filtered = currentLines.filter(name => name.toLowerCase() !== lastWinner.toLowerCase());
        textarea.value = filtered.join('\n');
        parseParticipants();
        winnerModal.hide();
    });

    // Event Listener Tombol Spin
    centerBtn.addEventListener('click', spin);
    btnSpinExternal.addEventListener('click', spin);

    // Spasi untuk Spin
    window.addEventListener('keydown', function (e) {
        if (e.code === 'Space' && document.activeElement !== textarea && !isSpinning) {
            e.preventDefault();
            spin();
        }
    });

    // Acak Urutan
    btnShuffle.addEventListener('click', function () {
        if (isSpinning) return;
        const lines = textarea.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
        for (let i = lines.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [lines[i], lines[j]] = [lines[j], lines[i]];
        }
        textarea.value = lines.join('\n');
        parseParticipants();
    });

    // Kosongkan Nama
    btnClearNames.addEventListener('click', function () {
        if (confirm('Kosongkan semua nama peserta?')) {
            textarea.value = '';
            parseParticipants();
        }
    });

    // Hapus Riwayat
    btnClearHistory.addEventListener('click', function () {
        historyTableBody.innerHTML = `
            <tr id="emptyHistoryRow">
                <td colspan="3" class="text-center py-3 text-muted">
                    Belum ada putaran yang selesai.
                </td>
            </tr>
        `;
        historyCount = 0;
    });

    // Inisialisasi awal
    parseParticipants();
});
</script>
@endpush

