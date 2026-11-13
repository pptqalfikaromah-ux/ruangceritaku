@extends('layouts.student')

@php
    $isBkp = ($selectedType ?? 'bimbingan_kelompok') === 'bimbingan_kelompok';
    $pageTitle = $isBkp ? 'Pengajuan Bimbingan Kelompok (BKP)' : 'Pengajuan Konseling Kelompok (KKP)';
    $themeColor = $isBkp ? '#0d9488' : '#7c3aed';
    $themeBtnClass = $isBkp ? 'btn-mint' : 'btn-purple';
    $themeTextClass = $isBkp ? 'text-mint' : 'text-purple';
    $serviceLabel = $isBkp ? 'Bimbingan Kelompok (BKP)' : 'Konseling Kelompok (KKP)';
@endphp

@section('title', $pageTitle)
@section('breadcrumb')
    <a href="{{ route('murid.layanan-kelompok.index', ['type' => $selectedType]) }}">{{ $serviceLabel }}</a>
    <i class="bi bi-chevron-right fs-xxs text-muted"></i>
    <span class="current">Ajukan Diri</span>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7 space-y-4">
        <!-- Header Card -->
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden text-center p-4 p-md-5 bg-white">
            <div class="rounded-circle {{ $isBkp ? 'bg-teal-subtle text-mint' : 'bg-purple-subtle text-purple' }} d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                <i class="bi {{ $isBkp ? 'bi-people-fill' : 'bi-heart-pulse-fill' }} fs-1"></i>
            </div>
            
            <h4 class="fw-extrabold text-dark mb-2">{{ $pageTitle }}</h4>
            <p class="text-muted fs-xs mb-4" style="line-height: 1.6;">
                Cukup tekan tombol di bawah untuk mendaftarkan dirimu. Guru BK yang akan menyusun kelompok, menentukan topik yang sesuai, serta menetapkan jadwal waktu dan tempatnya.
            </p>

            <form action="{{ route('murid.layanan-kelompok.store') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="service_type" value="{{ $selectedType }}">
                <button type="submit" class="btn {{ $themeBtnClass }} text-white btn-lg rounded-pill px-5 py-2.5 fs-sm fw-extrabold shadow-sm">
                    <i class="bi bi-send-check-fill me-1.5"></i> Kirim Pengajuan Sekarang
                </button>
            </form>

            <a href="{{ route('murid.layanan-kelompok.index', ['type' => $selectedType]) }}" class="text-muted fs-xs text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman {{ $serviceLabel }}
            </a>
        </div>
    </div>
</div>
@endsection
