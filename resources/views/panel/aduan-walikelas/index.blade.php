@extends('layouts.panel')

@section('title', 'Aduan Kasus dari Wali Kelas')
@section('breadcrumb')
    <span class="current">Aduan Kasus Wali Kelas</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2.5 rounded-3 bg-danger-subtle text-danger d-inline-flex flex-shrink-0 mt-0.5">
                        <i class="bi bi-megaphone-fill fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Laporan Wali Kelas
                            </span>
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Kolaborasi Guru BK
                            </span>
                        </div>
                        <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Aduan Kasus dari Wali Kelas</h1>
                        <p class="text-muted fs-xs mb-0">Daftar kasus insidental murid yang dilaporkan oleh Wali Kelas agar segera ditindaklanjuti oleh Tim Guru BK.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 fs-xs" placeholder="Cari kasus, murid, atau wali kelas..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <select name="status" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu_tindak_lanjut" {{ request('status') === 'menunggu_tindak_lanjut' ? 'selected' : '' }}>Menunggu Tindak Lanjut</option>
                        <option value="sedang_ditangani" {{ request('status') === 'sedang_ditangani' ? 'selected' : '' }}>Sedang Ditangani</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 d-flex gap-1.5">
                    <button type="submit" class="btn btn-mint text-white btn-sm w-100 fw-bold rounded-pill shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('panel.aduan-walikelas.index') }}" class="btn btn-light border btn-sm rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Info Counter -->
    <div class="d-flex justify-content-between align-items-center px-1">
        <span class="fw-extrabold text-dark fs-sm">Daftar Aduan Masuk</span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">
            Menampilkan {{ $complaints->firstItem() ?? 0 }}-{{ $complaints->lastItem() ?? 0 }} dari {{ $complaints->total() }} laporan
        </span>
    </div>

    <!-- ========================================== -->
    <!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
    <!-- ========================================== -->
    <div class="d-md-none mb-3">
        @forelse($complaints as $index => $comp)
            <div class="mobile-card mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pb-3 border-bottom">
                    <div>
                        <span class="text-muted small d-block">
                            <i class="bi bi-calendar-event me-1 text-mint"></i>{{ $comp->incident_date->format('d M Y') }}
                        </span>
                        <span class="badge bg-teal-subtle text-mint badge-pill-custom mt-1">
                            Kelas {{ $comp->classroom?->name }}
                        </span>
                    </div>
                    <span class="badge rounded-pill {{ $comp->status_badge_class }} badge-pill-custom text-nowrap flex-shrink-0">
                        {{ $comp->status_label }}
                    </span>
                </div>

                <!-- Pelapor & Murid -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="card-info-box h-100 p-2.5">
                            <span class="text-muted small d-block mb-1">Wali Kelas:</span>
                            <div class="fw-bold text-dark fs-6 text-truncate">{{ $comp->teacher?->name }}</div>
                            <div class="text-muted small">NIP: {{ $comp->teacher?->nip_nuptk ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-info-box h-100 p-2.5 bg-teal-subtle border-teal">
                            <span class="text-mint small d-block mb-1 fw-bold">Murid Terkait:</span>
                            <div class="fw-bold text-dark fs-6 text-truncate">{{ $comp->student?->name }}</div>
                            <div class="text-muted small">NISN: {{ $comp->student?->nisn }}</div>
                        </div>
                    </div>
                </div>

                <!-- Masalah -->
                <div class="card-info-box mb-3">
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle badge-pill-custom mb-1.5 d-inline-block">
                        {{ $comp->category_label }}
                    </span>
                    <h3 class="card-name mb-2 fs-6">{{ $comp->title }}</h3>
                    @if($comp->teacher_notes)
                        <div class="p-2.5 rounded-3 bg-white border text-muted small mt-2" style="line-height: 1.55;">
                            <span class="text-muted small d-block fw-bold mb-1 text-uppercase">Catatan Wali Kelas:</span>
                            {{ $comp->teacher_notes }}
                        </div>
                    @endif
                </div>

                <!-- Button Aksi Penuh -->
                <div>
                    <a href="{{ route('panel.aduan-walikelas.show', $comp) }}" class="btn btn-mint text-white btn-card-action w-100 shadow-sm">
                        <i class="bi bi-reply-fill me-1"></i> Tinjau & Respon Kasus <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="mobile-card text-center py-5 px-3 mb-3">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted opacity-50"></i>
                <p class="text-muted fs-xs mb-0">Belum ada laporan kasus murid dari Wali Kelas.</p>
            </div>
        @endforelse
    </div>

    <!-- ========================================== -->
    <!-- 2. TAMPILAN DESKTOP: TABEL (>= 768px)      -->
    <!-- ========================================== -->
    <div class="d-none d-md-block">
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 50px;" class="ps-3.5 text-center">No</th>
                            <th style="width: 110px;">Tanggal</th>
                            <th>Wali Kelas Pelapor</th>
                            <th>Murid Terkait</th>
                            <th style="width: 110px;">Rombel</th>
                            <th>Kategori Kasus</th>
                            <th>Pokok Masalah</th>
                            <th class="text-center" style="width: 150px;">Status</th>
                            <th class="text-end pe-3.5" style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @forelse($complaints as $index => $comp)
                            <tr>
                                <td class="text-center fw-bold text-muted ps-3.5">{{ $complaints->firstItem() + $index }}</td>
                                <td class="fw-semibold text-muted">{{ $comp->incident_date->format('d M Y') }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $comp->teacher?->name }}</div>
                                    <div class="text-muted fs-xxs">NIP: {{ $comp->teacher?->nip_nuptk ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $comp->student?->name }}</div>
                                    <div class="text-muted fs-xxs">NISN: <code class="text-mint">{{ $comp->student?->nisn }}</code></div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                        Kelas {{ $comp->classroom?->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                        {{ $comp->category_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 200px;">{{ $comp->title }}</div>
                                    <div class="text-muted fs-xxs text-truncate" style="max-width: 200px;">{{ $comp->teacher_notes }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $comp->status_badge_class }} px-2.5 py-1">
                                        {{ $comp->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-3.5">
                                    <a href="{{ route('panel.aduan-walikelas.show', $comp) }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1 fw-bold fs-xxs shadow-xs">
                                        <i class="bi bi-reply-fill me-1"></i> Respon
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                    Belum ada laporan kasus murid dari Wali Kelas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($complaints->hasPages())
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
                {{ $complaints->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
