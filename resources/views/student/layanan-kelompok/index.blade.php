@extends('layouts.student')

@php
    $isBkp = ($filterType ?? 'bimbingan_kelompok') === 'bimbingan_kelompok';
    $pageTitle = $isBkp ? 'Bimbingan Kelompok (BKP)' : 'Konseling Kelompok (KKP)';
    $themeGradient = $isBkp 
        ? 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)' 
        : 'linear-gradient(135deg, #7c3aed 0%, #581c87 100%)';
    $themeColor = $isBkp ? '#0d9488' : '#7c3aed';
    $themeBtnClass = $isBkp ? 'btn-mint' : 'btn-purple';
    $themeTextClass = $isBkp ? 'text-mint' : 'text-purple';
    $themeBorderClass = $isBkp ? 'border-mint' : 'border-purple';
    $themeBgSubtle = $isBkp ? 'bg-teal-subtle' : 'bg-purple-subtle';
    $serviceLabel = $isBkp ? 'Bimbingan Kelompok (BKP)' : 'Konseling Kelompok (KKP)';
    $serviceShort = $isBkp ? 'BKP' : 'KKP';
@endphp

@section('title', $pageTitle)
@section('breadcrumb')
    <span class="current">{{ $pageTitle }}</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner Khusus Sesuai Menu yang Dibuka (BKP atau KKP) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3" style="background: {{ $themeGradient }}; color: #fff;">
        <div class="card-body p-3.5 p-md-4 position-relative">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-2">
                        <span class="badge bg-white {{ $themeTextClass }} px-2.5 py-1 fs-xxs fw-bold rounded-pill">
                            <i class="bi {{ $isBkp ? 'bi-people-fill' : 'bi-heart-pulse-fill' }} me-1"></i> {{ $serviceShort }}
                        </span>
                        <span class="badge bg-white text-dark px-2.5 py-1 fs-xxs fw-bold rounded-pill">
                            Siswa: {{ $student->name }}
                        </span>
                        @if($student->zodiac_label)
                            <span class="badge-zodiac shadow-xs" style="background: #ffffff !important; color: #6b21a8 !important; border: 1px solid rgba(255,255,255,.8) !important;">
                                {{ $student->zodiac_label }}
                            </span>
                        @endif
                    </div>

                    <h1 class="h4 fw-extrabold text-white mb-1.5">{{ $pageTitle }}</h1>

                    @if($isBkp)
                        <p class="text-white-50 fs-xs mb-3" style="line-height: 1.6; max-width: 680px;">
                            Sesi belajar dan diskusi terarah bersama teman sebaya dan Guru BK. Membahas kiat belajar efektif, kepercayaan diri, adaptasi sekolah, atau persiapan ujian bersama.
                        </p>
                    @else
                        <p class="text-white-50 fs-xs mb-3" style="line-height: 1.6; max-width: 680px;">
                            Sesi konseling bersama Guru BK dan teman yang menghadapi dinamika serupa dalam suasana saling menjaga rahasia, saling menghargai, dan saling mendukung.
                        </p>
                    @endif

                    <!-- Aksi 1 Tombol Mengajukan Tanpa Perlu Mengisi Apapun -->
                    @if(!$pendingRequest)
                        <form action="{{ route('murid.layanan-kelompok.store') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu ingin mengajukan diri untuk mengikuti sesi {{ $serviceLabel }}? Guru BK akan menyatukanmu ke dalam kelompok dan menetapkan jadwal serta ruangannya.');">
                            @csrf
                            <input type="hidden" name="service_type" value="{{ $filterType }}">
                            <button type="submit" class="btn btn-light {{ $themeTextClass }} rounded-pill px-4 py-2.5 fs-sm fw-extrabold shadow-sm">
                                <i class="bi bi-send-check-fill me-1.5"></i> Ajukan {{ $serviceLabel }}
                            </button>
                        </form>
                    @endif
                </div>

                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <i class="bi {{ $isBkp ? 'bi-people-fill' : 'bi-heart-pulse-fill' }} text-white-50" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Notifikasi Pengajuan Aktif (Menunggu Guru BK) -->
    @if($pendingRequest)
        <div class="card border-0 shadow-sm rounded-4 p-3.5 p-md-4 mb-3 bg-white border-start border-4 border-warning">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0 mt-1 mt-md-0" style="width: 44px; height: 44px;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2.5 py-0.5 fs-xxs fw-bold">
                                <i class="bi bi-clock-history me-1"></i> Menunggu Kelompok & Jadwal
                            </span>
                            <span class="text-muted fs-xxs font-monospace">Tiket: #{{ $pendingRequest->ticket_number }}</span>
                            <span class="text-muted fs-xxs">&bull; Diajukan: {{ $pendingRequest->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 fs-sm">Kamu Sudah Mengajukan Diri untuk {{ $serviceLabel }}</h6>
                        <p class="text-muted fs-xs mb-0" style="line-height: 1.5;">
                            Permohonanmu sudah masuk ke sistem Guru BK. Guru BK yang akan menentukan topik, menyatukanmu ke dalam kelompok yang pas, serta menetapkan jadwal waktu dan tempatnya. Begitu jadwal ditetapkan, detail kelompok akan langsung muncul di halaman ini.
                        </p>
                    </div>
                </div>

                <form action="{{ route('murid.layanan-kelompok.cancel-request', $pendingRequest->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan {{ $serviceLabel }} ini?');" class="flex-shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 fs-xs fw-bold">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Pengajuan
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- SEKSI 1: KELOMPOK TERJADWAL & ANGGOTA (NOTIFIKASI AKTIF)  -->
    <!-- ========================================================= -->
    @if($activeGroups->isNotEmpty())
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2 px-1">
                <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fs-xxs fw-bold animate__animated animate__pulse animate__infinite">
                    <i class="bi bi-bell-fill me-1"></i> PEMBERITAHUAN JADWAL
                </span>
                <h6 class="fw-extrabold text-dark mb-0 fs-sm">Sesi Kelompok Terbentuk & Terjadwal</h6>
            </div>

            <div class="space-y-3">
                @foreach($activeGroups as $grp)
                    <div class="card card-app border-2 {{ $themeBorderClass }} shadow-sm rounded-4 overflow-hidden p-3.5 mb-3 bg-white">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                                    <span class="fw-bold font-monospace {{ $themeTextClass }} fs-xs">{{ $grp->ticket_number }}</span>
                                    <span class="badge {{ $grp->service_type_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                                        {{ $grp->service_type_label }}
                                    </span>
                                    <span class="badge {{ $grp->status_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                                        {{ $grp->status_label }}
                                    </span>
                                </div>
                                <h5 class="fw-extrabold text-dark mb-1 fs-6">{{ $grp->topic }}</h5>
                                <p class="text-muted fs-xs mb-0">{{ Str::limit($grp->description, 140) }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <a href="{{ route('murid.layanan-kelompok.show', $grp) }}" class="btn btn-sm {{ $themeBtnClass }} text-white rounded-pill px-3 py-1.5 fs-xs fw-bold shadow-xs">
                                    <i class="bi bi-eye me-1"></i> Rincian Kelompok
                                </a>
                                <a href="{{ route('murid.layanan-kelompok.printCard', $grp) }}" target="_blank" class="btn btn-sm btn-light border text-danger rounded-pill px-3 py-1.5 fs-xs fw-bold" title="Cetak Kartu Undangan">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Jadwal Pelaksanaan & Ruangan -->
                        <div class="row g-2 mb-3 p-3 rounded-3 bg-light border">
                            <div class="col-12 col-sm-4">
                                <span class="text-muted fs-xxs d-block fw-semibold">📅 Tanggal Pelaksanaan:</span>
                                <strong class="text-dark fs-xs">
                                    {{ $grp->scheduled_date ? $grp->scheduled_date->translatedFormat('l, d F Y') : 'Belum ditentukan' }}
                                </strong>
                            </div>
                            <div class="col-6 col-sm-4">
                                <span class="text-muted fs-xxs d-block fw-semibold">⏰ Jam / Sesi:</span>
                                <strong class="text-dark fs-xs">
                                    {{ $grp->start_time ?? '-' }} - {{ $grp->end_time ?? '-' }} WIB
                                </strong>
                            </div>
                            <div class="col-6 col-sm-4">
                                <span class="text-muted fs-xxs d-block fw-semibold">📍 Tempat / Ruangan:</span>
                                <strong class="text-dark fs-xs">{{ $grp->location ?? 'Ruang BK' }}</strong>
                            </div>
                        </div>

                        <!-- Daftar Teman Anggota dalam 1 Kelompok -->
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-dark fs-xs">
                                    <i class="bi bi-people-fill {{ $themeTextClass }} me-1"></i> Teman-Teman dalam Kelompok Ini ({{ $grp->members->count() }} Siswa):
                                </span>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse($grp->members as $mem)
                                    @php $isMe = $mem->id === $student->id; @endphp
                                    <div class="p-2 rounded-3 border d-flex align-items-center gap-2 {{ $isMe ? $themeBgSubtle . ' ' . $themeBorderClass : 'bg-white' }}" style="font-size: 0.8rem;">
                                        <img src="{{ $mem->photo_url }}" alt="{{ $mem->name }}" class="rounded-circle shadow-xs" width="28" height="28" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark {{ $isMe ? $themeTextClass : '' }}" style="line-height: 1.2;">
                                                {{ $mem->name }} {!! $isMe ? '<span class="badge ' . ($isBkp ? 'bg-mint' : 'bg-purple') . ' text-white ms-0.5 rounded-pill" style="font-size: 0.6rem;">Kamu</span>' : '' !!}
                                            </div>
                                            <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                                <span class="text-muted" style="font-size: 0.68rem;">{{ $mem->classrooms->first()?->name ?? 'Siswa' }}</span>
                                                @if($mem->zodiac_label)
                                                    <span class="badge-zodiac" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;">
                                                        {{ $mem->zodiac_label }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-muted fs-xxs">Belum ada anggota lain yang terdaftar.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- SEKSI 2: RIWAYAT PENGAJUAN MANDIRI SISWA                 -->
    <!-- ========================================================= -->
    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
        <div>
            <h6 class="fw-extrabold text-dark mb-0 fs-sm">Riwayat Pengajuan {{ $serviceShort }} Kamu</h6>
            <p class="text-muted fs-xxs mb-0">Daftar permohonan {{ $serviceLabel }} yang pernah kamu kirimkan ke Guru BK.</p>
        </div>
        <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xxs">
            Total {{ $myRequests->total() }} Pengajuan
        </span>
    </div>

    <div class="space-y-3">
        @forelse($myRequests as $req)
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden p-3.5 mb-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="fw-bold font-monospace {{ $themeTextClass }} fs-xs">{{ $req->ticket_number }}</span>
                            <span class="badge {{ $req->service_type_badge_class }} rounded-pill px-2 py-0.5 fs-xxs fw-bold">
                                {{ $req->service_type_label }}
                            </span>
                            <span class="badge {{ $req->status_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                                {{ $req->status_label }}
                            </span>
                        </div>
                        <h5 class="fw-extrabold text-dark mb-1 fs-6">{{ $req->topic }}</h5>
                        <p class="text-muted fs-xs mb-0">{{ Str::limit($req->description, 130) }}</p>
                    </div>

                    <div class="text-md-end flex-shrink-0">
                        <span class="text-muted fs-xxs d-block">Tanggal Pengajuan:</span>
                        <strong class="text-dark fs-xs">{{ $req->created_at->translatedFormat('d M Y - H:i') }} WIB</strong>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pt-1">
                    <div class="fs-xs text-muted">
                        @if($req->status === 'dikelompokkan' && $req->groupCounseling)
                            <span class="text-success fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Telah digabungkan ke Sesi Kelompok #{{ $req->groupCounseling->ticket_number }}
                            </span>
                        @else
                            <span class="text-warning fw-semibold">
                                <i class="bi bi-hourglass-split me-1"></i> Sedang menunggu peninjauan dan pengelompokan oleh Guru BK
                            </span>
                        @endif
                    </div>

                    @if($req->status === 'dikelompokkan' && $req->groupCounseling)
                        <a href="{{ route('murid.layanan-kelompok.show', $req->groupCounseling) }}" class="btn btn-sm {{ $themeBtnClass }} text-white rounded-pill px-3 py-1 fs-xxs fw-bold">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka Sesi Kelompok
                        </a>
                    @elseif($req->status === 'menunggu_kelompok')
                        <form action="{{ route('murid.layanan-kelompok.cancel-request', $req->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan ini?');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2.5 py-0.5 fs-xxs fw-bold">
                                <i class="bi bi-trash me-1"></i> Batalkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card card-app border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="p-3 rounded-circle {{ $themeBgSubtle }} {{ $themeTextClass }} d-inline-flex mx-auto mb-2" style="width: 50px; height: 50px; align-items: center; justify-content: center;">
                    <i class="bi {{ $isBkp ? 'bi-people' : 'bi-heart-pulse' }} fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark fs-sm mb-1">Belum Ada Pengajuan {{ $serviceShort }}</h6>
                <p class="text-muted fs-xs mb-3">Kamu belum pernah mengajukan {{ $serviceLabel }}. Cukup tekan 1 tombol di bawah untuk mengajukan diri ke Guru BK.</p>
                @if(!$pendingRequest)
                    <div>
                        <form action="{{ route('murid.layanan-kelompok.store') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu ingin mengajukan diri untuk mengikuti sesi {{ $serviceLabel }}? Guru BK akan menyatukanmu ke dalam kelompok dan menetapkan jadwal serta ruangannya.');">
                            @csrf
                            <input type="hidden" name="service_type" value="{{ $filterType }}">
                            <button type="submit" class="btn {{ $themeBtnClass }} text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs">
                                <i class="bi bi-plus-circle me-1"></i> Ajukan {{ $serviceLabel }} Sekarang
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    @if($myRequests->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $myRequests->links() }}
        </div>
    @endif
</div>
@endsection
