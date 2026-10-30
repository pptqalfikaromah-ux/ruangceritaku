@extends('layouts.student')

@section('title', 'Detail Permohonan Konseling - ' . $konseling->ticket_number)
@section('page_title', 'Detail Konseling')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item"><a href="{{ route('murid.konseling.index') }}" class="text-decoration-none" style="color: var(--emis-teal);">Konseling</a></li>
    <li class="breadcrumb-item active">{{ $konseling->ticket_number }}</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('murid.konseling.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold font-monospace rounded-pill">{{ $konseling->ticket_number }}</span>
                    <span class="badge {{ $konseling->status_badge_class }} px-2 py-1 fs-xs fw-medium rounded-pill">
                        {{ $konseling->student_status_label }}
                    </span>
                    <span class="badge {{ $konseling->method_badge_class }} px-2 py-1 fs-xs fw-medium rounded-pill">
                        <i class="bi {{ $konseling->isOnlineMeet() ? 'bi-camera-video' : 'bi-door-open' }} me-1"></i>{{ $konseling->method_label }}
                    </span>
                </div>
                <h1 class="h5 fw-bold text-dark mb-0">{{ $konseling->purpose }}</h1>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            @if(in_array($konseling->status, ['scheduled', 'approved', 'completed']))
                <a href="{{ route('murid.konseling.printCard', $konseling) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3.5 shadow-sm fw-bold">
                    <i class="bi bi-printer-fill me-1"></i> Unduh Kartu Konseling (PDF)
                </a>
            @endif

            @if($konseling->schedule && $konseling->schedule->student_confirmation !== 'confirmed' && !in_array($konseling->status, ['completed', 'cancelled']))
                <form method="POST" action="{{ route('murid.konseling.confirmSchedule', $konseling) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-emerald btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Saya Bersedia Hadir
                    </button>
                </form>
                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                    <i class="bi bi-arrow-repeat me-1"></i> Minta Jadwal Lain
                </button>
            @endif
        </div>
    </div>

    <!-- Kartu Tiket Ringkas Jadwal Konseling -->
    @if($konseling->schedule)
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden" style="border: 1.5px solid #0d9488 !important;">
            <div class="py-2.5 px-3.5 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-emerald text-white rounded-pill px-2.5 py-1 fs-xs fw-bold font-monospace">
                        <i class="bi bi-ticket-perforated-fill me-1"></i> TIKET #{{ $konseling->ticket_number }}
                    </span>
                    <span class="fw-bold text-emerald fs-xs">Jadwal Sesi Konseling Siswa</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($konseling->isOnlineMeet())
                        <span class="badge bg-info-subtle text-info px-2.5 py-1 rounded-pill fs-xs fw-bold">
                            <i class="bi bi-camera-video me-1"></i> Online Google Meet
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill fs-xs fw-bold">
                            <i class="bi bi-door-open me-1"></i> Tatap Muka di Sekolah
                        </span>
                    @endif

                    @if(in_array($konseling->status, ['scheduled', 'approved', 'completed']))
                        <a href="{{ route('murid.konseling.printCard', $konseling) }}" target="_blank" class="btn btn-sm btn-outline-emerald rounded-pill py-1 px-3 fs-xs fw-semibold">
                            <i class="bi bi-printer me-1"></i> Cetak Kartu Kecil
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-3.5">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted fs-xxs text-uppercase fw-semibold">Hari & Tanggal</div>
                        <div class="fw-bold text-dark fs-sm mt-0.5">{{ $konseling->schedule->scheduled_date->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted fs-xxs text-uppercase fw-semibold">Waktu Sesi</div>
                        <div class="fw-bold text-dark fs-sm mt-0.5">{{ $konseling->schedule->start_time }} - {{ $konseling->schedule->end_time }} WIB</div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted fs-xxs text-uppercase fw-semibold">Tempat / Ruangan</div>
                        <div class="fw-bold text-dark fs-sm mt-0.5">
                            @if($konseling->isOnlineMeet())
                                Google Meet (Online)
                            @else
                                {{ $konseling->schedule->location }}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted fs-xxs text-uppercase fw-semibold">Guru BK Pendamping</div>
                        <div class="fw-bold text-dark fs-sm mt-0.5">{{ $konseling->schedule->counselor?->user?->name ?? $konseling->schedule->counselor?->name ?? 'Guru BK' }}</div>
                    </div>
                </div>

                @if($konseling->isOnlineMeet() && $konseling->schedule->meeting_link)
                    <div class="mt-3 p-2.5 rounded-3 border d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2" style="background: #f0f7ff; border-color: #bfdbfe !important;">
                        <div>
                            <div class="fw-bold text-primary fs-xs mb-0.5">
                                <i class="bi bi-camera-video-fill me-1"></i> Tautan Sesi Google Meet Tersedia:
                            </div>
                            <div class="text-muted fs-xxs">Silakan masuk saat waktu sesi tiba.</div>
                        </div>
                        <a href="{{ $konseling->schedule->meeting_link }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fs-xs fw-bold text-nowrap shadow-xs">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Meet
                        </a>
                    </div>
                @endif

                @if($konseling->schedule->notes)
                    <div class="mt-2.5 p-2.5 bg-light rounded-3 border fs-xs">
                        <span class="fw-bold text-dark d-block mb-0.5"><i class="bi bi-chat-text text-emerald me-1"></i> Pesan Guru BK:</span>
                        <div class="text-dark">{{ $konseling->schedule->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Request Details -->
        <div class="col-lg-7 space-y-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm">Uraian Permohonan Saya</span>
                </div>
                <div class="card-body p-4 space-y-3 fs-sm">
                    <div>
                        <span class="text-muted fs-xs d-block mb-1">Topik Konseling</span>
                        <div class="fw-bold text-dark p-2 bg-light rounded border">{{ $konseling->purpose }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted fs-xs d-block mb-1">Bidang Bimbingan</span>
                            <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                                {{ $konseling->guidanceField?->name ?? 'Umum' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted fs-xs d-block mb-1">Layanan</span>
                            <span class="badge bg-emerald-subtle text-emerald px-2 py-1 rounded-pill">
                                {{ $konseling->serviceType?->name ?? 'Konseling Perorangan' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-muted fs-xs d-block mb-1">Cerita / Masalah yang Saya Tuliskan</span>
                        <div class="p-3 bg-light rounded-3 border text-dark" style="white-space: pre-line;">
                            {{ $konseling->description }}
                        </div>
                    </div>

                    @if($konseling->additional_notes)
                        <div>
                            <span class="text-muted fs-xs d-block mb-1">Catatan Tambahan Saya</span>
                            <div class="p-3 bg-light rounded-3 border text-dark italic">
                                "{{ $konseling->additional_notes }}"
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Official Response & History -->
        <div class="col-lg-5 space-y-4">
            @if($konseling->status_notes)
                <div class="card border-0 shadow-sm rounded-3 bg-info-subtle text-info-emphasis p-3">
                    <div class="d-flex gap-2">
                        <i class="bi bi-chat-quote-fill fs-5 mt-1"></i>
                        <div>
                            <span class="fw-bold fs-sm">Pesan Resmi dari Guru BK:</span>
                            <p class="fs-xs mb-0 mt-1 text-dark">{{ $konseling->status_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm">Riwayat Tindak Lanjut</span>
                </div>
                <div class="card-body p-3">
                    <div class="space-y-3 fs-xs">
                        @forelse($konseling->histories as $hist)
                            <div class="p-3 bg-light rounded-3 border space-y-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">{{ $hist->actor?->name ?? 'Sistem' }}</span>
                                    <span class="text-muted">{{ $hist->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-dark mb-0 pt-1 border-top mt-1">{{ $hist->notes }}</p>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                Belum ada riwayat tercatat.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Permintaan Reschedule Jadwal -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('murid.konseling.requestReschedule', $konseling) }}">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-sm fw-bold">Minta Penyesuaian Jadwal Konseling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body space-y-3">
                    <p class="fs-xs text-muted mb-2">
                        Jika kamu berhalangan hadir pada waktu yang telah ditetapkan, silakan sampaikan alasannya beserta usulan waktu yang lebih cocok untukmu.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Alasan Berhalangan / Usulan Waktu Baru <span class="text-danger">*</span></label>
                        <textarea name="reschedule_reason" class="form-control form-control-sm" rows="3" placeholder="Contoh: Pada hari tersebut ada ulangan harian jam ke-3, apakah bisa diganti ke hari Kamis jam istirahat?..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm">Kirim Permintaan Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
