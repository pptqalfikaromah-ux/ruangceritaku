@extends('layouts.student')

@section('title', 'Instrumen Masalah Siswa (IMS)')

@section('breadcrumb')
    <a href="{{ route('murid.konseling.ajukan') }}">Ajukan Konseling</a>
    <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
    <span class="active">Instrumen Masalah (IMS)</span>
@endsection

@section('content')
<div class="space-y-5">
    <!-- Hero Banner Modern (Mint & Sunny Gold) -->
    <div class="card border-0 rounded-4 overflow-hidden shadow-sm position-relative mb-4" 
         style="background: linear-gradient(135deg, #00B894 0%, #008F72 65%, #00705a 100%);">
        <!-- Ambient Glow Circles -->
        <div class="position-absolute rounded-circle" style="width: 320px; height: 320px; background: radial-gradient(circle, rgba(255, 184, 0, 0.22) 0%, transparent 70%); top: -80px; right: -40px; pointer-events: none;"></div>
        <div class="position-absolute rounded-circle" style="width: 240px; height: 240px; background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%); bottom: -60px; left: 20%; pointer-events: none;"></div>

        <div class="card-body p-4 p-md-5 text-white position-relative" style="z-index: 1;">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge px-3 py-1.5 rounded-pill fw-bold fs-xs text-slate-900" style="background: #FFB800; border: 1px solid #FFE082;">
                            <i class="bi bi-sparkles me-1 text-dark"></i> Instrumen Kebutuhan BK
                        </span>
                        @if($assessment->isSubmitted())
                            <span class="badge px-3 py-1.5 rounded-pill fw-semibold fs-xs text-white" style="background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(255, 255, 255, 0.4);">
                                <i class="bi bi-check-circle-fill me-1 text-warning"></i> Lembar Sudah Dikirimkan ke Guru BK
                            </span>
                        @else
                            <span class="badge px-3 py-1.5 rounded-pill fw-semibold fs-xs text-white" style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-pencil-square me-1" style="color: #FFD15C;"></i> Mode Pengisian Aktif
                            </span>
                        @endif
                    </div>

                    <h2 class="fw-extrabold mb-2 tracking-tight" style="font-size: clamp(1.6rem, 2.8vw, 2.2rem);">
                        Peta Masalah & Kebutuhan Siswa (IMS)
                    </h2>
                    <p class="text-white opacity-90 fs-sm mb-4" style="max-width: 620px; line-height: 1.65;">
                        Kenali dan pilih kendala yang sedang kamu hadapi melalui <strong>{{ $serviceTypes->count() }} Rumpun Layanan Konseling</strong> di bawah. Setiap layanan memuat 4 bidang masalah (Pribadi, Sosial, Belajar, Karir). Ceritakan dengan jujur, kerahasiaanmu 100% aman terjamin.
                    </p>

                    <div class="d-flex flex-wrap gap-2.5">
                        <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-gold btn-sm rounded-pill px-4 py-2.5 fw-bold shadow-sm" style="color: #1e293b;">
                            <i class="bi bi-send-check-fill me-1.5"></i> Ajukan Konseling dari Temuan IMS &rarr;
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="d-inline-block text-start p-3.5 rounded-4" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.25); min-width: 240px;">
                        <div class="d-flex align-items-center gap-2 mb-2 text-white opacity-85 fs-xs fw-semibold text-uppercase">
                            <i class="bi bi-shield-check fs-6" style="color: #FFD15C;"></i> Etika Kerahasiaan BK
                        </div>
                        <div class="small text-white fw-medium lh-base mb-2">
                            Setiap butir masalah dan catatan curhat yang kamu isi hanya dibaca oleh Guru BK untuk membantumu.
                        </div>
                        <div class="d-flex align-items-center gap-2 pt-2 border-top border-white border-opacity-20 fs-xs fw-bold" style="color: #FFD15C;">
                            <i class="bi bi-lock-fill"></i> Privasi Siswa Terlindungi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title & 9 Service Grid -->
    <div class="mb-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <span>{{ $serviceTypes->count() }} Menu Layanan Konseling</span>
                <span class="badge bg-light text-muted border rounded-pill fs-xs fw-semibold">Pilih Menu Layanan Konseling</span>
            </h4>
            <p class="text-muted fs-xs mb-0 mt-0.5">Klik salah satu kotak layanan di bawah untuk membuka pilihan 4 bidang bimbingan.</p>
        </div>
    </div>

    @php
        $servicesData = [
            'LAY-01' => [
                'icon' => 'bi-compass',
                'grad' => 'linear-gradient(135deg, #0284c7 0%, #2563eb 100%)',
                'color' => '#2563eb',
                'bg_subtle' => '#eff6ff',
                'short' => 'Layanan Orientasi',
                'desc' => 'Adaptasi lingkungan sekolah, fasilitas ibadah, kesehatan, tata tertib, dan teman baru.',
            ],
            'LAY-02' => [
                'icon' => 'bi-info-circle',
                'grad' => 'linear-gradient(135deg, #7c3aed 0%, #6366f1 100%)',
                'color' => '#7c3aed',
                'bg_subtle' => '#f5f3ff',
                'short' => 'Layanan Informasi',
                'desc' => 'Informasi kesehatan remaja, kiat belajar efektif, pemahaman diri, dan rencana masa depan.',
            ],
            'LAY-03' => [
                'icon' => 'bi-grid-1x2',
                'grad' => 'linear-gradient(135deg, #ea580c 0%, #d97706 100%)',
                'color' => '#ea580c',
                'bg_subtle' => '#fff7ed',
                'short' => 'Penempatan & Penyaluran',
                'desc' => 'Posisi tempat duduk di kelas, kelompok belajar, ekstrakurikuler, serta minat dan bakat.',
            ],
            'LAY-04' => [
                'icon' => 'bi-mortarboard',
                'grad' => 'linear-gradient(135deg, #059669 0%, #0d9488 100%)',
                'color' => '#059669',
                'bg_subtle' => '#ecfdf5',
                'short' => 'Penguasaan Konten',
                'desc' => 'Mengembangkan kebiasaan belajar prima, manajemen waktu, teknik ujian, dan mengelola emosi.',
            ],
            'LAY-05' => [
                'icon' => 'bi-heart-pulse',
                'grad' => 'linear-gradient(135deg, #e11d48 0%, #db2777 100%)',
                'color' => '#e11d48',
                'bg_subtle' => '#fff1f2',
                'short' => 'Konseling Perorangan',
                'desc' => 'Konseling mendalam tatap muka secara pribadi untuk masalah personal dan rasa percaya diri.',
            ],
            'LAY-06' => [
                'icon' => 'bi-people',
                'grad' => 'linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%)',
                'color' => '#4f46e5',
                'bg_subtle' => '#eef2ff',
                'short' => 'Bimbingan Kelompok',
                'desc' => 'Diskusi kelompok mengenai solidaritas, toleransi, bahaya pergaulan bebas, dan kerja sama.',
            ],
            'LAY-07' => [
                'icon' => 'bi-chat-quote',
                'grad' => 'linear-gradient(135deg, #0d9488 0%, #0891b2 100%)',
                'color' => '#0d9488',
                'bg_subtle' => '#f0fdfa',
                'short' => 'Konseling Kelompok',
                'desc' => 'Menyelesaikan hambatan bersama dalam dinamika kelompok kecil dengan saling mendukung.',
            ],
            'LAY-08' => [
                'icon' => 'bi-person-check',
                'grad' => 'linear-gradient(135deg, #334155 0%, #475569 100%)',
                'color' => '#334155',
                'bg_subtle' => '#f8fafc',
                'short' => 'Layanan Konsultasi',
                'desc' => 'Mendapatkan saran bagaimana membantu sahabat, teman sebangku, atau kendala dengan pihak lain.',
            ],
            'LAY-09' => [
                'icon' => 'bi-handshake',
                'grad' => 'linear-gradient(135deg, #9333ea 0%, #c026d3 100%)',
                'color' => '#9333ea',
                'bg_subtle' => '#faf5ff',
                'short' => 'Layanan Mediasi',
                'desc' => 'Penyelesaian perselisihan atau salah paham antarteman, guru, atau lingkungan keluarga.',
            ],
        ];
    @endphp

    <div class="row g-3 g-lg-4">
        @foreach($serviceTypes as $svc)
            @php
                $itemData = $servicesData[$svc->code] ?? $servicesData[$svc->id] ?? [
                    'icon' => 'bi-stars',
                    'grad' => 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
                    'color' => '#059669',
                    'bg_subtle' => '#ecfdf5',
                    'short' => $svc->name,
                    'desc' => $svc->description ?? 'Pilihan layanan bimbingan konseling peserta didik.',
                ];
                $answeredCount = $selectedCountByService[$svc->id] ?? 0;
            @endphp
            <div class="col-lg-4 col-md-6 col-12">
                <a href="{{ route('murid.ims.service', $svc->id) }}" class="text-decoration-none h-100 d-block">
                    <div class="card h-100 border-0 rounded-4 bg-white p-4 modern-service-card position-relative overflow-hidden">
                        <!-- Top Accent Line -->
                        <div class="card-top-accent" style="background: {{ $itemData['grad'] }};"></div>

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <!-- Gradient Icon Circle -->
                            <div class="modern-service-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm"
                                 style="background: {{ $itemData['grad'] }};">
                                <i class="bi {{ $itemData['icon'] }} fs-4"></i>
                            </div>

                            <!-- Status Badge -->
                            @if($answeredCount > 0)
                                <span class="badge rounded-pill px-2.5 py-1.5 fs-xs fw-semibold text-white shadow-xs" 
                                      style="background: #059669;">
                                    <i class="bi bi-check2-circle me-1"></i> Ada Pilihan
                                </span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xs">
                                    Siap Diisi
                                </span>
                            @endif
                        </div>

                        <!-- Service Title -->
                        <div class="mb-2">
                            <span class="fs-xs fw-bold text-uppercase tracking-wider text-muted d-block mb-1">
                                Layanan 0{{ $loop->iteration }}
                            </span>
                            <h5 class="fw-bold text-dark mb-1 card-title-hover" style="letter-spacing: -0.01em;">
                                {{ str_replace('Layanan ', '', $svc->name) }}
                            </h5>
                        </div>

                        <!-- Description -->
                        <p class="text-muted fs-xs mb-4 flex-grow-1" style="line-height: 1.6;">
                            {{ $itemData['desc'] }}
                        </p>

                        <!-- Card Footer Action -->
                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="fs-xs fw-bold card-action-text" style="color: {{ $itemData['color'] }};">
                                Pilih 4 Bidang Masalah &rarr;
                            </span>
                            <div class="action-arrow-circle d-flex align-items-center justify-content-center rounded-circle" 
                                 style="background: {{ $itemData['bg_subtle'] }}; color: {{ $itemData['color'] }}; width: 28px; height: 28px;">
                                <i class="bi bi-chevron-right fs-xs"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    .modern-service-card {
        box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-top-accent {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .modern-service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: #cbd5e1 !important;
    }

    .modern-service-card:hover .card-top-accent {
        opacity: 1;
    }

    .modern-service-card:hover .action-arrow-circle {
        transform: translateX(3px);
        transition: transform 0.2s ease;
    }

    .modern-service-icon {
        width: 48px;
        height: 48px;
    }
</style>
@endpush
