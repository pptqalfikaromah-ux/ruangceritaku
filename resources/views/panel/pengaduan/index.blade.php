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
                
                <!-- Urgency select -->
                <div class="col-6 col-md-3">
                    <select name="urgency" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Tingkat Urgensi --</option>
                        <option value="darurat" {{ $urgency == 'darurat' ? 'selected' : '' }}>Darurat (Immediate)</option>
                        <option value="tinggi" {{ $urgency == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ $urgency == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ $urgency == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                
                <!-- Filter & Reset button -->
                <div class="col-12 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'category', 'urgency', 'status', 'risk']))
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
    <div class="d-md-none space-y-3">
        @forelse($complaints as $item)
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden complaint-card position-relative {{ $item->safety_risk_flag ? 'border-start border-danger border-4' : '' }}">
                
                <!-- Ribbon Bahaya jika Risiko Keselamatan -->
                @if($item->safety_risk_flag)
                    <div class="bg-danger text-white py-1 px-3 fs-xxs fw-bold d-flex align-items-center justify-content-between">
                        <span class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-shield-fill-exclamation"></i> RISIKO KEAMANAN & KESELAMATAN
                        </span>
                        <span class="badge bg-white text-danger fw-extrabold px-2 py-0.5 rounded-pill">DARURAT</span>
                    </div>
                @endif

                <div class="card-body p-3">
                    <!-- Header Card: Tiket, Tanggal, & Status -->
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                        <div>
                            <span class="font-monospace fw-bold text-mint fs-xs d-block">
                                {{ $item->ticket_number }}
                            </span>
                            <span class="text-muted fs-xxs">
                                <i class="bi bi-clock me-0.5"></i>{{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        <span class="badge {{ $item->status_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold text-nowrap">
                            {{ $item->status_label }}
                        </span>
                    </div>

                    <!-- Identitas Pelapor -->
                    <div class="d-flex align-items-center gap-2.5 mb-2.5">
                        <div class="rounded-circle bg-teal-subtle text-mint d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" 
                             style="width: 36px; height: 36px; font-size: 0.9rem;">
                            {{ strtoupper(substr($item->student?->name ?? $item->reporter_name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="fw-bold text-dark fs-sm text-truncate">
                                {{ $item->student?->name ?? $item->reporter_name ?? 'Murid' }}
                            </div>
                            <div class="text-muted fs-xxs d-flex flex-wrap align-items-center gap-1">
                                <span class="badge bg-light text-dark border px-1.5 py-0.5 rounded">
                                    {{ $item->student?->classrooms->first()?->name ?? 'Belum ada kelas' }}
                                </span>
                                <span>NISN: <code class="text-mint">{{ $item->student?->nisn ?? '-' }}</code></span>
                            </div>
                        </div>
                    </div>

                    <!-- Badges Kategori & Urgensi -->
                    <div class="d-flex flex-wrap gap-1.5 mb-2">
                        <span class="badge bg-teal-subtle text-mint px-2 py-1 rounded fs-xxs fw-semibold">
                            <i class="bi bi-tag-fill me-1"></i>{{ $item->category }}
                        </span>
                        <span class="badge {{ $item->urgency_badge_class }} px-2 py-1 rounded fs-xxs fw-semibold text-uppercase">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $item->urgency_level }}
                        </span>
                    </div>

                    <!-- Uraian Kronologi Box (Terbaca rapi, tidak terpotong kata) -->
                    <div class="p-2.5 rounded-3 bg-light border text-dark fs-xs mb-2.5" style="line-height: 1.55; max-height: 85px; overflow-y: auto;">
                        <span class="text-muted fs-xxs d-block fw-bold mb-0.5 text-uppercase">Kronologi Laporan:</span>
                        {{ $item->description }}
                    </div>

                    <!-- Meta Waktu & Lokasi Kejadian -->
                    <div class="d-flex align-items-center justify-content-between text-muted fs-xxs pt-2 border-top">
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

                    <!-- Tombol Aksi Penuh Ramah Jempol (Touch-friendly) -->
                    <div class="mt-3 pt-1">
                        <a href="{{ route('panel.pengaduan.show', $item->id) }}" 
                           class="btn btn-mint btn-sm text-white w-100 rounded-pill py-2 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-1.5 fs-xs">
                            <i class="bi bi-shield-check"></i> Periksa Detail & Tindak Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card card-app border-0 shadow-sm rounded-4 text-center py-5 px-3">
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
                            <th style="min-width: 130px;" class="text-center">Urgensi & Risiko</th>
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

                                <!-- Urgensi & Risiko -->
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <span class="badge {{ $item->urgency_badge_class }} px-2 py-0.5 rounded-pill fs-xxs text-uppercase fw-semibold">
                                            {{ $item->urgency_level }}
                                        </span>
                                        @if($item->safety_risk_flag)
                                            <span class="badge bg-danger text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">
                                                <i class="bi bi-shield-fill-exclamation me-0.5"></i>Bahaya
                                            </span>
                                        @endif
                                    </div>
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
                                    <a href="{{ route('panel.pengaduan.show', $item->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill py-1 px-3 fs-xxs fw-semibold shadow-xs text-nowrap">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
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
