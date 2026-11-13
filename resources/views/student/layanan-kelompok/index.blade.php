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
                            👥 Sesi Kelompok Terarah
                        </span>
                        @if($bkpEnabled && $kkpEnabled)
                            <span class="badge bg-white text-mint px-2.5 py-1 fs-xxs fw-bold rounded-pill">BKP & KKP</span>
                        @elseif($bkpEnabled)
                            <span class="badge bg-white text-mint px-2.5 py-1 fs-xxs fw-bold rounded-pill">Bimbingan Kelompok</span>
                        @elseif($kkpEnabled)
                            <span class="badge bg-white text-purple px-2.5 py-1 fs-xxs fw-bold rounded-pill">Konseling Kelompok</span>
                        @endif
                    </div>
                    <h1 class="h4 fw-extrabold text-white mb-1">Bimbingan & Konseling Kelompok</h1>
                    <p class="text-white-50 fs-xs mb-3" style="line-height: 1.6;">
                        Ajukan kebutuhan bimbinganmu secara mandiri. Guru BK akan menyatukanmu dengan kelompok belajar atau teman yang memiliki topik serupa, lalu menjadwalkan sesi bersama.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @if($bkpEnabled)
                            <a href="{{ route('murid.layanan-kelompok.create', ['type' => 'bimbingan_kelompok']) }}" class="btn btn-light text-mint rounded-pill px-3.5 py-2 fs-xs fw-extrabold shadow-sm">
                                <i class="bi bi-people-fill me-1"></i> Ajukan Bimbingan Kelompok (BKP)
                            </a>
                        @endif
                        @if($kkpEnabled)
                            <a href="{{ route('murid.layanan-kelompok.create', ['type' => 'konseling_kelompok']) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fs-xs fw-extrabold shadow-sm">
                                <i class="bi bi-heart-pulse-fill me-1"></i> Ajukan Konseling Kelompok (KKP)
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-4 text-center d-none d-md-block">
                    <i class="bi bi-people-fill text-white-50" style="font-size: 5.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

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
                    <div class="card card-app border-2 border-mint shadow-sm rounded-4 overflow-hidden p-3.5 mb-3 bg-white">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-3 pb-2 border-bottom">
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                                    <span class="fw-bold font-monospace text-mint fs-xs">{{ $grp->ticket_number }}</span>
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
                                <a href="{{ route('murid.layanan-kelompok.show', $grp) }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fs-xs fw-bold shadow-xs">
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
                                    <i class="bi bi-people-fill text-mint me-1"></i> Teman-Teman dalam Kelompok Ini ({{ $grp->members->count() }} Siswa):
                                </span>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse($grp->members as $mem)
                                    @php $isMe = $mem->id === $student->id; @endphp
                                    <div class="p-2 rounded-3 border d-flex align-items-center gap-2 {{ $isMe ? 'bg-teal-subtle border-teal' : 'bg-white' }}" style="font-size: 0.8rem;">
                                        <img src="{{ $mem->photo_url }}" alt="{{ $mem->name }}" class="rounded-circle shadow-xs" width="28" height="28" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark {{ $isMe ? 'text-mint' : '' }}" style="line-height: 1.2;">
                                                {{ $mem->name }} {!! $isMe ? '<span class="badge bg-mint text-white ms-0.5 rounded-pill" style="font-size: 0.6rem;">Kamu</span>' : '' !!}
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
            <h6 class="fw-extrabold text-dark mb-0 fs-sm">Riwayat Pengajuan Mandiri Kamu</h6>
            <p class="text-muted fs-xxs mb-0">Daftar permohonan bimbingan/konseling yang pernah kamu kirimkan ke Guru BK.</p>
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
                            <span class="fw-bold font-monospace text-mint fs-xs">{{ $req->ticket_number }}</span>
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
                        <a href="{{ route('murid.layanan-kelompok.show', $req->groupCounseling) }}" class="btn btn-sm btn-outline-teal rounded-pill px-3 py-1 fs-xxs fw-bold">
                            <i class="bi bi-arrow-right-circle me-1"></i> Buka Sesi Kelompok
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="card card-app border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="p-3 rounded-circle bg-teal-subtle text-mint d-inline-flex mx-auto mb-2" style="width: 50px; height: 50px; align-items: center; justify-content: center;">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark fs-sm mb-1">Belum Ada Pengajuan Layanan Kelompok</h6>
                <p class="text-muted fs-xs mb-3">Kamu belum pernah mengajukan bimbingan atau konseling kelompok. Jangan ragu untuk berdiskusi bersama teman dan Guru BK!</p>
                <div>
                    @if($bkpEnabled)
                        <a href="{{ route('murid.layanan-kelompok.create', ['type' => 'bimbingan_kelompok']) }}" class="btn btn-mint text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Bimbingan Kelompok (BKP)
                        </a>
                    @elseif($kkpEnabled)
                        <a href="{{ route('murid.layanan-kelompok.create', ['type' => 'konseling_kelompok']) }}" class="btn btn-purple text-white rounded-pill px-4 py-2 fs-xs fw-bold shadow-xs">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Konseling Kelompok (KKP)
                        </a>
                    @endif
                </div>
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
