@extends('layouts.panel')

@section('title', 'Layanan Pengaduan & Perlindungan Anak')

@section('breadcrumb')
    <span class="current">Pengaduan Siswa</span>
@endsection

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .complaint-card {
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .complaint-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
    }
    .complaint-card:active {
        transform: scale(0.995);
    }
    
    .table-pengaduan th,
    .table-pengaduan td {
        vertical-align: middle;
        padding: 0.75rem 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="space-y-4">

    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2.5 rounded-3 bg-teal-subtle text-mint d-inline-flex flex-shrink-0 mt-0.5">
                        <i class="bi bi-shield-exclamation fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Pelayanan Darurat & Pengaduan
                            </span>
                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Perlindungan Anak
                            </span>
                        </div>
                        <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Layanan Pengaduan Anak</h1>
                        <p class="text-muted fs-xs mb-0">Saluran pemantauan, verifikasi, dan investigasi kasus perundungan, kekerasan, serta kerawanan keselamatan murid.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Status Tabs (Scrollable on Mobile) -->
    <div class="d-flex gap-2 overflow-x-auto pb-1 no-scrollbar">
        <a href="{{ route('panel.pengaduan.index') }}" 
           class="btn btn-sm {{ (!$status && !$riskOnly) ? 'btn-mint text-white' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            Semua ({{ $counters['all'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['risk' => 1]) }}" 
           class="btn btn-sm {{ $riskOnly ? 'btn-danger text-white' : 'btn-outline-danger' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-shield-fill-exclamation me-1"></i> Risiko Bahaya ({{ $counters['risk'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'pending']) }}" 
           class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi ({{ $counters['pending'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'investigating']) }}" 
           class="btn btn-sm {{ $status === 'investigating' ? 'btn-info text-dark' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-search me-1"></i> Sedang Ditindaklanjuti ({{ $counters['investigating'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'resolved']) }}" 
           class="btn btn-sm {{ $status === 'resolved' ? 'btn-success text-white' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-check2-circle me-1"></i> Selesai Ditangani ({{ $counters['resolved'] }})
        </a>
    </div>

    <!-- Filter Bar Card -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-3.5">
            <form method="GET" action="{{ route('panel.pengaduan.index') }}" class="row g-2 align-items-center">
                <!-- Search input -->
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 fs-xs" placeholder="Cari No. Tiket / Deskripsi / Pelapor...">
                    </div>
                </div>
                
                <!-- Category select -->
                <div class="col-6 col-md-3">
                    <select name="category" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status select -->
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="investigating" {{ $status == 'investigating' ? 'selected' : '' }}>Sedang Ditangani</option>
                        <option value="resolved" {{ $status == 'resolved' ? 'selected' : '' }}>Selesai Tertangani</option>
                        <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                
                <!-- Filter & Reset button -->
                <div class="col-12 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'category', 'status', 'risk']))
                        <a href="{{ route('panel.pengaduan.index') }}" class="btn btn-sm btn-light border rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Info Bar / Counter -->
    <div class="d-flex justify-content-between align-items-center px-1">
        <span class="fw-extrabold text-dark fs-sm">
            Daftar Laporan Masuk
        </span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">
            Menampilkan {{ $complaints->firstItem() ?? 0 }}-{{ $complaints->lastItem() ?? 0 }} dari {{ $complaints->total() }} laporan
        </span>
    </div>

    <!-- ========================================== -->
    <!-- 1. TAMPILAN MOBILE: CARD LIST (Layar < 768px) -->
    <!-- ========================================== -->
    <div class="d-md-none mb-3">
        @forelse($complaints as $item)
            <div class="mobile-card mb-3 position-relative {{ $item->safety_risk_flag ? 'border-start border-danger border-4' : '' }}">
                
                <!-- Ribbon Bahaya jika Risiko Keselamatan -->
                @if($item->safety_risk_flag)
                    <div class="bg-danger text-white py-1.5 px-3 small fw-bold d-flex align-items-center justify-content-between rounded-3 mb-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-fill-exclamation fs-6"></i> RISIKO KEAMANAN & KESELAMATAN
                        </span>
                        <span class="badge bg-white text-danger fw-extrabold px-2 py-0.5 rounded-pill">DARURAT</span>
                    </div>
                @endif

                <!-- Header Card: Tiket, Tanggal, & Status -->
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pb-3 border-bottom">
                    <div>
                        <span class="font-monospace fw-bold text-mint fs-6 d-block mb-1">
                            #{{ $item->ticket_number }}
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-clock me-1 text-mint"></i>{{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <span class="badge {{ $item->status_badge_class }} badge-pill-custom text-nowrap flex-shrink-0">
                        {{ $item->status_label }}
                    </span>
                </div>

                <!-- Identitas Pelapor -->
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-teal-subtle text-mint d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" 
                         style="width: 48px; height: 48px; font-size: 1.15rem;">
                        {{ strtoupper(substr($item->student?->name ?? $item->reporter_name ?? 'M', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden flex-grow-1">
                        <h3 class="card-name mb-1 text-truncate">
                            {{ $item->student?->name ?? $item->reporter_name ?? 'Murid' }}
                        </h3>
                        <div class="card-sub d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-teal-subtle text-mint badge-pill-custom">
                                Kelas {{ $item->student?->classrooms->first()?->name ?? 'Belum ada rombel' }}
                            </span>
                            <span>NISN: <code class="text-mint fw-bold">{{ $item->student?->nisn ?? '-' }}</code></span>
                        </div>
                    </div>
                </div>

                <!-- Badges Kategori & Risiko -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-teal-subtle text-mint badge-pill-custom">
                        <i class="bi bi-tag-fill me-1"></i>{{ $item->category }}
                    </span>
                    @if($item->safety_risk_flag)
                        <span class="badge bg-danger text-white badge-pill-custom">
                            <i class="bi bi-shield-fill-exclamation me-1"></i>Risiko Keselamatan
                        </span>
                    @endif
                </div>

                <!-- Uraian Kronologi Box (Terbaca rapi, bernafas) -->
                <div class="card-info-box mb-3">
                    <span class="text-muted small d-block fw-bold mb-1 text-uppercase">Kronologi Laporan:</span>
                    <p class="text-dark mb-2" style="line-height: 1.6;">
                        {{ $item->description }}
                    </p>
                    <div class="d-flex align-items-center justify-content-between text-muted small pt-2 border-top">
                        <div class="text-truncate me-2" title="Waktu Kejadian">
                            <i class="bi bi-calendar-event me-1 text-mint"></i>
                            <span class="text-dark fw-semibold">
                                {{ $item->incident_date ? $item->incident_date->translatedFormat('d M Y') : 'Waktu tdk spesifik' }}
                            </span>
                        </div>
                        <div class="text-truncate text-end" title="Tempat Kejadian">
                            <i class="bi bi-geo-alt me-1 text-danger"></i>
                            <span class="text-dark fw-semibold">{{ $item->incident_location ?? 'Sekolah' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Penuh Ramah Jempol (Touch-friendly) -->
                <div class="d-flex align-items-center gap-2">
                    @if($item->status === 'resolved' || $item->status === 'closed')
                        <a href="{{ route('panel.pengaduan.printReport', $item->id) }}" target="_blank" class="btn btn-outline-danger btn-card-icon" title="Cetak Arsip Laporan PDF">
                            <i class="bi bi-file-earmark-pdf fs-5"></i>
                        </a>
                    @endif
                    <a href="{{ route('panel.pengaduan.show', $item->id) }}" 
                       class="btn btn-mint text-white btn-card-action flex-grow-1 shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> Periksa Detail & Tindak Lanjut <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="mobile-card text-center py-5 px-3 mb-3">
                <div class="p-3 rounded-circle bg-light text-success d-inline-flex mx-auto mb-2">
                    <i class="bi bi-shield-check fs-1"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Tidak Ada Laporan Pengaduan</h6>
                <p class="text-muted fs-xs mb-0">Belum ada pengaduan murid yang sesuai dengan filter pencarian ini.</p>
            </div>
        @endforelse
    </div>

    <!-- ========================================== -->
    <!-- 2. TAMPILAN DESKTOP: TABEL MODERN (Layar >= 768px) -->
    <!-- ========================================== -->
    <div class="d-none d-md-block">
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive mb-0">
                <table class="table table-hover align-middle table-pengaduan mb-0">
                    <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="min-width: 140px;" class="ps-3.5">No. Tiket</th>
                            <th style="min-width: 180px;">Murid Pelapor</th>
                            <th style="min-width: 250px;">Kategori & Kronologi</th>
                            <th style="min-width: 110px;" class="text-center">Risiko Keamanan</th>
                            <th style="min-width: 140px;">Waktu & Lokasi</th>
                            <th style="min-width: 130px;" class="text-center">Status</th>
                            <th style="min-width: 90px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @forelse($complaints as $item)
                            <tr class="{{ $item->safety_risk_flag ? 'table-danger-subtle' : '' }}">
                                <!-- No. Tiket & Waktu Kirim -->
                                <td class="ps-3.5">
                                    <span class="fw-bold font-monospace text-mint fs-xs d-block">
                                        {{ $item->ticket_number }}
                                    </span>
                                    <div class="text-muted fs-xxs">
                                        <i class="bi bi-clock me-0.5"></i>{{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </td>

                                <!-- Murid Pelapor -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-teal-subtle text-mint d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" 
                                             style="width: 32px; height: 32px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($item->student?->name ?? $item->reporter_name ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark fs-xs text-truncate" title="{{ $item->student?->name ?? $item->reporter_name ?? 'Murid' }}">
                                                {{ $item->student?->name ?? $item->reporter_name ?? 'Murid' }}
                                            </div>
                                            <div class="text-muted fs-xxs text-truncate">
                                                NISN: <code class="text-mint">{{ $item->student?->nisn ?? '-' }}</code> 
                                                &bull; <span class="fw-semibold text-dark">{{ $item->student?->classrooms->first()?->name ?? 'VII-A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori & Kronologi -->
                                <td>
                                    <div>
                                        <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs fw-semibold mb-1 d-inline-block">
                                            {{ $item->category }}
                                        </span>
                                        <div class="text-muted fs-xs text-truncate" style="max-width: 280px;" title="{{ $item->description }}">
                                            {{ $item->description }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Risiko Keamanan -->
                                <td class="text-center">
                                    @if($item->safety_risk_flag)
                                        <span class="badge bg-danger text-white px-2.5 py-1 rounded-pill fs-xxs fw-bold">
                                            <i class="bi bi-shield-fill-exclamation me-0.5"></i>Bahaya
                                        </span>
                                    @else
                                        <span class="text-muted fs-xxs"><i class="bi bi-check2 text-success me-0.5"></i>Aman</span>
                                    @endif
                                </td>

                                <!-- Waktu & Lokasi Kejadian -->
                                <td>
                                    <div>
                                        <div class="text-dark fs-xxs fw-semibold">
                                            <i class="bi bi-calendar-event me-0.5 text-mint"></i>
                                            {{ $item->incident_date ? $item->incident_date->translatedFormat('d M Y') : '-' }}
                                        </div>
                                        <div class="text-muted fs-xxs text-truncate" style="max-width: 140px;" title="{{ $item->incident_location ?? 'Sekolah' }}">
                                            <i class="bi bi-geo-alt me-0.5 text-danger"></i>{{ $item->incident_location ?? 'Sekolah' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="text-center">
                                    <span class="badge {{ $item->status_badge_class }} px-2.5 py-1 rounded-pill fs-xxs fw-semibold">
                                        {{ $item->status_label }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="text-end pe-3.5">
                                    <div class="d-flex align-items-center justify-content-end gap-1.5">
                                        @if($item->status === 'resolved' || $item->status === 'closed')
                                            <a href="{{ route('panel.pengaduan.printReport', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fs-xxs fw-semibold shadow-xs" title="Cetak Arsip Laporan PDF">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak
                                            </a>
                                        @endif
                                        <a href="{{ route('panel.pengaduan.show', $item->id) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-pill py-1 px-3 fs-xxs fw-semibold shadow-xs text-nowrap">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="p-3 rounded-circle bg-light d-inline-flex mb-2">
                                        <i class="bi bi-shield-check fs-1 text-success"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Laporan Pengaduan</h6>
                                    <p class="text-muted fs-xs mb-0">Belum ada pengaduan murid yang sesuai dengan filter pencarian ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if($complaints->hasPages())
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto no-scrollbar">
                {{ $complaints->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
