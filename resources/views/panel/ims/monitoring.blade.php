@extends('layouts.panel')

@section('title', 'Monitoring Pengisian Instrumen Masalah Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Monitoring Asesmen</span>
                <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs fw-medium rounded-pill">Status Responden Murid</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Monitoring Pengisian IMS</h1>
            <p class="text-muted small mb-0">Pantau progres pengisian 180 butir Instrumen Masalah Siswa per kelas dan semester.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panel.ims.master') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-collection me-1"></i> Master 180 Butir
            </a>
            <a href="{{ route('panel.ims.rekap') }}" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                <i class="bi bi-bar-chart-line me-1"></i> Rekapitulasi Analisis
            </a>
        </div>
    </div>

    <!-- Stat Counter Cards -->
    <div class="row g-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Total Murid</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalStudents }}</h3>
                        <span class="text-muted fs-xs">Target responden</span>
                    </div>
                    <div class="p-3 bg-light rounded-3 text-secondary">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Sudah Terkirim</span>
                        <h3 class="fw-bold text-success mt-1 mb-0">{{ $submittedCount }}</h3>
                        <span class="text-success fs-xs fw-semibold">
                            {{ $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100, 1) : 0 }}% Selesai
                        </span>
                    </div>
                    <div class="p-3 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Simpan Draft</span>
                        <h3 class="fw-bold text-warning-emphasis mt-1 mb-0">{{ $draftCount }}</h3>
                        <span class="text-warning-emphasis fs-xs">Sedang mengisi</span>
                    </div>
                    <div class="p-3 bg-warning-subtle rounded-3 text-warning">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Belum Mengisi</span>
                        <h3 class="fw-bold text-danger mt-1 mb-0">{{ $unfilledCount }}</h3>
                        <span class="text-danger fs-xs">Perlu tindak lanjut</span>
                    </div>
                    <div class="p-3 bg-danger-subtle rounded-3 text-danger">
                        <i class="bi bi-exclamation-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.ims.monitoring') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Nama / NISN / NIS...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="classroom_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas / Rombel --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->level }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="submitted" {{ $statusFilter == 'submitted' ? 'selected' : '' }}>Sudah Dikirim</option>
                        <option value="draft" {{ $statusFilter == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                                Semester {{ $sem->semester }} ({{ $sem->academicYear?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary bg-emerald border-0 w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                    @if(request()->hasAny(['q', 'classroom_id', 'status', 'semester_id']))
                        <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-sm btn-light border" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark">Data Pengisian Murid</span>
            <span class="badge bg-light text-muted border">Menampilkan {{ $paginatedStudents->firstItem() ?? 0 }}-{{ $paginatedStudents->lastItem() ?? 0 }} dari {{ $paginatedStudents->total() }} murid</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Identitas Murid</th>
                        <th style="width: 140px;">Kelas / Rombel</th>
                        <th style="width: 140px;" class="text-center">Status IMS</th>
                        <th style="width: 160px;">Waktu Kirim</th>
                        <th style="width: 120px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($paginatedStudents as $index => $student)
                        <tr>
                            <td class="text-center text-muted">
                                {{ $paginatedStudents->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle border" style="width: 34px; height: 34px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $student->name }}</div>
                                        <div class="text-muted fs-xs">NISN: {{ $student->nisn }} &bull; {{ $student->gender_label }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $currentCls = $student->classrooms->first();
                                @endphp
                                @if($currentCls)
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-2 fs-xs">
                                        {{ $currentCls->name }}
                                    </span>
                                @else
                                    <span class="text-muted fs-xs">- Belum Rombel -</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($student->ims_status === 'submitted')
                                    <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-check-circle me-1"></i> Terkirim
                                    </span>
                                @elseif($student->ims_status === 'draft')
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-hourglass-split me-1"></i> Draft
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill fs-xs">
                                        <i class="bi bi-dash-circle me-1"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($student->current_assessment && $student->current_assessment->submitted_at)
                                    <div class="text-dark fs-xs fw-medium">{{ $student->current_assessment->submitted_at->translatedFormat('d M Y, H:i') }}</div>
                                @elseif($student->current_assessment)
                                    <span class="text-muted fs-xs">Belum dikirimkan</span>
                                @else
                                    <span class="text-muted fs-xs">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($student->current_assessment && $student->ims_status === 'submitted')
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fs-xs">
                                            <i class="bi bi-eye me-1"></i> Periksa
                                        </a>
                                        <a href="{{ route('panel.ims.print-individual', $student->current_assessment->id) }}" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2 fs-xs" title="Cetak Format Resmi AUM (Format 3)">
                                            <i class="bi bi-printer me-1"></i> AUM
                                        </a>
                                    </div>
                                @elseif($student->current_assessment)
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" class="btn btn-sm btn-light border py-1 px-2 fs-xs text-muted">
                                            <i class="bi bi-eye me-1"></i> Draft
                                        </a>
                                        <a href="{{ route('panel.ims.print-individual', $student->current_assessment->id) }}" target="_blank" class="btn btn-sm btn-light border py-1 px-2 fs-xs text-muted" title="Cetak Format Resmi AUM (Draft)">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1 fs-xs">Tidak Ada Data</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada data murid yang ditemukan untuk kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paginatedStudents->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $paginatedStudents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
