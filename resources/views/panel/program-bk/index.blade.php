@extends('layouts.panel')

@section('title', 'Program Bimbingan & Konseling (Prota, Promes, RPL)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Perencanaan BK</span>
                <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs fw-medium rounded-pill">Prota &bull; Promes &bull; RPL</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Pengelolaan Program BK</h1>
            <p class="text-muted small mb-0">Rencana Program Tahunan, Program Semester, dan Rencana Pelaksanaan Layanan (RPL) berbasis asesmen IMS.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Program
            </button>
            <a href="{{ route('panel.program-bk.rpl.create') }}" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                <i class="bi bi-file-earmark-plus me-1"></i> Susun RPL Baru
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Agenda Program Direncanakan</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalPrograms }} Program</h3>
                        <span class="text-muted fs-xs">Prota & Promes Semester Berjalan</span>
                    </div>
                    <div class="p-3 bg-emerald-subtle rounded-3 text-emerald">
                        <i class="bi bi-calendar2-range fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Dokumen RPL Tersusun</span>
                        <h3 class="fw-bold text-primary mt-1 mb-0">{{ $totalRpls }} RPL</h3>
                        <span class="text-muted fs-xs">Siap dilaksanakan konselor</span>
                    </div>
                    <div class="p-3 bg-primary-subtle rounded-3 text-primary">
                        <i class="bi bi-file-earmark-ruled fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Landasan Program</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">Hasil IMS Siswa</h4>
                        <span class="text-muted fs-xs">Berbasis data asesmen aktual</span>
                    </div>
                    <div class="p-3 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.program-bk.index') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($academicYears as $y)
                            <option value="{{ $y->id }}" {{ $academicYearId == $y->id ? 'selected' : '' }}>
                                TP {{ $y->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Semester --</option>
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>
                                Semester {{ $s->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="guidance_field_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Bidang --</option>
                        @foreach($guidanceFields as $f)
                            <option value="{{ $f->id }}" {{ $fieldId == $f->id ? 'selected' : '' }}>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="service_type_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Layanan (9) --</option>
                        @foreach($serviceTypes as $srv)
                            <option value="{{ $srv->id }}" {{ $serviceId == $srv->id ? 'selected' : '' }}>
                                {{ $srv->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    @if(request()->hasAny(['academic_year_id', 'semester_id', 'guidance_field_id', 'service_type_id']))
                        <a href="{{ route('panel.program-bk.index') }}" class="btn btn-sm btn-light border w-100" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Program Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark">Matriks Program Bimbingan & Konseling</span>
            <span class="badge bg-light text-muted border">Total {{ $programs->count() }} Kegiatan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted">
                    <tr>
                        <th style="width: 110px;">Rencana Bulan</th>
                        <th>Topik / Nama Program Layanan</th>
                        <th style="width: 140px;">Bidang BK</th>
                        <th style="width: 190px;">Layanan BK</th>
                        <th style="width: 110px;" class="text-center">Sasaran</th>
                        <th style="width: 110px;" class="text-center">Dokumen RPL</th>
                        <th style="width: 120px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($programs as $item)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded fs-xs fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-emerald"></i>
                                    {{ $item->month_planned ?: 'Fleksibel' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                @if($item->goals)
                                    <div class="text-muted fs-xs mt-1">{{ Str::limit($item->goals, 90) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill fs-xs">
                                    {{ $item->guidanceField?->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-2 fs-xs">
                                    {{ $item->serviceType?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded fs-xs">
                                    Kelas {{ $item->target_level }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->rpls->count() > 0)
                                    <a href="{{ route('panel.program-bk.rpl.show', $item->rpls->first()->id) }}" class="badge bg-success-subtle text-success text-decoration-none px-2 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-file-earmark-check me-1"></i> Ada ({{ $item->rpls->count() }})
                                    </a>
                                @else
                                    <a href="{{ route('panel.program-bk.rpl.create', ['program_id' => $item->id]) }}" class="badge bg-light text-primary border text-decoration-none px-2 py-1 rounded-pill fs-xs">
                                        + Susun RPL
                                    </a>
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('panel.program-bk.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Hapus butir program ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2 fs-xs" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar2-x fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada rencana program BK untuk semester ini. Silakan klik "Tambah Program" atau "Susun RPL Baru".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Program BK -->
<div class="modal fade" id="addProgramModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('panel.program-bk.store') }}">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-sm fw-bold">Tambah Rencana Program BK (Prota / Promes)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body space-y-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Tahun Ajaran</label>
                            <select name="academic_year_id" class="form-select form-select-sm" required>
                                @foreach($academicYears as $y)
                                    <option value="{{ $y->id }}" {{ $academicYearId == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Semester</label>
                            <select name="semester_id" class="form-select form-select-sm">
                                @foreach($semesters as $s)
                                    <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>Semester {{ $s->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Nama / Topik Program Layanan</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Pengenalan Gaya Belajar Efektif" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Bidang Bimbingan</label>
                            <select name="guidance_field_id" class="form-select form-select-sm" required>
                                @foreach($guidanceFields as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Layanan BK</label>
                            <select name="service_type_id" class="form-select form-select-sm" required>
                                @foreach($serviceTypes as $srv)
                                    <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Sasaran Kelas</label>
                            <input type="text" name="target_level" class="form-control form-control-sm" placeholder="Contoh: VII, VIII, IX atau Semua" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Rencana Bulan</label>
                            <input type="text" name="month_planned" class="form-control form-control-sm" placeholder="Contoh: Juli Minggu ke-2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Tujuan Program</label>
                        <textarea name="goals" class="form-control form-control-sm" rows="2" placeholder="Tujuan yang diharapkan dicapai murid..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm bg-emerald border-0">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
