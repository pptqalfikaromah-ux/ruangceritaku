@extends('layouts.student')

@section('title', 'Ajukan Konseling')

@section('breadcrumb')
    <span class="active">Ajukan Konseling</span>
@endsection

@section('content')
<div class="space-y-4 pb-5">
    <!-- Header Banner -->
    <div class="card border-0 rounded-4 overflow-hidden shadow-sm position-relative mb-4" 
         style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 65%, #115e59 100%);">
        <div class="position-absolute rounded-circle" style="width: 280px; height: 280px; background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, transparent 70%); top: -70px; right: -40px; pointer-events: none;"></div>
        <div class="card-body p-4 p-md-5 text-white position-relative" style="z-index: 1;">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3);">
                        <i class="bi bi-shield-check text-warning"></i>
                        <span class="fs-xs fw-bold tracking-wide text-uppercase">Ruang Aman Bimbingan & Konseling</span>
                    </div>
                    <h1 class="fw-extrabold mb-2" style="font-size: clamp(1.5rem, 2.5vw, 2.1rem); letter-spacing: -0.02em;">
                        Pilih Jalur Konseling yang Nyaman Bagimu
                    </h1>
                    <p class="text-white opacity-90 fs-sm mb-0" style="max-width: 620px; line-height: 1.6;">
                        Setiap masalah layak didengarkan dan didampingi. Kamu bisa langsung menceritakan masalahmu dengan kata-katamu sendiri, atau memanfaatkan instrumen asesmen masalah (IMS) untuk pemetaan yang terstruktur.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end d-none d-lg-block">
                    <div class="p-3.5 rounded-4 d-inline-block text-start" style="background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.22); max-width: 270px;">
                        <div class="d-flex align-items-center gap-2 mb-1.5 text-warning fw-bold fs-xs">
                            <i class="bi bi-lock-fill"></i> 100% Rahasia & Aman
                        </div>
                        <div class="fs-xs text-white opacity-90 lh-base">
                            Ceritamu hanya dibaca oleh Guru BK pembina untuk mendampingi dan memberikan solusi terbaik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Pintu Pilihan Konseling -->
    <div class="row g-4">
        <!-- Pilihan 1: Konseling Pribadi -->
        <div class="col-md-6">
            <div class="card h-100 border-0 rounded-4 bg-white shadow-sm p-4 p-lg-4 d-flex flex-column position-relative overflow-hidden transition-all hover-translate-up"
                 style="border: 2px solid #ccfbf1 !important;">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge rounded-pill px-3 py-1.5 fs-xs fw-bold" style="background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Curhat Bebas & Cepat
                    </span>
                </div>

                <div class="d-flex align-items-center justify-content-center rounded-4 mb-3.5"
                     style="width: 58px; height: 58px; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: #fff; font-size: 1.6rem; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);">
                    <i class="bi bi-chat-heart-fill"></i>
                </div>

                <h3 class="fw-extrabold text-dark mb-1.5" style="font-size: 1.35rem;">
                    Bimbingan / Konseling Pribadi
                </h3>
                <div class="text-mint fw-bold fs-xs mb-3">Tulis Cerita Masalahmu Sendiri</div>

                <p class="text-muted fs-xs mb-4 flex-grow-1" style="line-height: 1.65;">
                    Ceritakan langsung apa yang sedang kamu rasakan atau alami dengan kalimatmu sendiri tanpa batasan format. Kamu juga bisa mencantumkan butir IMS jika dirasa relevan.
                </p>

                <div class="p-3 rounded-3 bg-light border mb-4 fs-xs text-muted">
                    <div class="d-flex align-items-center gap-2 mb-1 text-dark fw-bold">
                        <i class="bi bi-check2-circle text-mint fs-6"></i> Keunggulan Jalur Pribadi:
                    </div>
                    <ul class="mb-0 ps-3 space-y-1">
                        <li>Bebas menulis masalah apa saja dengan kata-katamu sendiri.</li>
                        <li>Dapat memilih tatap muka di ruang BK atau online meet.</li>
                        <li>Opsional: Bisa menyertakan butir IMS jika menginginkannya.</li>
                    </ul>
                </div>

                <a href="{{ route('murid.konseling.create') }}" class="btn btn-mint w-100 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <span>Mulai Cerita / Curhat Pribadi</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Pilihan 2: Konseling Melalui IMS -->
        <div class="col-md-6">
            <div class="card h-100 border-0 rounded-4 bg-white shadow-sm p-4 p-lg-4 d-flex flex-column position-relative overflow-hidden transition-all hover-translate-up"
                 style="border: 2px solid #e0e7ff !important;">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge rounded-pill px-3 py-1.5 fs-xs fw-bold" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">
                        <i class="bi bi-clipboard2-check-fill me-1"></i>Asesmen Terstruktur
                    </span>
                </div>

                <div class="d-flex align-items-center justify-content-center rounded-4 mb-3.5"
                     style="width: 58px; height: 58px; background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); color: #fff; font-size: 1.6rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);">
                    <i class="bi bi-clipboard2-check"></i>
                </div>

                <h3 class="fw-extrabold text-dark mb-1.5" style="font-size: 1.35rem;">
                    Konseling Melalui IMS
                </h3>
                <div class="text-indigo fw-bold fs-xs mb-3" style="color: #4f46e5;">Instrumen Masalah Siswa Resmi</div>

                <p class="text-muted fs-xs mb-4 flex-grow-1" style="line-height: 1.65;">
                    Kenali dan petakan kendalamu secara terstruktur melalui lembar instrumen kebutuhan (Orientasi, Informasi, Penempatan, dsb.) lalu ajukan jadwal konseling dari temuan masalah tersebut.
                </p>

                <div class="p-3 rounded-3 bg-light border mb-4 fs-xs text-muted">
                    <div class="d-flex align-items-center gap-2 mb-1 text-dark fw-bold">
                        <i class="bi bi-check2-circle text-indigo fs-6" style="color: #4f46e5;"></i> Keunggulan Jalur IMS:
                    </div>
                    <ul class="mb-0 ps-3 space-y-1">
                        <li>Membantu memetakan masalah yang mungkin sulit kamu ungkapkan.</li>
                        <li>Mencakup 4 bidang (Pribadi, Sosial, Belajar, Karier).</li>
                        <li>Guru BK langsung membaca rangkuman butir masalahmu.</li>
                    </ul>
                </div>

                <a href="{{ route('murid.ims.index') }}" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #4f46e5; border-color: #4f46e5;">
                    <span>Buka Lembar Instrumen IMS</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
