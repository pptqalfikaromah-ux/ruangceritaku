@extends('layouts.student')

@section('title', 'Pilih Bidang Masalah - ' . $service->name)

@section('breadcrumb')
    <a href="{{ route('murid.ims.index') }}">IMS</a>
    <span>/</span>
    <span class="active">{{ $service->name }}</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Step Navigation Indicator -->
    <div class="card border-0 rounded-4 bg-white p-3 shadow-xs mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('murid.ims.index') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1.5 fs-xs fw-semibold border">
                    <i class="bi bi-arrow-left me-1"></i> Ganti Layanan
                </a>
                <span class="text-muted fs-xs d-none d-sm-inline">|</span>
                <span class="fs-xs text-muted fw-medium d-none d-sm-inline">Alur Pengisian Instrumen Masalah</span>
            </div>

            <div class="d-flex align-items-center gap-2 fs-xs">
                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">
                    <i class="bi bi-check-circle-fill me-1"></i>1. Layanan
                </span>
                <i class="bi bi-chevron-right text-muted fs-xxs"></i>
                <span class="badge bg-emerald text-white rounded-pill px-3 py-1 fw-bold shadow-xs">
                    2. Pilih Bidang
                </span>
                <i class="bi bi-chevron-right text-muted fs-xxs"></i>
                <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1">
                    3. Isi 5 Butir & Catatan
                </span>
            </div>
        </div>
    </div>

    <!-- Banner Layanan Terpilih -->
    <div class="card border-0 rounded-4 bg-white p-4 shadow-sm mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="service-active-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm"
                     style="width: 52px; height: 52px; background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                    <i class="bi bi-bookmark-star fs-3"></i>
                </div>
                <div>
                    <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2.5 py-0.5 fs-xs fw-semibold mb-1">
                        Layanan Terpilih
                    </span>
                    <h3 class="fw-extrabold text-dark mb-0 tracking-tight">{{ $service->name }}</h3>
                    <p class="text-muted fs-xs mb-0 mt-1">
                        Silakan pilih 1 dari 4 <strong>Bidang Masalah</strong> di bawah untuk membuka lembar instrumen 5 butir dan catatan lebar.
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-sm btn-outline-purple rounded-pill px-3.5 py-2 fw-semibold" style="border-color: #7c3aed; color: #7c3aed;">
                    <i class="bi bi-calendar-check me-1"></i> Jadwalkan Konseling IMS
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KARTU BIDANG BIMBINGAN -->
    @php
        $fieldsConfig = [
            'Pribadi' => [
                'icon' => 'bi-person-heart',
                'grad' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
                'color' => '#1d4ed8',
                'bg_subtle' => '#eff6ff',
                'badge' => 'Bidang Personal',
                'desc' => 'Keluhan terkait diri sendiri, kesehatan fisik & mental, ibadah, emosi, kecemasan, atau manajemen waktu luang.',
            ],
            'Sosial' => [
                'icon' => 'bi-people',
                'grad' => 'linear-gradient(135deg, #059669 0%, #0d9488 100%)',
                'color' => '#059669',
                'bg_subtle' => '#ecfdf5',
                'badge' => 'Bidang Relasi',
                'desc' => 'Hubungan dengan teman sekelas, pertemanan, adaptasi sosial, rasa terasing, atau keharmonisan keluarga.',
            ],
            'Belajar' => [
                'icon' => 'bi-journal-bookmark',
                'grad' => 'linear-gradient(135deg, #d97706 0%, #b45309 100%)',
                'color' => '#d97706',
                'bg_subtle' => '#fffbeb',
                'badge' => 'Bidang Akademik',
                'desc' => 'Kesulitan memahami materi pelajaran, rasa malas, konsentrasi belajar, tugas menumpuk, atau persiapan ujian.',
            ],
            'Karier' => [
                'icon' => 'bi-briefcase',
                'grad' => 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)',
                'color' => '#7c3aed',
                'bg_subtle' => '#f5f3ff',
                'badge' => 'Bidang Masa Depan',
                'desc' => 'Pilihan ekstrakurikuler, cita-cita masa depan, minat bakat keahlian, dan rencana studi lanjut ke jenjang atas.',
            ],
            'Karir' => [
                'icon' => 'bi-briefcase',
                'grad' => 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)',
                'color' => '#7c3aed',
                'bg_subtle' => '#f5f3ff',
                'badge' => 'Bidang Masa Depan',
                'desc' => 'Pilihan ekstrakurikuler, cita-cita masa depan, minat bakat keahlian, dan rencana studi lanjut ke jenjang atas.',
            ],
        ];
    @endphp

    <div class="row g-4">
        @foreach($guidanceFields as $fld)
            @php
                $cfg = $fieldsConfig[$fld->name] ?? [
                    'icon' => 'bi-circle',
                    'grad' => 'linear-gradient(135deg, #64748b 0%, #475569 100%)',
                    'color' => '#475569',
                    'bg_subtle' => '#f8fafc',
                    'badge' => 'Bidang BK',
                    'desc' => 'Permasalahan bimbingan konseling.',
                ];
                $answeredCount = $selectedCountByField[$fld->id] ?? 0;
            @endphp
            <div class="col-md-6 col-12">
                <a href="{{ route('murid.ims.instrument', [$service->id, $fld->id]) }}" class="text-decoration-none h-100 d-block">
                    <div class="card h-100 border-0 rounded-4 bg-white p-4 modern-field-card d-flex flex-column justify-content-between position-relative overflow-hidden shadow-sm">
                        <!-- Left Accent Bar -->
                        <div class="field-left-accent" style="background: {{ $cfg['grad'] }};"></div>

                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="field-icon-box rounded-3 d-flex align-items-center justify-content-center text-white shadow-xs"
                                     style="background: {{ $cfg['grad'] }};">
                                    <i class="bi {{ $cfg['icon'] }} fs-4"></i>
                                </div>

                                @if($answeredCount > 0)
                                    <span class="badge rounded-pill px-3 py-1.5 fs-xs fw-semibold text-white" style="background: #059669;">
                                        <i class="bi bi-check2-circle me-1"></i> Ada Pilihan
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xs">
                                        5 Butir Soal
                                    </span>
                                @endif
                            </div>

                            <span class="badge rounded-pill px-2.5 py-0.5 fs-xs fw-semibold mb-1" style="background: {{ $cfg['bg_subtle'] }}; color: {{ $cfg['color'] }};">
                                {{ $cfg['badge'] }}
                            </span>
                            <h4 class="fw-bold text-dark mb-2 tracking-tight">
                                Bidang {{ $fld->name }}
                            </h4>
                            <p class="text-muted fs-xs mb-4" style="line-height: 1.65;">
                                {{ $cfg['desc'] }}
                            </p>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="fs-xs fw-bold" style="color: {{ $cfg['color'] }};">
                                Buka 5 Pernyataan & Catatan &rarr;
                            </span>
                            <div class="field-arrow-box rounded-circle d-flex align-items-center justify-content-center"
                                 style="background: {{ $cfg['bg_subtle'] }}; color: {{ $cfg['color'] }}; width: 30px; height: 30px;">
                                <i class="bi bi-arrow-right fs-xs"></i>
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
    .modern-field-card {
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .field-left-accent {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 4px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .modern-field-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: #cbd5e1 !important;
    }

    .modern-field-card:hover .field-left-accent {
        opacity: 1;
    }

    .field-icon-box {
        width: 50px;
        height: 50px;
    }
</style>
@endpush
