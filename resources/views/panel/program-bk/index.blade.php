@extends('layouts.panel')

@section('title', 'Program Bimbingan & Konseling (Prota, Promes, RPL)')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                        <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Perencanaan BK</span>
                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Prota &bull; Promes &bull; RPL</span>
                    </div>
                    <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Pengelolaan Program BK</h1>
                    <p class="text-muted fs-xs mb-0">Rencana Program Tahunan, Program Semester, dan RPL berbasis asesmen IMS.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold fs-xs shadow-xs" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Program
                    </button>
                    <a href="{{ route('panel.program-bk.rpl.create') }}" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-1.5 fw-bold fs-xs shadow-xs">
                        <i class="bi bi-file-earmark-plus me-1"></i> Susun RPL Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-2 g-md-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Agenda Program Direncanakan</span>
                        <h4 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalPrograms }} Program</h4>
                        <span class="text-muted fs-xxs">Prota & Promes Semester Berjalan</span>
                    </div>
                    <div class="p-2.5 bg-teal-subtle rounded-3 text-mint">
                        <i class="bi bi-calendar2-range fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Dokumen RPL</span>
                        <h4 class="fw-extrabold text-primary mt-1 mb-0">{{ $totalRpls }} RPL</h4>
                        <span class="text-muted fs-xxs">Siap dilaksanakan</span>
                    </div>
                    <div class="p-2.5 bg-primary-subtle rounded-3 text-primary">
                        <i class="bi bi-file-earmark-ruled fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Landasan Program</span>
                        <h5 class="fw-extrabold text-success mt-1 mb-0">Hasil IMS Siswa</h5>
                        <span class="text-muted fs-xxs">Asesmen aktual</span>
                    </div>
                    <div class="p-2.5 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.program-bk.index') }}" class="row g-2 align-items-center">
                <div class="col-6 col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($academicYears as $y)
                            <option value="{{ $y->id }}" {{ $academicYearId == $y->id ? 'selected' : '' }}>
                                TP {{ $y->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="semester_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Semester --</option>
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>
                                Semester {{ $s->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="guidance_field_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Bidang --</option>
                        @foreach($guidanceFields as $f)
                            <option value="{{ $f->id }}" {{ $fieldId == $f->id ? 'selected' : '' }}>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="service_type_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Layanan BK --</option>
                        @foreach($serviceTypes as $srv)
                            <option value="{{ $srv->id }}" {{ $serviceId == $srv->id ? 'selected' : '' }}>
                                {{ $srv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-1">
                    @if(request()->hasAny(['academic_year_id', 'semester_id', 'guidance_field_id', 'service_type_id']))
                        <a href="{{ route('panel.program-bk.index') }}" class="btn btn-sm btn-light border w-100 rounded-pill fs-xs" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Info Counter -->
    <div class="d-flex justify-content-between align-items-center px-1">
        <span class="fw-extrabold text-dark fs-sm">Matriks Program BK</span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">Total {{ $programs->count() }} Kegiatan</span>
    </div>

    <!-- ========================================== -->
    <!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
    <!-- ========================================== -->
    <div class="d-md-none space-y-3 mb-3">
        @forelse($programs as $item)
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                        <div>
                            <span class="badge bg-teal-subtle text-mint px-2 py-0.5 rounded fs-xxs fw-bold mb-1">
                                <i class="bi bi-calendar-event me-1"></i>{{ $item->month_planned ?: 'Fleksibel' }}
                            </span>
                            <div class="fw-bold text-dark fs-sm">{{ $item->name }}</div>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded fs-xxs text-nowrap">
                            Kelas {{ $item->target_level }}
                        </span>
                    </div>

                    @if($item->goals)
                        <div class="text-muted fs-xs mb-2.5" style="line-height: 1.5;">
                            {{ $item->goals }}
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-1.5 mb-2.5">
                        <span class="badge bg-primary-subtle text-primary px-2 py-0.5 rounded-pill fs-xxs">
                            <i class="bi bi-bookmark me-0.5"></i>{{ $item->guidanceField?->name ?? '-' }}
                        </span>
                        <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs">
                            {{ $item->serviceType?->name ?? '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <div>
                            @if($item->rpls->count() > 0)
                                <a href="{{ route('panel.program-bk.rpl.show', $item->rpls->first()->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fs-xs fw-bold">
                                    <i class="bi bi-file-earmark-check me-1"></i> Lihat RPL ({{ $item->rpls->count() }})
                                </a>
                            @else
                                <a href="{{ route('panel.program-bk.rpl.create', ['program_id' => $item->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fs-xs fw-bold">
                                    <i class="bi bi-plus-lg me-1"></i> Susun RPL
                                </a>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('panel.program-bk.destroy', $item) }}" class="d-inline m-0" onsubmit="return confirm('Hapus butir program ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card card-app border-0 shadow-sm rounded-4 text-center py-5 px-3">
                <i class="bi bi-calendar2-x fs-1 d-block mb-2 text-secondary"></i>
                <p class="text-muted fs-xs mb-0">Belum ada agenda program bimbingan untuk filter yang dipilih.</p>
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
                            <th style="width: 120px;" class="ps-3.5">Rencana Bulan</th>
                            <th>Topik / Nama Program Layanan</th>
                            <th style="width: 150px;">Bidang BK</th>
                            <th style="width: 180px;">Layanan BK</th>
                            <th style="width: 100px;" class="text-center">Sasaran</th>
                            <th style="width: 130px;" class="text-center">Dokumen RPL</th>
                            <th style="width: 100px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @forelse($programs as $item)
                            <tr>
                                <td class="ps-3.5">
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded fs-xxs fw-semibold">
                                        <i class="bi bi-calendar-event me-1 text-mint"></i>
                                        {{ $item->month_planned ?: 'Fleksibel' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    @if($item->goals)
                                        <div class="text-muted fs-xxs mt-0.5">{{ Str::limit($item->goals, 90) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-2 py-0.5 rounded-pill fs-xxs">
                                        {{ $item->guidanceField?->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs">
                                        {{ $item->serviceType?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5 rounded fs-xxs">
                                        Kelas {{ $item->target_level }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->rpls->count() > 0)
                                        <a href="{{ route('panel.program-bk.rpl.show', $item->rpls->first()->id) }}" class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded-pill fs-xxs fw-bold">
                                            <i class="bi bi-file-earmark-check me-1"></i> Ada ({{ $item->rpls->count() }})
                                        </a>
                                    @else
                                        <a href="{{ route('panel.program-bk.rpl.create', ['program_id' => $item->id]) }}" class="badge bg-light text-primary border text-decoration-none px-2 py-1 rounded-pill fs-xxs fw-semibold">
                                            + Susun RPL
                                        </a>
                                    @endif
                                </td>
                                <td class="text-end pe-3.5">
                                    <form method="POST" action="{{ route('panel.program-bk.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus butir program ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-muted">
                                    <i class="bi bi-calendar2-x fs-1 d-block mb-2 text-secondary"></i>
                                    Tidak ada data matriks program bimbingan & konseling.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Program BK -->
<div class="modal fade" id="addProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="POST" action="{{ route('panel.program-bk.store') }}">
                @csrf
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="bi bi-calendar-plus text-mint me-1.5"></i> Tambah Program Layanan BK Baru
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 space-y-3">
                    <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">
                    <input type="hidden" name="semester_id" value="{{ $semesterId }}">

                    <div>
                        <label class="form-label fw-bold small text-dark">Topik / Nama Program Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Bimbingan Klasikal Mengenal Gaya Belajar Efektif" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Bidang BK <span class="text-danger">*</span></label>
                            <select name="guidance_field_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Bidang --</option>
                                @foreach($guidanceFields as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Jenis Layanan BK (9 Layanan) <span class="text-danger">*</span></label>
                            <select name="service_type_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($serviceTypes as $srv)
                                    <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Tingkat Kelas Sasaran <span class="text-danger">*</span></label>
                            <select name="target_level" class="form-select form-select-sm" required>
                                <option value="VII">Kelas VII (Tujuh)</option>
                                <option value="VIII">Kelas VIII (Delapan)</option>
                                <option value="IX">Kelas IX (Sembilan)</option>
                                <option value="X">Kelas X (Sepuluh)</option>
                                <option value="XI">Kelas XI (Sebelas)</option>
                                <option value="XII">Kelas XII (Dua Belas)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Rencana Bulan Pelaksanaan</label>
                            <input type="text" name="month_planned" class="form-control form-control-sm" placeholder="Contoh: Agustus / Minggu ke-2">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-dark">Tujuan / Sasaran Layanan</label>
                        <textarea name="goals" rows="2" class="form-control form-control-sm" placeholder="Uraikan tujuan yang ingin dicapai melalui program layanan ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-mint text-white rounded-pill px-4 fw-bold shadow-xs">
                        Simpan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
