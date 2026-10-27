@extends('layouts.panel')

@section('title', 'Layanan Pengaduan & Perlindungan Anak')

@section('breadcrumb')
    <span class="current">Pengaduan Siswa</span>
@endsection

@push('styles')
<style>
    .table-pengaduan-fit {
        table-layout: fixed;
        width: 100% !important;
        margin-bottom: 0;
    }
    .table-pengaduan-fit th,
    .table-pengaduan-fit td {
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
    }
    .wrapper-pengaduan-fit {
        overflow-x: auto;
    }
    @media (min-width: 992px) {
        .wrapper-pengaduan-fit {
            overflow-x: hidden !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">

    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3.5 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-2.5 rounded-3 bg-teal-subtle text-mint d-inline-flex flex-shrink-0 mt-1">
                        <i class="bi bi-shield-exclamation fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xs fw-semibold rounded-pill">
                                Pengaduan Siswa
                            </span>
                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fs-xs fw-semibold rounded-pill">
                                Perlindungan Keselamatan Anak
                            </span>
                        </div>
                        <h1 class="h4 fw-extrabold text-dark mb-1">Layanan Pengaduan Anak</h1>
                        <p class="text-muted small mb-0">Saluran aman pemantauan, verifikasi, dan investigasi kasus perundungan, kekerasan, dan kerawanan keselamatan murid.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Status Tabs -->
    <div class="d-flex gap-2 overflow-x-auto pb-1">
        <a href="{{ route('panel.pengaduan.index') }}" 
           class="btn btn-sm {{ (!$status && !$riskOnly) ? 'btn-mint text-white' : 'btn-light border text-dark' }} rounded-pill px-3.5 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            Semua ({{ $counters['all'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['risk' => 1]) }}" 
           class="btn btn-sm {{ $riskOnly ? 'btn-danger text-white' : 'btn-outline-danger' }} rounded-pill px-3.5 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-shield-fill-exclamation me-1"></i> Risiko Keselamatan Anak ({{ $counters['risk'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'pending']) }}" 
           class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }} rounded-pill px-3.5 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi ({{ $counters['pending'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'investigating']) }}" 
           class="btn btn-sm {{ $status === 'investigating' ? 'btn-info text-dark' : 'btn-light border text-dark' }} rounded-pill px-3.5 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-search me-1"></i> Sedang Ditindaklanjuti ({{ $counters['investigating'] }})
        </a>
        <a href="{{ route('panel.pengaduan.index', ['status' => 'resolved']) }}" 
           class="btn btn-sm {{ $status === 'resolved' ? 'btn-success text-white' : 'btn-light border text-dark' }} rounded-pill px-3.5 py-1.5 text-nowrap fs-xs fw-bold shadow-xs">
            <i class="bi bi-check2-circle me-1"></i> Selesai Ditangani ({{ $counters['resolved'] }})
        </a>
    </div>

    <!-- Filter Bar Card -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3.5">
            <form method="GET" action="{{ route('panel.pengaduan.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Cari No. Tiket / Deskripsi / Pelapor...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori Pengaduan --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select name="urgency" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Tingkat Urgensi --</option>
                        <option value="darurat" {{ $urgency == 'darurat' ? 'selected' : '' }}>Darurat (Immediate)</option>
                        <option value="tinggi" {{ $urgency == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ $urgency == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ $urgency == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs">
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

    <!-- Table Pengaduan Card (Fits snugly without horizontal scroll) -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Laporan Pengaduan Siswa Masuk</span>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xs">
                Menampilkan {{ $complaints->firstItem() ?? 0 }}-{{ $complaints->lastItem() ?? 0 }} dari {{ $complaints->total() }} laporan
            </span>
        </div>
        
        <div class="wrapper-pengaduan-fit">
            <table class="table table-hover align-middle table-pengaduan-fit">
                <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.03em;">
                    <tr>
                        <th style="width: 12%;" class="ps-3">No. Tiket</th>
                        <th style="width: 17%;">Murid Pelapor</th>
                        <th style="width: 26%;">Kategori & Kronologi</th>
                        <th style="width: 12%;" class="text-center">Urgensi & Risiko</th>
                        <th style="width: 12%;">Waktu & Lokasi</th>
                        <th style="width: 13%;" class="text-center">Status</th>
                        <th style="width: 8%;" class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($complaints as $item)
                        <tr class="{{ $item->safety_risk_flag ? 'table-danger-subtle' : '' }}">
                            <!-- No. Tiket -->
                            <td class="ps-3">
                                <span class="fw-bold font-monospace text-mint fs-xxs d-block text-truncate" title="{{ $item->ticket_number }}">
                                    {{ $item->ticket_number }}
                                </span>
                                <div class="text-muted fs-xxs text-truncate">
                                    {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                </div>
                            </td>

                            <!-- Murid Pelapor -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-teal-subtle text-mint d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" 
                                         style="width: 30px; height: 30px; font-size: 0.78rem;">
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
                                <div class="overflow-hidden">
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs fw-semibold mb-1 d-inline-block text-truncate mw-100" title="{{ $item->category }}">
                                        {{ $item->category }}
                                    </span>
                                    <div class="text-muted fs-xs text-truncate" title="{{ $item->description }}">
                                        {{ $item->description }}
                                    </div>
                                </div>
                            </td>

                            <!-- Urgensi & Risiko -->
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge {{ $item->urgency_badge_class }} px-2 py-0.5 rounded-pill fs-xxs text-uppercase fw-semibold text-truncate mw-100">
                                        {{ $item->urgency_level }}
                                    </span>
                                    @if($item->safety_risk_flag)
                                        <span class="badge bg-danger text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold text-truncate mw-100">
                                            <i class="bi bi-shield-fill-exclamation me-0.5"></i>Bahaya
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Waktu & Lokasi -->
                            <td>
                                <div class="overflow-hidden">
                                    <div class="text-dark fs-xxs fw-semibold text-truncate">
                                        {{ $item->incident_date ? $item->incident_date->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div class="text-muted fs-xxs text-truncate" title="{{ $item->incident_location ?? 'Sekolah' }}">
                                        <i class="bi bi-geo-alt me-0.5"></i>{{ $item->incident_location ?? 'Sekolah' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <span class="badge {{ $item->status_badge_class }} px-2 py-1 rounded-pill fs-xxs fw-semibold d-inline-block text-truncate mw-100" title="{{ $item->status_label }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="text-end pe-3">
                                <a href="{{ route('panel.pengaduan.show', $item->id) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-pill py-1 px-2.5 fs-xxs fw-semibold shadow-xs text-nowrap">
                                    <i class="bi bi-eye"></i> Detail
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
                                <p class="text-muted fs-xs mb-0">Belum ada pengaduan siswa pada kategori atau filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
            <div class="card-footer bg-white py-3 px-3.5 border-top">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
