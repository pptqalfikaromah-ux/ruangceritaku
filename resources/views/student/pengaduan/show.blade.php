@extends('layouts.student')

@section('title', 'Detail Pelayanan Darurat - ' . $pengaduan->ticket_number)
@section('page_title', 'Detail Pelayanan Darurat')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item"><a href="{{ route('murid.pengaduan.index') }}" class="text-decoration-none" style="color: var(--emis-teal);">Pelayanan Darurat</a></li>
    <li class="breadcrumb-item active">{{ $pengaduan->ticket_number }}</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('murid.pengaduan.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-xs fw-semibold font-monospace rounded-pill">{{ $pengaduan->ticket_number }}</span>
                    <span class="badge {{ $pengaduan->status_badge_class }} px-2 py-1 fs-xs fw-medium rounded-pill">{{ $pengaduan->status_label }}</span>
                    <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs rounded-pill"><i class="bi bi-person-check me-1"></i> Identitas Terverifikasi</span>
                </div>
                <h1 class="h5 fw-bold text-dark mb-0">{{ $pengaduan->category }}</h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Complaint Content -->
        <div class="col-lg-7 space-y-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm">Rincian Laporan yang Kamu Kirimkan</span>
                </div>
                <div class="card-body p-4 space-y-3 fs-sm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted fs-xs d-block mb-1">Tingkat Urgensi</span>
                            <span class="badge {{ $pengaduan->urgency_badge_class }} text-uppercase fs-xs">
                                {{ $pengaduan->urgency_level }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted fs-xs d-block mb-1">Waktu Kejadian</span>
                            <span class="fw-medium text-dark">{{ $pengaduan->incident_date ? $pengaduan->incident_date->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-muted fs-xs d-block mb-1">Lokasi Kejadian</span>
                        <div class="fw-medium text-dark">{{ $pengaduan->incident_location ?? 'Lingkungan Madrasah' }}</div>
                    </div>

                    <div>
                        <span class="text-muted fs-xs d-block mb-1">Uraian / Cerita Peristiwa</span>
                        <div class="p-3 bg-light rounded-3 border text-dark" style="white-space: pre-line;">
                            {{ $pengaduan->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Follow-up & Feedback from Counselor -->
        <div class="col-lg-5 space-y-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm"><i class="bi bi-shield-check text-emerald me-1"></i> Tindak Lanjut dari Guru BK</span>
                </div>
                <div class="card-body p-4 fs-sm">
                    @if($pengaduan->handling_notes)
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted fs-xs d-block mb-1 fw-bold">Catatan / Respon Guru BK:</span>
                            <p class="text-dark mb-0" style="white-space: pre-line;">{{ $pengaduan->handling_notes }}</p>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-hourglass-split fs-1 d-block mb-2 text-warning"></i>
                            <span class="fs-xs">Laporanmu sudah diterima dan saat ini sedang ditinjau dengan penuh kehati-hatian oleh Guru BK.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
