@extends('layouts.student')

@section('title', 'Layanan Kelompok (BKP & KKP)')
@section('breadcrumb')
    <span class="current">Layanan Kelompok</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3" style="background: linear-gradient(135deg, #0d9488, #0f766e); color: #fff;">
        <div class="card-body p-3.5 p-md-4 position-relative">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-2">
                        <span class="badge bg-white text-dark px-2.5 py-1 fs-xxs fw-bold rounded-pill">
                            👥 Sesi Bersama Teman
                        </span>
                        <span class="badge bg-white text-mint px-2.5 py-1 fs-xxs fw-bold rounded-pill">
                            BKP & KKP
                        </span>
                    </div>
                    <h1 class="h4 fw-extrabold text-white mb-1">Bimbingan & Konseling Kelompok</h1>
                    <p class="text-white-50 fs-xs mb-3" style="line-height: 1.6;">
                        Diskusikan topik belajar, pertemanan, atau cari solusi masalah bersama Guru BK dan teman-teman sekelompokmu secara interaktif dan nyaman.
                    </p>
                    <a href="{{ route('murid.layanan-kelompok.create') }}" class="btn btn-light text-mint rounded-pill px-4 py-2 fs-xs fw-extrabold shadow-sm">
                        <i class="bi bi-plus-circle-fill me-1"></i> Ajukan Sesi Kelompok Baru
                    </a>
                </div>
                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <i class="bi bi-people-fill text-white-50" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box Edukasi BKP vs KKP -->
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="p-3 rounded-4 bg-teal-subtle border-teal h-100">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-mint text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">BKP</span>
                    <strong class="text-dark fs-xs">Bimbingan Kelompok</strong>
                </div>
                <p class="text-muted fs-xxs mb-0" style="line-height: 1.55;">
                    Membahas topik umum bersama teman (misal: tips belajar efektif, persiapan ujian, pencegahan bullying, adaptasi).
                </p>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="p-3 rounded-4 bg-purple-subtle border h-100" style="border-color: #d8b4fe !important;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-purple text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">KKP</span>
                    <strong class="text-dark fs-xs">Konseling Kelompok</strong>
                </div>
                <p class="text-muted fs-xxs mb-0" style="line-height: 1.55;">
                    Menyelesaikan masalah pribadi yang serupa bersama Guru BK dengan asas kerahasiaan kelompok yang saling mendukung.
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Pengajuan Kelompok Siswa -->
    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
        <h6 class="fw-extrabold text-dark mb-0 fs-sm">Riwayat Sesi Kelompokmu</h6>
        <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xxs">
            Total {{ $groups->total() }} Sesi
        </span>
    </div>

    <div class="space-y-3">
        @forelse($groups as $item)
            @php
                $isLeader = $item->leader_student_id === $student->id;
            @endphp
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden p-3.5 mb-3 position-relative">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="fw-bold font-monospace text-mint fs-xs">{{ $item->ticket_number }}</span>
                            <span class="badge {{ $item->service_type_badge_class }} rounded-pill px-2 py-0.5 fs-xxs fw-bold">
                                {{ $item->service_type === 'konseling_kelompok' ? 'Konseling Kelompok (KKP)' : 'Bimbingan Kelompok (BKP)' }}
                            </span>
                            @if($isLeader)
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fs-xxs fw-bold">
                                    <i class="bi bi-star-fill me-0.5"></i>Kamu Ketua
                                </span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-xxs">
                                    Anggota Kelompok
                                </span>
                            @endif
                        </div>
                        <h5 class="fw-extrabold text-dark mb-1 fs-6">{{ $item->topic }}</h5>
                        <p class="text-muted fs-xs mb-0">{{ Str::limit($item->description, 130) }}</p>
                    </div>

                    <span class="badge {{ $item->status_badge_class }} rounded-pill px-3 py-1 fs-xxs fw-bold text-nowrap flex-shrink-0">
                        {{ $item->status_label }}
                    </span>
                </div>

                <!-- Info Jadwal & Peserta -->
                <div class="row g-2 align-items-center pt-1 mb-3 fs-xs">
                    <div class="col-12 col-md-5">
                        <span class="text-muted fs-xxs d-block">Jadwal Sesi:</span>
                        @if($item->scheduled_date)
                            <strong class="text-dark">
                                <i class="bi bi-calendar-check me-1 text-mint"></i>{{ $item->scheduled_date->translatedFormat('l, d M Y') }}
                            </strong>
                            <div class="text-muted fs-xxs">
                                <i class="bi bi-clock me-0.5"></i>Pukul {{ $item->start_time }} - {{ $item->end_time }} WIB
                                &bull; <i class="bi bi-geo-alt me-0.5 text-danger"></i>{{ $item->location }}
                            </div>
                        @else
                            <span class="text-muted fs-xxs">
                                <i class="bi bi-hourglass-split me-1 text-warning"></i>Menunggu konfirmasi jadwal dari Guru BK
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="text-muted fs-xxs d-block">Anggota Kelompok:</span>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 fs-xxs">
                                <i class="bi bi-star-fill text-warning me-0.5"></i>{{ $item->leader?->name }} (Ketua)
                            </span>
                            @foreach($item->members->take(3) as $m)
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-0.5 fs-xxs">
                                    {{ $m->name }}
                                </span>
                            @endforeach
                            @if($item->members->count() > 3)
                                <span class="badge bg-teal-subtle text-mint rounded-pill px-2 py-0.5 fs-xxs">
                                    +{{ $item->members->count() - 3 }} lainnya
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-md-end">
                        <span class="text-muted fs-xxs d-block">Konselor:</span>
                        <strong class="text-dark fs-xxs">{{ $item->counselor?->name ?? 'Tim Guru BK' }}</strong>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                    @if($item->scheduled_date)
                        <a href="{{ route('murid.layanan-kelompok.printCard', $item) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 fs-xs fw-semibold">
                            <i class="bi bi-printer me-1"></i> Cetak Kartu Sesi
                        </a>
                    @endif
                    <a href="{{ route('murid.layanan-kelompok.show', $item) }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fs-xs fw-bold shadow-xs">
                        Lihat Detail Sesi <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <div class="p-3 rounded-circle bg-teal-subtle text-mint d-inline-flex mx-auto mb-2" style="width: 64px; height: 64px; align-items: center; justify-content: center;">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Sesi Kelompok</h5>
                <p class="text-muted fs-xs mb-3">Kamu belum pernah mengajukan atau menjadi anggota sesi Bimbingan/Konseling Kelompok.</p>
                <div>
                    <a href="{{ route('murid.layanan-kelompok.create') }}" class="btn btn-mint text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs">
                        <i class="bi bi-plus-circle-fill me-1"></i> Ajukan Sesi Kelompok Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($groups->hasPages())
        <div class="d-flex justify-content-center pt-2">
            {{ $groups->links() }}
        </div>
    @endif
</div>
@endsection

