@extends('layouts.student')

@section('title', 'Instrumen Masalah Siswa (IMS)')

@section('breadcrumb')
    <a href="{{ route('murid.dashboard') }}">Beranda</a>
    <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
    <span class="active">Asesmen Masalah Siswa (IMS)</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Ringkas & Elegan -->
    <div class="card border-0 rounded-4 overflow-hidden shadow-sm position-relative" 
         style="background: linear-gradient(135deg, #00B894 0%, #008F72 70%, #00705a 100%);">
        <div class="position-absolute rounded-circle" style="width: 280px; height: 280px; background: radial-gradient(circle, rgba(255, 184, 0, 0.20) 0%, transparent 70%); top: -70px; right: -30px; pointer-events: none;"></div>
        
        <div class="card-body p-4 p-md-4.5 text-white position-relative" style="z-index: 1;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge px-3 py-1 rounded-pill fw-bold fs-xs text-dark" style="background: #FFB800;">
                            <i class="bi bi-card-checklist me-1"></i> Asesmen Kebutuhan Siswa
                        </span>
                        @if($assessment->isSubmitted())
                            <span class="badge px-2.5 py-1 rounded-pill fw-semibold fs-xs bg-white text-emerald">
                                <i class="bi bi-check-circle-fill me-1"></i> Asesmen Selesai Terkirim
                            </span>
                        @else
                            <span class="badge px-2.5 py-1 rounded-pill fw-semibold fs-xs text-white" style="background: rgba(255, 255, 255, 0.22); border: 1px solid rgba(255, 255, 255, 0.35);">
                                <i class="bi bi-pencil-square me-1" style="color: #FFD15C;"></i> Mode Pemilihan Butir
                            </span>
                        @endif
                    </div>
                    <h3 class="fw-extrabold mb-1 tracking-tight" style="font-size: clamp(1.4rem, 2.2vw, 1.85rem);">
                        Instrumen Masalah Siswa (IMS / AUM)
                    </h3>
                    <p class="text-white opacity-90 fs-xs mb-0" style="max-width: 600px; line-height: 1.55;">
                        Pilih butir-butir masalah yang sedang kamu alami pada <strong>8 Layanan Konseling</strong> di bawah secara berurutan. Jawabanmu bersifat rahasia dan aman.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2 flex-shrink-0 align-items-center">
                    @if($assessment->isSubmitted())
                        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-3 py-2 fw-bold fs-xs d-inline-flex align-items-center gap-1.5 shadow-xs">
                            <i class="bi bi-check-circle-fill"></i> Sudah Terkirim ke Guru BK
                        </span>
                    @else
                        <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-semibold fs-xs d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-pencil-square"></i> Dalam Pengisian (Draft)
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @php
        $completedCount = 0;
        foreach($serviceTypes as $s) {
            if (!empty($selectedCountByService[$s->id]) && $selectedCountByService[$s->id] > 0) {
                $completedCount++;
            }
        }
        $totalServices = $serviceTypes->count();
        $progressPct = $totalServices > 0 ? round(($completedCount / $totalServices) * 100) : 0;

        $servicesMeta = [
            'LAY-01' => [
                'icon' => 'bi-compass',
                'color' => '#2563eb',
                'bg' => '#eff6ff',
                'desc' => 'Adaptasi lingkungan sekolah, sarana ibadah, kesehatan, tata tertib, dan teman baru.',
            ],
            'LAY-02' => [
                'icon' => 'bi-info-circle',
                'color' => '#7c3aed',
                'bg' => '#f5f3ff',
                'desc' => 'Informasi kesehatan remaja, kiat belajar efektif, pemahaman diri, dan cita-cita masa depan.',
            ],
            'LAY-03' => [
                'icon' => 'bi-diagram-3',
                'color' => '#d97706',
                'bg' => '#fef3c7',
                'desc' => 'Penempatan posisi duduk, pemilihan kawan akrab/teman sebangku, penjurusan, dan rencana karir.',
            ],
            'LAY-04' => [
                'icon' => 'bi-mortarboard',
                'color' => '#059669',
                'bg' => '#ecfdf5',
                'desc' => 'Kebiasaan belajar prima, manajemen waktu, persiapan ujian, dan pengendalian emosi diri.',
            ],
            'LAY-05' => [
                'icon' => 'bi-heart-pulse',
                'color' => '#e11d48',
                'bg' => '#fff1f2',
                'desc' => 'Konseling mendalam tatap muka secara pribadi untuk masalah personal dan rasa percaya diri.',
            ],
            'LAY-06' => [
                'icon' => 'bi-people',
                'color' => '#4f46e5',
                'bg' => '#eef2ff',
                'desc' => 'Diskusi kelompok mengenai solidaritas, toleransi, bahaya pergaulan bebas, dan kerja sama.',
            ],
            'LAY-07' => [
                'icon' => 'bi-chat-quote',
                'color' => '#0d9488',
                'bg' => '#f0fdfa',
                'desc' => 'Menyelesaikan hambatan bersama dalam dinamika kelompok kecil dengan saling mendukung.',
            ],
            'LAY-08' => [
                'icon' => 'bi-person-check',
                'color' => '#334155',
                'bg' => '#f8fafc',
                'desc' => 'Mendapatkan saran bagaimana membantu sahabat, teman sebangku, atau kendala dengan pihak lain.',
            ],
            'LAY-09' => [
                'icon' => 'bi-handshake',
                'color' => '#9333ea',
                'bg' => '#faf5ff',
                'desc' => 'Penyelesaian perselisihan atau salah paham antarteman, guru, atau lingkungan keluarga.',
            ],
        ];
    @endphp

    <!-- Card Progres Pengisian Layanan -->
    <div class="card border-0 shadow-xs rounded-3 bg-white p-3 p-md-3.5">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <div class="d-flex align-items-center justify-content-between mb-1.5">
                    <span class="fs-xs fw-bold text-dark">
                        Progres Asesmen Layanan: <span class="text-emerald">{{ $completedCount }} dari {{ $totalServices }} Layanan Selesai</span>
                    </span>
                    <span class="fs-xs fw-bold text-muted">{{ $progressPct }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px; background: #E2ECE9;">
                    <div class="progress-bar rounded-pill" role="progressbar" 
                         style="width: {{ $progressPct }}%; background: linear-gradient(90deg, #00B894, #059669);" 
                         aria-valuenow="{{ $progressPct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="d-flex align-items-center justify-content-md-end gap-3 fs-xs text-muted">
                    <div>
                        <span class="fw-bold text-dark fs-sm">{{ $totalSelectedCount }}</span> Butir Dialami
                    </div>
                    <div class="vr opacity-25" style="height: 20px;"></div>
                    <div>
                        <span class="fw-bold text-warning-emphasis fs-sm">{{ $totalHeavyCount ?? count($heavyByService ?? []) }}</span> Butir Terberat
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Daftar Layanan -->
    <div class="d-flex align-items-center justify-content-between pt-1">
        <h5 class="fw-bold text-dark mb-0 fs-sm d-flex align-items-center gap-2">
            <span>Daftar 8 Layanan Bimbingan & Konseling</span>
            <span class="badge bg-light text-muted border rounded-pill fs-xxs fw-medium">Format 20 Butir / Layanan</span>
        </h5>
        <span class="text-muted fs-xs d-none d-sm-inline">Klik layanan untuk membuka butir asesmen</span>
    </div>

    <!-- DAFTAR LAYANAN BERURUT KE BAWAH (VERTICAL LIST SEPERTI ASESMEN) -->
    <div class="ims-vertical-list d-flex flex-column gap-2.5">
        @foreach($serviceTypes as $index => $svc)
            @php
                $item = $servicesMeta[$svc->code] ?? [
                    'icon' => 'bi-stars',
                    'color' => '#059669',
                    'bg' => '#ecfdf5',
                    'desc' => $svc->description ?? 'Pilihan butir masalah pada layanan konseling.',
                ];
                $answeredCount = $selectedCountByService[$svc->id] ?? 0;
                $isCompleted = $answeredCount > 0;
                $heavyNum = $heavyByService[$svc->id] ?? null;
            @endphp

            <a href="{{ route('murid.ims.service', $svc->id) }}" class="ims-list-link text-decoration-none">
                <div class="card border rounded-4 p-3.5 p-md-4 ims-service-row {{ $isCompleted ? 'ims-row-completed' : 'ims-row-pending' }}">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-sm-nowrap">
                        
                        <!-- Sisi Kiri: Nomor + Ikon + Info Layanan -->
                        <div class="d-flex align-items-center gap-3 min-w-0 flex-grow-1">
                            <!-- Nomor Urut -->
                            <div class="service-idx-circle flex-shrink-0 {{ $isCompleted ? 'idx-completed' : 'idx-pending' }}">
                                @if($isCompleted)
                                    <i class="bi bi-check-lg fw-bold"></i>
                                @else
                                    <span>{{ sprintf('%02d', $loop->iteration) }}</span>
                                @endif
                            </div>

                            <!-- Ikon Layanan -->
                            <div class="service-icon-box flex-shrink-0 d-none d-sm-flex align-items-center justify-content-center rounded-3" 
                                 style="background: {{ $item['bg'] }}; color: {{ $item['color'] }}; width: 42px; height: 42px;">
                                <i class="bi {{ $item['icon'] }} fs-5"></i>
                            </div>

                            <!-- Teks Nama Layanan & Deskripsi -->
                            <div class="min-w-0">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 class="fw-bold text-dark mb-0 service-name-title" style="letter-spacing: 0.01em;">
                                        {{ $svc->name }}
                                    </h6>
                                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                        20 Butir
                                    </span>
                                </div>
                                <p class="text-muted fs-xs mb-0 text-truncate service-desc" style="max-width: 580px; line-height: 1.5;">
                                    {{ $item['desc'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Status & Tombol Aksi -->
                        <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto ms-sm-0">
                            <!-- Badge Status -->
                            @if($isCompleted)
                                <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-3 py-1.5 fw-bold fs-xs d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>{{ $answeredCount }} Dipilih</span>
                                </span>
                                @if($heavyNum)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1.5 fw-bold fs-xs d-none d-md-inline-flex align-items-center gap-1" title="Masalah Paling Mengganggu">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span>#{{ $heavyNum }}</span>
                                    </span>
                                @endif
                                <span class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fs-xs fw-semibold d-none d-sm-inline-flex align-items-center gap-1 text-nowrap">
                                    Ubah <i class="bi bi-chevron-right" style="font-size: 0.65rem;"></i>
                                </span>
                            @else
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5 fw-medium fs-xs d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-circle"></i>
                                    <span>Belum Diisi</span>
                                </span>
                                <span class="btn btn-sm btn-emerald rounded-pill px-3 py-1.5 fs-xs fw-semibold text-white d-none d-sm-inline-flex align-items-center gap-1 text-nowrap shadow-xs">
                                    Mulai <i class="bi bi-arrow-right" style="font-size: 0.65rem;"></i>
                                </span>
                            @endif

                            <!-- Panah Mobile / Akses Cepat -->
                            <div class="row-arrow-circle d-flex align-items-center justify-content-center text-muted rounded-circle ms-1" style="width: 26px; height: 26px;">
                                <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Panel Pengiriman Asesmen di Bawah -->
    @if(!$assessment->isSubmitted())
        <div class="card border-0 rounded-4 bg-white p-4 shadow-sm text-center mt-3" style="border: 1px solid #E2ECE9 !important;">
            <div class="d-flex flex-column align-items-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2.5" style="width: 48px; height: 48px; background: #E6FAF5; color: #00B894;">
                    <i class="bi bi-send-check fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Sudah Selesai Memilih Butir Masalah?</h5>
                <p class="text-muted fs-xs mb-3" style="max-width: 520px;">
                    Kamu telah memilih <strong>{{ $totalSelectedCount }} Butir Masalah</strong> dari {{ $completedCount }} layanan. Jika kamu sudah selesai memeriksa semua layanan, silakan kirimkan asesmen ini untuk memproses <strong>Hasil Pengolahan AUM</strong>.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <form action="{{ route('murid.ims.submit') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-emerald rounded-pill px-4 py-2 fw-bold fs-xs shadow-sm" onclick="return confirm('Apakah kamu yakin sudah selesai memilih butir masalah dan ingin mengirimkan asesmen IMS ini ke Guru BK?')">
                            <i class="bi bi-check-all me-1"></i> Selesai & Kirimkan Asesmen IMS
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 rounded-4 bg-white p-4 shadow-sm text-center mt-3" style="border: 1px solid #E2ECE9 !important;">
            <div class="d-flex flex-column align-items-center">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2.5" style="width: 48px; height: 48px; background: #ecfdf5; color: #059669;">
                    <i class="bi bi-patch-check-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Asesmen IMS Kamu Telah Berhasil Terkirim</h5>
                <p class="text-muted fs-xs mb-0" style="max-width: 520px;">
                    Seluruh butir masalah yang kamu pilih telah tersimpan dengan aman dan diterima oleh Guru BK / Admin. Data ini akan dianalisis secara konfidensial untuk membantu perencanaan bimbingan dan pendampingan terbaik untukmu.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Styling Daftar Layanan Berurut ke Bawah */
    .ims-service-row {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease-in-out;
    }

    .ims-row-completed {
        border-color: #d1fae5 !important;
        background: #fafdfc;
    }

    .ims-service-row:hover {
        border-color: #00B894 !important;
        box-shadow: 0 6px 18px -3px rgba(0, 184, 148, 0.12), 0 2px 6px -2px rgba(0, 0, 0, 0.04) !important;
        transform: translateY(-1.5px);
    }

    .ims-service-row:hover .service-name-title {
        color: #008F72 !important;
    }

    .ims-service-row:hover .row-arrow-circle {
        color: #00B894 !important;
        transform: translateX(3px);
        transition: transform 0.15s ease;
    }

    /* Lingkaran Nomor Urut */
    .service-idx-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .idx-completed {
        background: #E6FAF5;
        color: #00B894;
        border: 1.5px solid #00B894;
    }

    .idx-pending {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Responsivitas Mobile */
    @media (max-width: 576px) {
        .service-desc {
            display: none !important;
        }
        .service-idx-circle {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }
        .service-name-title {
            font-size: 0.88rem !important;
            line-height: 1.35;
        }
        .ims-service-row {
            padding: 16px 18px !important;
            border-radius: 16px !important;
        }
    }
</style>
@endpush
