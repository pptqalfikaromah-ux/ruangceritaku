@extends('layouts.student')

@section('title', 'Asesmen Belum Dibuka')

@section('content')
<div class="card border-0 shadow-sm rounded-3 p-5 bg-white text-center">
    <div class="py-5">
        <i class="bi bi-calendar-x fs-1 text-secondary mb-3 d-block"></i>
        <h4 class="fw-bold text-dark">Asesmen Belum Tersedia</h4>
        <p class="text-muted fs-sm max-w-md mx-auto mb-4">
            Periode semester aktif belum dibuka oleh pihak sekolah. Silakan hubungi Guru BK atau Admin sekolah untuk informasi lebih lanjut.
        </p>
        <a href="{{ route('murid.dashboard') }}" class="btn btn-emerald btn-sm rounded-pill px-4">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
