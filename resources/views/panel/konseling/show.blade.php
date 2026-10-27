@extends('layouts.panel')

@section('title', 'Detail Permohonan Konseling - ' . $konseling->ticket_number)

@section('content')
<div class="space-y-4">

    <!-- Top Action Bar -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3.5 p-md-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <a href="{{ route('panel.konseling.index') }}" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-xs flex-shrink-0 mt-1" style="width: 40px; height: 40px;" title="Kembali ke Agenda Konseling">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                            <span class="badge bg-teal-subtle text-mint font-monospace px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                <i class="bi bi-ticket-perforated me-1"></i>{{ $konseling->ticket_number }}
                            </span>
                            <x-badge-status :status="$konseling->status" />
                            
                            @if($konseling->source === 'ims')
                                <span class="badge bg-purple-subtle text-purple border-purple px-2.5 py-1 rounded-pill fs-xs fw-semibold">
                                    <i class="bi bi-clipboard2-check me-1"></i>Tindak Lanjut IMS
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border-primary px-2.5 py-1 rounded-pill fs-xs fw-semibold">
                                    <i class="bi bi-person-heart me-1"></i>Konseling Pribadi
                                </span>
                            @endif

                            @if($konseling->counseling_method === 'online_meet' || ($konseling->schedule && $konseling->schedule->counseling_method === 'online_meet'))
                                <span class="badge bg-info-subtle text-info-emphasis border-info px-2.5 py-1 rounded-pill fs-xs fw-semibold">
                                    <i class="bi bi-camera-video me-1"></i>Daring (Google Meet)
                                </span>
                            @else
                                <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-xs fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i>Tatap Muka
                                </span>
                            @endif
                        </div>
                        <h1 class="h5 fw-extrabold text-dark mb-0">{{ $konseling->purpose }}</h1>
                    </div>
                </div>

                <!-- Action Buttons Group -->
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    @if(in_array($konseling->status, ['scheduled', 'approved', 'completed']))
                        <a href="{{ route('panel.konseling.printCard', $konseling) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs">
                            <i class="bi bi-printer me-1"></i> Cetak Kartu
                        </a>
                    @endif

                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-semibold shadow-xs" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-pencil-square me-1"></i> Ubah Status
                    </button>

                    <!-- Setujui Rencana Jadwal Button (Hero CTA) -->
                    <button type="button" class="btn btn-mint btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                        <i class="bi bi-calendar-check me-1.5"></i> {{ $konseling->schedule ? 'Atur Ulang Jadwal' : 'Setujui Rencana Jadwal' }}
                    </button>

                    @if(in_array($konseling->status, ['scheduled', 'awaiting_student_confirmation', 'approved']))
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#completeModal">
                            <i class="bi bi-check2-circle me-1.5"></i> Tandai Sudah Tertangani
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success / Info -->
    <x-alert />

    <div class="row g-4">
        <!-- LEFT COLUMN: Rincian Masalah & Permohonan -->
        <div class="col-lg-8 space-y-4">

            <!-- Card: Rincian Permohonan Konseling -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-1.5 rounded-3 bg-teal-subtle text-mint d-inline-flex">
                            <i class="bi bi-card-text fs-5"></i>
                        </div>
                        <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Rincian Permohonan Konseling</span>
                    </div>

                    @if($konseling->source === 'ims' && $konseling->ims_assessment_id)
                        <a href="{{ route('panel.ims.show', $konseling->ims_assessment_id) }}" class="btn btn-sm btn-outline-purple rounded-pill px-3 py-1 fs-xs fw-semibold">
                            <i class="bi bi-file-earmark-text me-1"></i> Buka Lembar IMS Murid
                        </a>
                    @endif
                </div>

                <div class="card-body p-4 space-y-4">
                    <!-- Topik & Judul Masalah -->
                    <div class="p-3 rounded-3 border bg-light-subtle" style="border-left: 4px solid var(--primary) !important;">
                        <span class="text-muted fs-xs fw-bold text-uppercase tracking-wide d-block mb-1">Topik / Masalah yang Diajukan</span>
                        <div class="fs-6 fw-bold text-dark">{{ $konseling->purpose }}</div>
                    </div>

                    <!-- Meta Information Grid -->
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 rounded-3 border bg-light h-100">
                                <span class="text-muted fs-xs d-block mb-1">Bidang Bimbingan</span>
                                <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                    {{ $konseling->guidanceField?->name ?? 'Bidang Pribadi' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 rounded-3 border bg-light h-100">
                                <span class="text-muted fs-xs d-block mb-1">Rekomendasi Layanan</span>
                                <span class="badge bg-teal-subtle text-mint px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                    {{ $konseling->serviceType?->name ?? 'Bimbingan Pribadi' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 rounded-3 border bg-light h-100">
                                <span class="text-muted fs-xs d-block mb-1">Waktu Usulan Siswa</span>
                                <span class="fw-bold text-dark fs-xs d-block">{{ $konseling->preferred_schedule ?? 'Fleksibel' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-2.5 rounded-3 border bg-light h-100">
                                <span class="text-muted fs-xs d-block mb-1">Tanggal Diajukan</span>
                                <span class="fw-bold text-dark fs-xs d-block">{{ $konseling->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Keluhan / Kebutuhan Murid -->
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark fs-sm d-flex align-items-center gap-1.5">
                                <i class="bi bi-chat-quote-fill text-warning fs-5"></i>
                                Deskripsi Kondisi / Curahan Hati Murid:
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary fs-xs">Pesan Murid</span>
                        </div>
                        <div class="p-3.5 rounded-3 border bg-white shadow-xs position-relative" style="background: #fafaf9; border-color: #e7e5e4 !important; font-size: 0.92rem; line-height: 1.6;">
                            <p class="mb-0 text-dark fst-italic">"{{ $konseling->description ?? '-' }}"</p>
                        </div>
                    </div>

                    <!-- Catatan Tambahan Siswa (Jika Ada) -->
                    @if($konseling->additional_notes)
                        <div>
                            <span class="fw-bold text-dark fs-xs d-block mb-1.5 text-muted">Catatan Tambahan dari Murid:</span>
                            <div class="p-3 rounded-3 border bg-light text-dark fs-sm">
                                {{ $konseling->additional_notes }}
                            </div>
                        </div>
                    @endif

                    <!-- Catatan Resmi Konselor / Madrasah (Jika Ada) -->
                    @if($konseling->status_notes)
                        <div class="p-3 rounded-3 border bg-info-subtle border-info-subtle text-info-emphasis">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="fw-bold fs-xs text-uppercase tracking-wide d-block">Catatan Respon Konselor / Madrasah Terakhir:</span>
                                    <p class="fs-sm mb-0 mt-1 text-dark">{{ $konseling->status_notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card: Status Realisasi & Penanganan Konseling -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Laporan & Hasil Penanganan Konseling</span>
                </div>
                <div class="card-body p-4">
                    @if($konseling->status === 'completed')
                        <div class="p-3.5 rounded-3 border bg-success-subtle border-success-subtle text-success-emphasis mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-patch-check-fill fs-4 text-success"></i>
                                <span class="fw-bold fs-sm">Sesi Konseling Telah Selesai (Sudah Tertangani)</span>
                            </div>
                            @if($konseling->completed_at)
                                <div class="fs-xs text-muted mb-2">
                                    Diselesaikan pada: <strong>{{ $konseling->completed_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                                </div>
                            @endif
                            @if($konseling->resolution_notes)
                                <div class="bg-white p-3 rounded-3 border text-dark fs-sm mt-2">
                                    <span class="fw-bold d-block text-muted fs-xs mb-1">Catatan Akhir Penanganan:</span>
                                    {{ $konseling->resolution_notes }}
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Petunjuk Tahapan Penanganan -->
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="p-3 rounded-3 border {{ in_array($konseling->status, ['pending', 'scheduled', 'completed']) ? 'bg-teal-subtle border-teal' : 'bg-light' }}">
                                    <i class="bi bi-check-circle-fill text-mint fs-4 d-block mb-1"></i>
                                    <span class="fw-bold fs-xs d-block text-dark">1. Pengajuan</span>
                                    <span class="text-muted fs-xxs">Murid Mengisi</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3 border {{ in_array($konseling->status, ['scheduled', 'completed']) ? 'bg-teal-subtle border-teal' : 'bg-light' }}">
                                    <i class="bi bi-calendar-check-fill {{ in_array($konseling->status, ['scheduled', 'completed']) ? 'text-mint' : 'text-muted' }} fs-4 d-block mb-1"></i>
                                    <span class="fw-bold fs-xs d-block text-dark">2. Penjadwalan</span>
                                    <span class="text-muted fs-xxs">Guru BK Menentukan</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded-3 border {{ $konseling->status === 'completed' ? 'bg-success-subtle border-success' : 'bg-light' }}">
                                    <i class="bi bi-patch-check-fill {{ $konseling->status === 'completed' ? 'text-success' : 'text-muted' }} fs-4 d-block mb-1"></i>
                                    <span class="fw-bold fs-xs d-block text-dark">3. Selesai</span>
                                    <span class="text-muted fs-xxs">Sudah Tertangani</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Sidebar (Profil Murid, Jadwal Sesi, Timeline) -->
        <div class="col-lg-4 space-y-4">

            <!-- Card 1: Identitas Murid Pemohon -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fw-extrabold text-dark fs-sm">Profil Murid Pemohon</span>
                    <span class="badge bg-light text-muted border">ID #{{ $konseling->student->id }}</span>
                </div>
                <div class="card-body p-3.5 text-center">
                    <div class="position-relative d-inline-block mb-2">
                        <img src="{{ $konseling->student->photo_url }}" 
                             alt="{{ $konseling->student->name }}" 
                             class="rounded-circle border shadow-xs" 
                             style="width: 64px; height: 64px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1rem;">{{ $konseling->student->name }}</h5>
                    <div class="text-muted fs-xs mb-3">
                        NISN: <span class="font-monospace fw-semibold text-dark">{{ $konseling->student->nisn }}</span>
                    </div>

                    <div class="p-2.5 rounded-3 bg-light border text-start fs-xs mb-3 space-y-1.5">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Rombel / Kelas:</span>
                            <span class="fw-bold text-dark">{{ $konseling->student->classrooms->first()?->name ?? 'Belum ada kelas' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Jenis Kelamin:</span>
                            <span class="fw-semibold text-dark">{{ $konseling->student->gender_label }}</span>
                        </div>
                        @if($konseling->student->guardian_phone)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kontak WhatsApp:</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $konseling->student->guardian_phone) }}" target="_blank" class="fw-semibold text-mint text-decoration-none">
                                    {{ $konseling->student->guardian_phone }} <i class="bi bi-box-arrow-up-right fs-xxs"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('panel.murid.show', $konseling->student_id) }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold" style="font-size: 0.8rem;">
                            <i class="bi bi-person-bounding-box me-1"></i> Buka Profil Lengkap Murid
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Informasi Jadwal Sesi Konseling -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fw-extrabold text-dark fs-sm">Jadwal Sesi Konseling</span>
                    @if($konseling->schedule)
                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-xs fw-bold">Terjadwal</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-pill fs-xs fw-bold">Belum Dijadwalkan</span>
                    @endif
                </div>

                <div class="card-body p-3.5">
                    @if($konseling->schedule)
                        <div class="space-y-3 fs-sm">
                            <!-- Hari & Tanggal -->
                            <div class="d-flex align-items-start gap-2.5">
                                <div class="p-1.5 rounded-2 bg-teal-subtle text-mint flex-shrink-0 mt-0.5">
                                    <i class="bi bi-calendar3 fs-5"></i>
                                </div>
                                <div>
                                    <span class="text-muted fs-xs d-block">Hari & Tanggal</span>
                                    <span class="fw-bold text-dark">{{ $konseling->schedule->scheduled_date->translatedFormat('l, d F Y') }}</span>
                                </div>
                            </div>

                            <!-- Waktu Pelaksanaan -->
                            <div class="d-flex align-items-start gap-2.5">
                                <div class="p-1.5 rounded-2 bg-teal-subtle text-mint flex-shrink-0 mt-0.5">
                                    <i class="bi bi-clock fs-5"></i>
                                </div>
                                <div>
                                    <span class="text-muted fs-xs d-block">Waktu Pelaksanaan</span>
                                    <span class="fw-bold text-dark">{{ $konseling->schedule->start_time }} - {{ $konseling->schedule->end_time }} WIB</span>
                                </div>
                            </div>

                            <!-- Lokasi atau Link Google Meet -->
                            @if($konseling->schedule->counseling_method === 'online_meet')
                                <div class="p-3 rounded-3 border bg-info-subtle border-info-subtle">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-camera-video-fill text-info fs-5"></i>
                                        <span class="fw-bold fs-xs text-info-emphasis">Konseling Online (Google Meet)</span>
                                    </div>
                                    @if($konseling->schedule->meeting_link)
                                        <div class="d-grid gap-1.5">
                                            <a href="{{ $konseling->schedule->meeting_link }}" target="_blank" class="btn btn-primary btn-sm rounded-pill fw-bold" style="font-size: 0.8rem;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Google Meet Sekarang
                                            </a>
                                            <div class="text-truncate small text-muted font-monospace mt-1" style="font-size: 0.72rem;">
                                                {{ $konseling->schedule->meeting_link }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fs-xs">Link belum dicantumkan.</span>
                                    @endif
                                </div>
                            @else
                                <div class="d-flex align-items-start gap-2.5">
                                    <div class="p-1.5 rounded-2 bg-teal-subtle text-mint flex-shrink-0 mt-0.5">
                                        <i class="bi bi-geo-alt fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted fs-xs d-block">Ruangan / Tempat</span>
                                        <span class="fw-bold text-dark">{{ $konseling->schedule->location ?? 'Ruang Bimbingan Konseling' }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Guru BK / Konselor PIC -->
                            <div class="d-flex align-items-start gap-2.5">
                                <div class="p-1.5 rounded-2 bg-teal-subtle text-mint flex-shrink-0 mt-0.5">
                                    <i class="bi bi-person-badge fs-5"></i>
                                </div>
                                <div>
                                    <span class="text-muted fs-xs d-block">Guru BK Pendamping</span>
                                    <span class="fw-bold text-dark">{{ $konseling->schedule->counselor?->name ?? 'Belum Ditugaskan' }}</span>
                                </div>
                            </div>

                            <!-- Konfirmasi Kehadiran Siswa -->
                            <div class="pt-2 border-top">
                                <span class="text-muted fs-xs d-block mb-1">Status Kehadiran Murid:</span>
                                @if($konseling->schedule->student_confirmation === 'confirmed')
                                    <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-check-circle-fill me-1"></i> Telah Dikonfirmasi Murid
                                    </span>
                                @elseif($konseling->schedule->student_confirmation === 'reschedule_requested')
                                    <span class="badge bg-warning-subtle text-warning px-2.5 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-arrow-repeat me-1"></i> Murid Meminta Reschedule
                                    </span>
                                    @if($konseling->schedule->reschedule_reason)
                                        <div class="fs-xs text-danger mt-1">Alasan: {{ $konseling->schedule->reschedule_reason }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xs">
                                        Menunggu Konfirmasi Murid
                                    </span>
                                @endif
                            </div>

                            <div class="pt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-semibold" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                    <i class="bi bi-calendar-event me-1"></i> Atur Ulang Rencana Jadwal
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="p-3 rounded-circle bg-light d-inline-flex mb-2">
                                <i class="bi bi-calendar-plus fs-2 text-muted"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Jadwal Belum Ditetapkan</h6>
                            <p class="text-muted fs-xs mb-3">Klik tombol di bawah untuk menyetujui permohonan dan mengatur jadwal konseling bagi murid ini.</p>
                            <button type="button" class="btn btn-mint btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                <i class="bi bi-calendar-check me-1.5"></i> Setujui Rencana Jadwal
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Riwayat Perjalanan Status (Audit Trail Timeline) -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-3.5 border-bottom">
                    <span class="fw-extrabold text-dark fs-sm">Riwayat Aktivitas Tiket</span>
                </div>
                <div class="card-body p-3.5">
                    <div class="timeline space-y-2.5">
                        @forelse($konseling->histories as $hist)
                            <div class="p-2.5 bg-light rounded-3 border fs-xs space-y-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">{{ $hist->actor?->name ?? 'Sistem Ruang Ceritaku' }}</span>
                                    <span class="text-muted">{{ $hist->created_at->translatedFormat('d M, H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5 rounded">{{ $hist->status_from }}</span>
                                    <i class="bi bi-arrow-right text-muted fs-xxs"></i>
                                    <span class="badge bg-primary-subtle text-primary px-2 py-0.5 rounded">{{ $hist->status_to }}</span>
                                </div>
                                @if($hist->notes)
                                    <p class="text-dark mb-0 pt-1 border-top mt-1" style="font-size: 0.76rem;">{{ $hist->notes }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted fs-xs">
                                Belum ada riwayat aktivitas tiket.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Ubah Status / Respon -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <form method="POST" action="{{ route('panel.konseling.status', $konseling) }}">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fs-sm fw-bold">Ubah Status Permohonan Konseling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 space-y-3">
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Pilih Status Baru</label>
                        <select name="status" class="form-select rounded-pill px-3" required>
                            <option value="approved" {{ $konseling->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="needs_information" {{ $konseling->status === 'needs_information' ? 'selected' : '' }}>Menunggu Informasi Tambahan</option>
                            <option value="rejected" {{ $konseling->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="cancelled" {{ $konseling->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Tugaskan Konselor PIC (Opsional)</label>
                        <select name="assigned_counselor_id" class="form-select rounded-pill px-3">
                            <option value="">-- Tetapkan / Ubah Konselor --</option>
                            @foreach($counselors as $c)
                                <option value="{{ $c->id }}" {{ $konseling->assigned_counselor_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Catatan Respon Resmi (Wajib)</label>
                        <textarea name="notes" class="form-control rounded-3" rows="3" placeholder="Tuliskan alasan penolakan, info yang dibutuhkan, atau catatan penerimaan permohonan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mint rounded-pill px-3.5 fw-bold shadow-xs">Simpan Respon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Penjadwalan Konseling (Setujui Rencana) -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <form method="POST" action="{{ route('panel.konseling.schedule', $konseling) }}">
                @csrf
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fs-sm fw-bold">Setujui Rencana Jadwal Konseling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 space-y-3">
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Guru BK / Konselor Pendamping</label>
                        <select name="counselor_id" class="form-select rounded-pill px-3" required>
                            @foreach($counselors as $c)
                                <option value="{{ $c->id }}" {{ ($konseling->assigned_counselor_id ?? $konseling->schedule?->counselor_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->user->name ?? $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Metode Konseling -->
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Metode Konseling</label>
                        @php
                            $currentMethod = $konseling->schedule?->counseling_method ?? $konseling->counseling_method ?? 'tatap_muka';
                        @endphp
                        <select name="counseling_method" id="scheduleMethodSelect" class="form-select rounded-pill px-3" onchange="toggleScheduleLocation(this.value)" required>
                            <option value="tatap_muka" {{ $currentMethod === 'tatap_muka' ? 'selected' : '' }}>Tatap Muka di Sekolah</option>
                            <option value="online_meet" {{ $currentMethod === 'online_meet' ? 'selected' : '' }}>Daring via Google Meet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Tanggal Konseling</label>
                        <input type="date" name="scheduled_date" value="{{ $konseling->schedule?->scheduled_date?->format('Y-m-d') ?? date('Y-m-d') }}" class="form-control rounded-pill px-3" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-bold">Jam Mulai</label>
                            <input type="time" name="start_time" value="{{ $konseling->schedule?->start_time ?? '09:00' }}" class="form-control rounded-pill px-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-bold">Jam Selesai</label>
                            <input type="time" name="end_time" value="{{ $konseling->schedule?->end_time ?? '10:00' }}" class="form-control rounded-pill px-3" required>
                        </div>
                    </div>

                    <!-- Tempat / Ruang Konseling (Jika Tatap Muka) -->
                    <div class="mb-3" id="locationGroup" style="{{ $currentMethod === 'online_meet' ? 'display: none;' : '' }}">
                        <label class="form-label fs-xs text-muted fw-bold">Tempat / Ruang Konseling</label>
                        <input type="text" name="location" id="locationInput" value="{{ $konseling->schedule?->location ?? 'Ruang BK Lantai 1' }}" class="form-control rounded-pill px-3" placeholder="Contoh: Ruang BK 1, Ruang Konseling Khusus...">
                    </div>

                    <!-- Tautan Google Meet (Jika Online) -->
                    <div class="mb-3" id="meetingLinkGroup" style="{{ $currentMethod === 'online_meet' ? '' : 'display: none;' }}">
                        <label class="form-label fs-xs text-muted fw-bold">
                            <i class="bi bi-camera-video text-primary me-1"></i> Tautan / Link Google Meet
                        </label>
                        <input type="url" name="meeting_link" id="meetingLinkInput" value="{{ $konseling->schedule?->meeting_link ?? 'https://meet.google.com/' }}" class="form-control rounded-pill px-3" placeholder="https://meet.google.com/xxx-yyyy-zzz">
                        <div class="form-text fs-xxs text-muted mt-1">Masukkan link rapat Google Meet untuk siswa.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Instruksi Khusus untuk Murid (Opsional)</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Contoh: Harap hadir tepat waktu atau siapkan catatan...">{{ $konseling->schedule?->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mint rounded-pill px-3.5 fw-bold shadow-xs">
                        <i class="bi bi-check-circle me-1"></i> Setujui Rencana Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Selesaikan Sesi Konseling (Sudah Tertangani) -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <form method="POST" action="{{ route('panel.konseling.complete', $konseling) }}">
                @csrf
                <div class="modal-header bg-light py-3 px-4">
                    <h5 class="modal-title fs-sm fw-bold">Konfirmasi Sesi Selesai (Sudah Tertangani)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 space-y-3">
                    <div class="alert alert-info py-2.5 px-3 fs-xs mb-3 rounded-3">
                        <i class="bi bi-info-circle me-1"></i> Menyimpan form ini akan mengubah status permohonan menjadi <strong>Sudah Tertangani (Selesai)</strong> dan mencatat realisasi layanan BK ke laporan.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Durasi Pelaksanaan (Menit)</label>
                        <input type="number" name="duration_minutes" value="45" class="form-control rounded-pill px-3" min="5" max="480" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Deskripsi Aktivitas Konseling</label>
                        <textarea name="activity_description" class="form-control rounded-3" rows="3" placeholder="Uraikan jalannya proses konseling dan pokok bahasan..." required>{{ $konseling->purpose }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Hasil & Evaluasi Konseling</label>
                        <textarea name="evaluation_result" class="form-control rounded-3" rows="2" placeholder="Pemahaman baru, keterbukaan konseli, komitmen perbaikan..." required>Sesi konseling telah selesai dilaksanakan dengan baik.</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Catatan Akhir Penanganan / Rekomendasi</label>
                        <textarea name="resolution_notes" class="form-control rounded-3" rows="2" placeholder="Catatan akhir penanganan untuk arsip madrasah..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-bold">Rencana Tindak Lanjut (Opsional)</label>
                        <textarea name="follow_up_plan" class="form-control rounded-3" rows="2" placeholder="Sesi lanjutan, observasi berkala, atau kolaborasi wali kelas..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-3.5 fw-bold shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i> Tandai Sudah Tertangani
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleScheduleLocation(method) {
        const locGroup = document.getElementById('locationGroup');
        const meetGroup = document.getElementById('meetingLinkGroup');
        if (method === 'online_meet') {
            locGroup.style.display = 'none';
            meetGroup.style.display = 'block';
        } else {
            locGroup.style.display = 'block';
            meetGroup.style.display = 'none';
        }
    }
</script>
@endsection
