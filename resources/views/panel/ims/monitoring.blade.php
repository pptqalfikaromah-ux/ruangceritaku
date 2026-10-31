@extends('layouts.panel')

@section('title', 'Monitoring Pengisian Instrumen Masalah Siswa')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                        <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Monitoring Asesmen</span>
                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Status Responden Murid</span>
                    </div>
                    <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Monitoring Pengisian IMS</h1>
                    <p class="text-muted fs-xs mb-0">Pantau progres pengisian 180 butir Instrumen Masalah Siswa per kelas dan semester.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('panel.ims.master') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-bold fs-xs shadow-xs">
                        <i class="bi bi-collection me-1"></i> Master Bank Soal IMS
                    </a>
                    <a href="{{ route('panel.ims.rekap') }}" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-1.5 fw-bold fs-xs shadow-xs">
                        <i class="bi bi-bar-chart-line me-1"></i> Rekapitulasi Analisis
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Feature Toggle Banner (Aktif / Nonaktifkan IMS untuk Murid) -->
    <div class="card border-0 shadow-sm rounded-4 p-3.5 p-sm-4 bg-white" style="border-left: 5px solid {{ $isImsGloballyEnabled ? '#0d9488' : '#f59e0b' }} !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-start align-items-sm-center gap-3 flex-grow-1" style="min-width: 0;">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: {{ $isImsGloballyEnabled ? '#0d9488' : '#d97706' }};">
                    <i class="bi {{ $isImsGloballyEnabled ? 'bi-toggle-on fs-3' : 'bi-toggle-off fs-3' }}"></i>
                </div>
                <div style="min-width: 0; flex: 1 1 auto;">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <span class="fw-bold text-dark fs-6">Status Akses Fitur IMS Siswa:</span>
                        @if($isImsGloballyEnabled)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold fs-xs text-nowrap">
                                <i class="bi bi-check-circle-fill me-1"></i> AKTIF (DITAMPILKAN)
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill fw-bold fs-xs text-nowrap">
                                <i class="bi bi-slash-circle-fill me-1"></i> NONAKTIF (DISEMBUNYIKAN)
                            </span>
                        @endif
                    </div>
                    <p class="text-muted fs-xs mb-0 lh-base" style="line-height: 1.55;">
                        @if($isImsGloballyEnabled)
                            Fitur IMS sedang aktif. Menu "Instrumen Masalah (IMS)" dan asesmen butir masalah tampil di akun seluruh siswa.
                        @else
                            Fitur IMS sedang dinonaktifkan. Seluruh menu dan instrumen IMS dikunci dari portal siswa.
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0">
                <form action="{{ route('panel.ims.toggle-status') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin {{ $isImsGloballyEnabled ? 'MENONAKTIFKAN' : 'MENGAKTIFKAN' }} fitur IMS untuk akun murid?')">
                    @csrf
                    @if($isImsGloballyEnabled)
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 py-2 fw-semibold shadow-xs text-nowrap">
                            <i class="bi bi-power me-1.5"></i> Nonaktifkan Fitur IMS
                        </button>
                    @else
                        <button type="submit" class="btn btn-emerald btn-sm rounded-pill px-4 py-2 fw-semibold shadow-xs text-white text-nowrap" style="background-color: #0d9488; border-color: #0d9488;">
                            <i class="bi bi-check-lg me-1.5"></i> Aktifkan Fitur IMS
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Stat Counter Cards (Compact on Mobile) -->
    <div class="row g-2 g-md-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Total Murid</span>
                        <h4 class="fw-extrabold text-dark mt-1 mb-0">{{ $totalStudents }}</h4>
                        <span class="text-muted fs-xxs">Target responden</span>
                    </div>
                    <div class="p-2.5 bg-light rounded-3 text-secondary">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Sudah Terkirim</span>
                        <h4 class="fw-extrabold text-success mt-1 mb-0">{{ $submittedCount }}</h4>
                        <span class="text-success fs-xxs fw-bold">
                            {{ $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100, 1) : 0 }}% Selesai
                        </span>
                    </div>
                    <div class="p-2.5 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Simpan Draft</span>
                        <h4 class="fw-extrabold text-warning-emphasis mt-1 mb-0">{{ $draftCount }}</h4>
                        <span class="text-warning-emphasis fs-xxs">Sedang mengisi</span>
                    </div>
                    <div class="p-2.5 bg-warning-subtle rounded-3 text-warning">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fs-xs fw-semibold">Belum Mengisi</span>
                        <h4 class="fw-extrabold text-danger mt-1 mb-0">{{ $unfilledCount }}</h4>
                        <span class="text-danger fs-xxs">Perlu tindak lanjut</span>
                    </div>
                    <div class="p-2.5 bg-danger-subtle rounded-3 text-danger">
                        <i class="bi bi-exclamation-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.ims.monitoring') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 fs-xs" placeholder="Nama / NISN / NIS...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="classroom_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Rombel --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->level }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="submitted" {{ $statusFilter == 'submitted' ? 'selected' : '' }}>Sudah Dikirim</option>
                        <option value="draft" {{ $statusFilter == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Mengisi</option>
                    </select>
                </div>
                <div class="col-8 col-md-2">
                    <select name="semester_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                                Smst {{ $sem->semester }} ({{ $sem->academicYear?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'classroom_id', 'status', 'semester_id']))
                        <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-sm btn-light border rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Info Counter -->
    <div class="d-flex justify-content-between align-items-center px-1">
        <span class="fw-extrabold text-dark fs-sm">Data Pengisian Murid</span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">
            Menampilkan {{ $paginatedStudents->firstItem() ?? 0 }}-{{ $paginatedStudents->lastItem() ?? 0 }} dari {{ $paginatedStudents->total() }} murid
        </span>
    </div>

    <!-- ========================================== -->
    <!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
    <!-- ========================================== -->
    <div class="d-md-none space-y-3 mb-3">
        @forelse($paginatedStudents as $index => $student)
            <div class="mobile-card mb-3">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle shadow-xs flex-shrink-0" width="52" height="52" style="object-fit: cover;">
                    <div class="overflow-hidden flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                            <h3 class="card-name mb-0 text-truncate">{{ $student->name }}</h3>
                            @if($student->ims_status === 'submitted')
                                <span class="badge bg-success-subtle text-success badge-pill-custom flex-shrink-0">
                                    <i class="bi bi-check-circle me-1"></i>Terkirim
                                </span>
                            @elseif($student->ims_status === 'draft')
                                <span class="badge bg-warning-subtle text-warning-emphasis badge-pill-custom flex-shrink-0">
                                    <i class="bi bi-hourglass-split me-1"></i>Draft
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger badge-pill-custom flex-shrink-0">
                                    <i class="bi bi-dash-circle me-1"></i>Belum
                                </span>
                            @endif
                        </div>
                        <div class="card-sub d-flex flex-wrap align-items-center gap-2">
                            <span>NISN: <code class="text-mint fw-bold px-1.5 py-0.5 rounded bg-light">{{ $student->nisn }}</code></span>
                            <span>&bull; {{ $student->gender_label }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-info-box mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-door-open me-1.5 text-mint"></i>Rombel</span>
                        <div>
                            @php $currentCls = $student->classrooms->first(); @endphp
                            @if($currentCls)
                                <span class="badge bg-teal-subtle text-mint badge-pill-custom">
                                    Kelas {{ $currentCls->name }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border badge-pill-custom">Belum Masuk Rombel</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                        <span class="text-muted"><i class="bi bi-clock me-1.5 text-mint"></i>Waktu Pengisian</span>
                        <span class="fw-semibold text-dark">
                            @if($student->ims_submitted_at)
                                {{ $student->ims_submitted_at->translatedFormat('d M Y, H:i') }}
                            @else
                                <span class="text-muted">- Belum Mengisi -</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    @if($student->ims_status === 'submitted' && $student->current_assessment)
                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" 
                           class="btn btn-mint text-white btn-card-action w-100 shadow-sm">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Lihat Hasil Analisis & Rekomendasi <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @elseif($student->ims_status === 'draft' && $student->current_assessment)
                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" 
                           class="btn btn-outline-warning text-dark btn-card-action w-100">
                            <i class="bi bi-eye me-1"></i> Tinjau Draft Jawaban
                        </a>
                    @else
                        <button type="button" class="btn btn-light border btn-card-action w-100 text-muted" disabled>
                            <i class="bi bi-dash-circle me-1"></i> Belum Mengisi Angket
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="mobile-card text-center py-5 px-3 mb-3">
                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                <p class="text-muted fs-xs mb-0">Tidak ada murid pada filter yang dipilih.</p>
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
                            <th>Identitas Murid</th>
                            <th style="width: 150px;">Kelas / Rombel</th>
                            <th style="width: 140px;" class="text-center">Status IMS</th>
                            <th style="width: 170px;">Waktu Kirim</th>
                            <th style="width: 140px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @forelse($paginatedStudents as $index => $student)
                            <tr>
                                <td class="text-center text-muted ps-3.5">
                                    {{ $paginatedStudents->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle shadow-xs" width="34" height="34" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $student->name }}</div>
                                            <div class="text-muted fs-xxs">NISN: <code class="text-mint">{{ $student->nisn }}</code> &bull; {{ $student->gender_label }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php $currentCls = $student->classrooms->first(); @endphp
                                    @if($currentCls)
                                        <span class="badge bg-light text-dark border px-2 py-0.5 rounded fs-xxs fw-semibold">
                                            {{ $currentCls->name }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-xxs">- Belum Rombel -</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($student->ims_status === 'submitted')
                                        <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill fs-xxs fw-bold">
                                            <i class="bi bi-check-circle me-1"></i> Terkirim
                                        </span>
                                    @elseif($student->ims_status === 'draft')
                                        <span class="badge bg-warning-subtle text-warning-emphasis px-2.5 py-1 rounded-pill fs-xxs fw-bold">
                                            <i class="bi bi-hourglass-split me-1"></i> Draft
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill fs-xxs fw-semibold">
                                            Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->ims_submitted_at)
                                        <div class="text-dark fs-xxs fw-semibold">
                                            {{ $student->ims_submitted_at->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    @else
                                        <span class="text-muted fs-xxs">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3.5">
                                    @if($student->ims_status === 'submitted' && $student->current_assessment)
                                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fs-xs fw-bold shadow-xs">
                                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Hasil
                                        </a>
                                    @elseif($student->ims_status === 'draft' && $student->current_assessment)
                                        <a href="{{ route('panel.ims.show', $student->current_assessment->id) }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-1.5 fs-xs fw-bold">
                                            Draft
                                        </a>
                                    @else
                                        <span class="text-muted fs-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                    Tidak ada data murid pada rombel ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($paginatedStudents->hasPages())
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
                {{ $paginatedStudents->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
