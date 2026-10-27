@extends('layouts.panel')

@section('title', $title)

@section('content')
<x-page-header :title="$title" subtitle="Modul Sistem Ruang Ceritaku (Bimbingan & Konseling Sekolah)">
</x-page-header>

<div class="row justify-content-center my-4">
    <div class="col-lg-8">
        <div class="card card-app text-center p-5">
            <div class="mb-3">
                <span class="badge bg-teal-subtle text-primary-teal px-3 py-1.5 rounded-pill fs-6 fw-bold">
                    {{ $badge }} Segera Hadir
                </span>
            </div>

            <div class="my-3">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary-teal shadow-sm" style="width: 90px; height: 90px; font-size: 2.75rem;">
                    <i class="bi {{ $icon }}"></i>
                </div>
            </div>

            <h4 class="fw-bold text-slate-800 mb-2">{{ $title }}</h4>
            <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
                {{ $description }}
            </p>

            <div class="p-3 rounded-3 bg-light border text-start mx-auto mb-4" style="max-width: 500px;">
                <div class="fw-bold small text-slate-800 mb-1">
                    <i class="bi bi-info-circle text-primary-teal me-1"></i> Rencana Pengembangan:
                </div>
                <div class="smaller text-muted">
                    Modul ini merupakan bagian dari roadmap pengembangan lanjutan aplikasi Ruang Ceritaku. Pada tahap 1 ini, fondasi data master, rombongan belajar, dan penugasan Guru BK telah aktif sepenuhnya.
                </div>
            </div>

            <div>
                <a href="{{ route('panel.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
