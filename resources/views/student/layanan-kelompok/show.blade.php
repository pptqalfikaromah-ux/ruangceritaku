@extends('layouts.student')

@section('title', 'Detail Sesi: ' . $group->ticket_number)
@section('breadcrumb')
    <a href="{{ route('murid.layanan-kelompok.index') }}">Layanan Kelompok</a>
    <i class="bi bi-chevron-right fs-xxs text-muted"></i>
    <span class="current">{{ $group->ticket_number }}</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1.5">
                        <span class="badge {{ $group->service_type_badge_class }} rounded-pill px-3 py-1 fs-xxs fw-bold">
                            {{ $group->service_type_label }}
                        </span>
                        <span class="badge {{ $group->status_badge_class }} rounded-pill px-3 py-1 fs-xxs fw-bold">
                            {{ $group->status_label }}
                        </span>
                    </div>
                    <h1 class="h4 fw-extrabold text-dark mb-0.5">Tiket #{{ $group->ticket_number }}</h1>
                    <p class="text-muted fs-xs mb-0">Diajukan pada {{ $group->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('murid.layanan-kelompok.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fs-xs fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    @if($group->scheduled_date)
                        <a href="{{ route('murid.layanan-kelompok.printCard', $group) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 fs-xs fw-bold">
                            <i class="bi bi-printer me-1"></i> Cetak Kartu Sesi
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Rincian Topik & Susunan Kelompok -->
        <div class="col-12 col-lg-7 space-y-3">
            <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4">
                <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                    <i class="bi bi-card-text me-1 text-mint"></i> Rincian Pengajuan Kelompok
                </h6>

                <div class="mb-3">
                    <label class="text-muted fs-xxs text-uppercase fw-bold d-block mb-1">Topik Bahasan:</label>
                    <div class="fw-bold text-dark fs-5">{{ $group->topic }}</div>
                </div>

                <div class="mb-3 p-3 rounded-3 bg-light border">
                    <label class="text-muted fs-xxs text-uppercase fw-bold d-block mb-1">Latar Belakang / Cerita:</label>
                    <p class="text-dark fs-xs mb-0" style="line-height: 1.65;">{{ $group->description }}</p>
                </div>

                @if($group->group_goal)
                    <div class="mb-3 p-3 rounded-3 bg-teal-subtle border-teal">
                        <label class="text-mint fs-xxs text-uppercase fw-bold d-block mb-1">Tujuan / Harapan Bersama:</label>
                        <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->group_goal }}</p>
                    </div>
                @endif
            </div>

            <!-- Susunan Peserta -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4">
                <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                    <i class="bi bi-people-fill me-1 text-mint"></i> Peserta Sesi Kelompok
                </h6>

                <!-- Ketua -->
                <div class="p-2.5 rounded-3 bg-light border mb-2 d-flex align-items-center gap-2.5">
                    <div class="rounded-circle bg-mint text-white d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-star-fill text-warning fs-6"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-1.5">
                            <strong class="text-dark fs-xs">{{ $group->leader?->name }}</strong>
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-0.2 fs-xxs fw-bold">Ketua</span>
                        </div>
                        <div class="text-muted fs-xxs">
                            NISN: {{ $group->leader?->nisn ?? '-' }} &bull; Kelas: {{ $group->leader?->classrooms->first()?->name ?? 'Siswa' }}
                        </div>
                    </div>
                </div>

                <!-- Anggota -->
                <div class="space-y-1.5">
                    @foreach($group->members as $idx => $m)
                        <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between fs-xs">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border rounded-circle" style="width: 22px; height: 22px; line-height: 18px; text-align: center;">{{ $idx + 1 }}</span>
                                <div>
                                    <span class="fw-semibold text-dark">{{ $m->name }}</span>
                                    <div class="text-muted fs-xxs">Kelas: {{ $m->classrooms->first()?->name ?? '-' }}</div>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-xxs">Anggota</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Jadwal & Lokasi -->
        <div class="col-12 col-lg-5 space-y-3">
            <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4">
                <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                    <i class="bi bi-calendar-check me-1 text-mint"></i> Jadwal & Tempat Pelaksanaan
                </h6>

                @if($group->scheduled_date)
                    <div class="p-3.5 rounded-4 bg-teal-subtle border-teal space-y-2.5 mb-3">
                        <div>
                            <span class="text-mint fs-xxs text-uppercase fw-bold d-block">Guru BK Pembimbing:</span>
                            <div class="fw-bold text-dark fs-6">{{ $group->counselor?->name ?? 'Tim Guru BK' }}</div>
                        </div>

                        <div>
                            <span class="text-mint fs-xxs text-uppercase fw-bold d-block">Hari & Tanggal:</span>
                            <strong class="text-dark fs-xs">
                                <i class="bi bi-calendar-event me-1"></i>{{ $group->scheduled_date->translatedFormat('l, d F Y') }}
                            </strong>
                        </div>

                        <div>
                            <span class="text-mint fs-xxs text-uppercase fw-bold d-block">Waktu:</span>
                            <strong class="text-dark fs-xs">
                                <i class="bi bi-clock me-1"></i>Pukul {{ $group->start_time }} - {{ $group->end_time }} WIB
                            </strong>
                        </div>

                        <div>
                            <span class="text-mint fs-xxs text-uppercase fw-bold d-block">Lokasi Pertemuan:</span>
                            <strong class="text-dark fs-xs">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $group->location }}
                            </strong>
                        </div>

                        @if($group->meeting_link)
                            <div class="pt-2 border-top">
                                <span class="text-mint fs-xxs text-uppercase fw-bold d-block mb-1">Tautan Sesi Daring (Google Meet):</span>
                                <a href="{{ $group->meeting_link }}" target="_blank" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fs-xs fw-bold shadow-xs text-truncate d-inline-flex align-items-center">
                                    <i class="bi bi-camera-video-fill me-1.5"></i> Masuk Google Meet
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($group->counselor_notes)
                        <div class="p-3 rounded-3 bg-light border">
                            <span class="text-muted fs-xxs text-uppercase fw-bold d-block mb-1">Pesan dari Guru BK:</span>
                            <p class="text-dark fs-xs mb-0 fst-italic" style="line-height: 1.55;">"{{ $group->counselor_notes }}"</p>
                        </div>
                    @endif
                @else
                    <div class="p-4 rounded-3 bg-light text-center border">
                        <i class="bi bi-hourglass-split text-warning fs-1 d-block mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1 fs-xs">Menunggu Konfirmasi Jadwal</h6>
                        <p class="text-muted fs-xxs mb-0">
                            Pengajuan kelompokmu sedang ditinjau oleh Guru BK. Jadwal resmi pelaksanaan sesi akan muncul di sini segera.
                        </p>
                    </div>
                @endif
            </div>

            @if($group->status === 'selesai' && $group->evaluation_result)
                <div class="card card-app border-0 shadow-sm rounded-4 p-3.5 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-2 pb-2 border-bottom fs-sm">
                        <i class="bi bi-check2-circle me-1 text-success"></i> Catatan Hasil Kelompok
                    </h6>
                    <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->evaluation_result }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

