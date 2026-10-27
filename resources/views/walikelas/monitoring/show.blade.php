@extends('layouts.walikelas')

@section('title', 'Detail Konseling Murid')
@section('page_title', 'Detail Aktivitas Konseling')
@section('page_subtitle', 'Tiket: ' . $konseling->ticket_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('walikelas.monitoring.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Monitoring
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-card-text me-1.5 text-primary"></i> Rincian Pengajuan Bimbingan Siswa
                </h6>
                <span class="badge rounded-pill bg-teal-subtle text-mint px-3 py-1.5 fw-bold">
                    Status: {{ ucfirst(str_replace('_', ' ', $konseling->status)) }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted fs-xs fw-bold text-uppercase">Kebutuhan / Tujuan Konseling</label>
                    <div class="p-3 rounded-3 bg-light border fw-semibold text-dark mt-1">
                        {{ $konseling->purpose }}
                    </div>
                </div>

                @if($konseling->preferred_time)
                    <div class="mb-3">
                        <label class="text-muted fs-xs fw-bold text-uppercase">Waktu Preferensi Siswa</label>
                        <div class="small text-dark mt-1">{{ $konseling->preferred_time }}</div>
                    </div>
                @endif

                @if($konseling->schedule)
                    <div class="p-3 rounded-3 border mb-3" style="background: #f0fdfa; border-color: #ccfbf1 !important;">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-calendar-check text-mint me-1"></i> Jadwal Konseling yang Ditetapkan</h6>
                        <div class="row g-2 small">
                            <div class="col-sm-6">
                                <span class="text-muted">Tanggal:</span>
                                <span class="fw-bold text-dark ms-1">{{ $konseling->schedule->counseling_date?->format('d F Y') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Jam:</span>
                                <span class="fw-bold text-dark ms-1">{{ $konseling->schedule->start_time }} - {{ $konseling->schedule->end_time }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Tempat:</span>
                                <span class="fw-bold text-dark ms-1">{{ $konseling->schedule->location ?? 'Ruang BK' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if($konseling->assignedCounselor)
                    <div class="mb-0">
                        <label class="text-muted fs-xs fw-bold text-uppercase">Guru BK Pendamping (PIC)</label>
                        <div class="d-flex align-items-center gap-2 mt-1 p-2.5 rounded-3 bg-light border">
                            <div class="p-2 rounded-circle bg-teal-subtle text-mint"><i class="bi bi-person-badge fs-5"></i></div>
                            <div>
                                <div class="fw-bold text-dark">{{ $konseling->assignedCounselor->name }}</div>
                                <div class="text-muted fs-xs">{{ $konseling->assignedCounselor->email ?? 'Konselor Sekolah' }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person me-1.5 text-warning"></i> Profil Siswa</h6>
            </div>
            <div class="card-body p-4 text-center">
                <div class="p-3 rounded-circle bg-light d-inline-flex mb-2">
                    <i class="bi bi-person-fill fs-1 text-muted"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $konseling->student?->name }}</h5>
                <div class="text-muted small mb-2">NISN: {{ $konseling->student?->nisn }}</div>
                <span class="badge bg-light text-muted border rounded-pill px-3 py-1">
                    Kelas {{ $classroom?->name ?? 'Binaan' }}
                </span>

                <div class="d-grid mt-4">
                    <a href="{{ route('walikelas.aduan.create', ['student_id' => $konseling->student_id]) }}" class="btn btn-outline-warning btn-sm rounded-pill py-2 fw-bold">
                        <i class="bi bi-flag-fill me-1"></i> Tambah Catatan / Kasus Terkait Anak Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
