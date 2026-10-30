@extends('layouts.panel')

@section('title', 'Master Data Instrumen Masalah Siswa (IMS)')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Bank Instrumen</span>
                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 fs-xs fw-medium rounded-pill">{{ $totalStatements }} Butir Standar BK</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Master Instrumen Masalah Siswa (IMS)</h1>
            <p class="text-muted small mb-0">Pengelolaan bank instrumen asesmen masalah konseling berbasis {{ $serviceTypes->count() }} Layanan & 4 Bidang Bimbingan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-people me-1"></i> Monitoring Murid
            </a>
            <a href="{{ route('panel.ims.rekap') }}" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                <i class="bi bi-bar-chart-line me-1"></i> Rekapitulasi Analisis
            </a>
        </div>
    </div>

    <!-- Global Feature Toggle Banner (Aktif / Nonaktifkan IMS untuk Murid) -->
    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid {{ $isImsGloballyEnabled ? '#0d9488' : '#f59e0b' }} !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: {{ $isImsGloballyEnabled ? '#0d9488' : '#d97706' }};">
                    <i class="bi {{ $isImsGloballyEnabled ? 'bi-toggle-on fs-3' : 'bi-toggle-off fs-3' }}"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold mb-0 text-dark">Status Akses Fitur IMS untuk Siswa:</h6>
                        @if($isImsGloballyEnabled)
                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                <i class="bi bi-check-circle-fill me-1"></i> AKTIF (DITAMPILKAN DI AKUN SISWA)
                            </span>
                        @else
                            <span class="badge bg-secondary text-white px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                <i class="bi bi-slash-circle-fill me-1"></i> NONAKTIF (DISEMBUNYIKAN DARI AKUN SISWA)
                            </span>
                        @endif
                    </div>
                    <p class="text-muted fs-xs mb-0 mt-1">
                        @if($isImsGloballyEnabled)
                            Fitur IMS sedang aktif. Menu "Instrumen Masalah (IMS)" dan asesmen butir masalah tampil di akun seluruh siswa.
                        @else
                            Fitur IMS sedang dinonaktifkan. Menu dan lembar instrumen IMS otomatis disembunyikan dari seluruh akun siswa.
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <form action="{{ route('panel.ims.toggle-status') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin {{ $isImsGloballyEnabled ? 'MENONAKTIFKAN' : 'MENGAKTIFKAN' }} fitur IMS untuk akun murid?')">
                    @csrf
                    @if($isImsGloballyEnabled)
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-xs">
                            <i class="bi bi-power me-1"></i> Nonaktifkan Fitur IMS
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-xs text-white">
                            <i class="bi bi-check-lg me-1"></i> Aktifkan Fitur IMS
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Total Butir Soal</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalStatements }}</h3>
                        <span class="text-muted fs-xs">{{ $serviceTypes->count() }} Layanan × 4 Bidang Bimbingan</span>
                    </div>
                    <div class="p-3 bg-emerald-subtle rounded-3 text-emerald">
                        <i class="bi bi-collection fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Butir Aktif Digunakan</span>
                        <h3 class="fw-bold text-emerald mt-1 mb-0">{{ $activeStatements }}</h3>
                        <span class="text-muted fs-xs">Ditampilkan pada angket murid</span>
                    </div>
                    <div class="p-3 bg-success-subtle rounded-3 text-success">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium">Komposisi Butir</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">5 Soal / Sel</h4>
                        <span class="text-muted fs-xs">Proporsional merata di setiap layanan</span>
                    </div>
                    <div class="p-3 bg-info-subtle rounded-3 text-info">
                        <i class="bi bi-grid-3x3 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.ims.master') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari isi pernyataan atau nomor butir...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="service_type_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Layanan ({{ $serviceTypes->count() }}) --</option>
                        @foreach($serviceTypes as $service)
                            <option value="{{ $service->id }}" {{ request('service_type_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="guidance_field_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Bidang (4) --</option>
                        @foreach($guidanceFields as $field)
                            <option value="{{ $field->id }}" {{ request('guidance_field_id') == $field->id ? 'selected' : '' }}>
                                Bidang {{ $field->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary bg-emerald border-0 w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'service_type_id', 'guidance_field_id', 'is_active']))
                        <a href="{{ route('panel.ims.master') }}" class="btn btn-sm btn-light border" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table & List Master Butir -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark">Daftar Butir Instrumen Masalah</span>
            <span class="badge bg-light text-muted border">Menampilkan {{ $statements->firstItem() ?? 0 }}-{{ $statements->lastItem() ?? 0 }} dari {{ $statements->total() }} butir</span>
        </div>

        {{-- TAMPILAN MOBILE (HP / Layar Kecil): Kalimat Memanjang ke Samping Penuh --}}
        <div class="d-md-none list-group list-group-flush">
            @forelse($statements as $item)
                @php
                    $fieldColors = [
                        'PRIBADI' => 'bg-primary-subtle text-primary border border-primary-subtle',
                        'SOSIAL' => 'bg-info-subtle text-info border border-info-subtle',
                        'BELAJAR' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                        'KARIR' => 'bg-purple-subtle text-purple border border-purple-subtle',
                    ];
                    $fieldName = strtoupper($item->guidanceField?->name ?? '');
                    $colorClass = $fieldColors[$fieldName] ?? 'bg-secondary-subtle text-secondary';
                @endphp
                <div class="list-group-item p-3 border-bottom bg-white">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div class="d-flex align-items-center flex-wrap gap-1.5">
                            <span class="badge bg-light text-dark border font-monospace fw-bold" style="font-size: 0.75rem;">
                                #{{ $item->statement_number }}
                            </span>
                            <span class="badge {{ $colorClass }}" style="font-size: 0.68rem;">
                                {{ $item->guidanceField?->name ?? '-' }}
                            </span>
                            <span class="badge bg-light text-dark border" style="font-size: 0.68rem;">
                                {{ $item->serviceType?->name ?? '-' }}
                            </span>
                            @if($item->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.68rem;">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Nonaktif</span>
                            @endif
                        </div>

                        @if(Auth::user()->isAdmin())
                            <div class="d-flex align-items-center gap-1.5 ms-auto">
                                <button type="button" class="btn btn-sm btn-light border py-1 px-2.5 fs-xs text-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $item->id }}"
                                        title="Edit Teks">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('panel.ims.toggle', $item) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} py-1 px-2 fs-xs"
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $item->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Kalimat Butir Memanjang Penuh ke Samping di Layar HP -->
                    <div class="text-dark fw-medium fs-sm lh-base" style="font-size: 0.88rem !important; line-height: 1.55;">
                        {{ $item->statement_text }}
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    Tidak ada butir instrumen yang ditemukan.
                </div>
            @endforelse
        </div>

        {{-- TAMPILAN DESKTOP & TABLET: Tabel Lebar Lengkap --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted">
                    <tr>
                        <th style="width: 70px;" class="text-center">No</th>
                        <th style="min-width: 280px;">Pernyataan Masalah / Kebutuhan Konseling</th>
                        <th style="width: 120px;">Bidang</th>
                        <th style="width: 180px;">Layanan BK</th>
                        <th style="width: 90px;" class="text-center">Status</th>
                        @if(Auth::user()->isAdmin())
                            <th style="width: 110px;" class="text-end">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($statements as $item)
                        <tr>
                            <td class="text-center fw-bold text-muted">
                                #{{ $item->statement_number }}
                            </td>
                            <td>
                                <div class="text-dark fw-medium lh-base">{{ $item->statement_text }}</div>
                            </td>
                            <td>
                                @php
                                    $fieldColors = [
                                        'PRIBADI' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        'SOSIAL' => 'bg-info-subtle text-info border border-info-subtle',
                                        'BELAJAR' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                        'KARIR' => 'bg-purple-subtle text-purple border border-purple-subtle',
                                    ];
                                    $fieldName = strtoupper($item->guidanceField?->name ?? '');
                                    $colorClass = $fieldColors[$fieldName] ?? 'bg-secondary-subtle text-secondary';
                                @endphp
                                <span class="badge {{ $colorClass }} px-2 py-1 rounded-pill fs-xs">
                                    {{ $item->guidanceField?->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-2 fs-xs">
                                    {{ $item->serviceType?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fs-xs">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill fs-xs">Nonaktif</span>
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 fs-xs" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $item->id }}"
                                                title="Edit Teks">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('panel.ims.toggle', $item) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} py-0 px-2 fs-xs"
                                                    title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="bi {{ $item->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? '6' : '5' }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ada data butir instrumen yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL EDIT BUTIR (Bisa dipanggil dari HP maupun Desktop) --}}
        @if(Auth::user()->isAdmin())
            @foreach($statements as $item)
                <div class="modal fade text-start" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <form method="POST" action="{{ route('panel.ims.update', $item) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fs-sm fw-bold">Edit Butir #{{ $item->statement_number }} ({{ $item->serviceType?->name ?? 'Layanan' }})</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body space-y-3">
                                    <div class="mb-3">
                                        <label class="form-label fs-xs text-muted fw-semibold">Pernyataan Masalah</label>
                                        <textarea name="statement_text" class="form-control form-control-sm" rows="3" required>{{ $item->statement_text }}</textarea>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeSwitch{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fs-xs text-dark" for="activeSwitch{{ $item->id }}">Status Aktif</label>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2">
                                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm bg-emerald border-0">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if($statements->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $statements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
