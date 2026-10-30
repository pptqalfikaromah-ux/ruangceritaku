@extends('layouts.panel')

@section('title', 'Data Murid')

@section('content')
<x-page-header title="Data Murid"
               subtitle="Daftar peserta didik dan riwayat rombel. Guru BK hanya dapat mengakses murid binaannya.">
    <x-slot:actions>
        @if(auth()->user()->isAdmin())
            <div class="d-flex flex-wrap gap-1.5">
                <a href="{{ route('panel.murid.export', request()->query()) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold shadow-xs fs-xs">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
                </a>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-bold shadow-xs fs-xs" data-bs-toggle="modal" data-bs-target="#modalImportStudent">
                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Import CSV
                </button>
                <a href="{{ route('panel.murid.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fw-bold shadow-xs fs-xs">
                    <i class="bi bi-person-plus me-1"></i> Tambah Murid
                </a>
            </div>
        @endif
    </x-slot:actions>
</x-page-header>

<!-- Failed Rows Alert if Import had issues -->
@if(session('import_failed_rows'))
    <div class="card border-warning mb-3 rounded-4 shadow-sm overflow-hidden">
        <div class="card-header bg-warning-subtle text-warning-emphasis fw-bold py-2.5 px-3">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporan Baris Gagal Diimpor:
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0 fs-xs">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Baris File</th>
                            <th>NISN</th>
                            <th>Keterangan Kesalahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('import_failed_rows') as $failed)
                            <tr>
                                <td class="ps-3">{{ $failed['row'] }}</td>
                                <td><code>{{ $failed['nisn'] }}</code></td>
                                <td class="text-danger">{{ $failed['error'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<!-- Search & Filter Card -->
<div class="card card-app mb-3 border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.murid.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 fs-xs" placeholder="Cari nama / NISN / NIS..." value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="classroom_id" class="form-select form-select-sm fs-xs">
                    <option value="">-- Semua Rombel --</option>
                    @foreach($classrooms as $cr)
                        <option value="{{ $cr->id }}" {{ ($filters['classroom_id'] ?? '') == $cr->id ? 'selected' : '' }}>
                            {{ $cr->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-3 col-md-2">
                <select name="gender" class="form-select form-select-sm fs-xs">
                    <option value="">-- JK --</option>
                    <option value="L" {{ ($filters['gender'] ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ ($filters['gender'] ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-3 col-md-2">
                <select name="status" class="form-select form-select-sm fs-xs">
                    <option value="">-- Status --</option>
                    <option value="aktif" {{ ($filters['status'] ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ ($filters['status'] ?? '') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ ($filters['status'] ?? '') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="nonaktif" {{ ($filters['status'] ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-flex gap-1.5">
                <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs" title="Filter"><i class="bi bi-funnel"></i></button>
                @if(array_filter($filters))
                    <a href="{{ route('panel.murid.index') }}" class="btn btn-sm btn-light border rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
<!-- ========================================== -->
<div class="d-md-none space-y-3 mb-3">
    @forelse($students as $student)
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex align-items-start gap-2.5 mb-2.5">
                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle shadow-xs flex-shrink-0" width="46" height="46" style="object-fit: cover;">
                    <div class="overflow-hidden flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                            <a href="{{ route('panel.murid.show', $student->id) }}" class="fw-extrabold text-dark text-truncate fs-sm text-decoration-none">
                                {{ $student->name }}
                            </a>
                            <x-badge-status :status="$student->status" />
                        </div>
                        <div class="text-muted fs-xxs d-flex flex-wrap align-items-center gap-1.5">
                            <span>NISN: <code class="text-mint fw-bold">{{ $student->nisn }}</code></span>
                            @if($student->nis)
                                <span>&bull; NIS: {{ $student->nis }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-1.5 mb-2.5">
                    @php $cr = $student->current_classroom; @endphp
                    @if($cr)
                        <span class="badge bg-teal-subtle text-mint px-2 py-0.5 rounded fs-xxs fw-semibold">
                            <i class="bi bi-door-open me-1"></i>Kelas {{ $cr->name }}
                        </span>
                    @else
                        <span class="badge bg-light text-muted border px-2 py-0.5 rounded fs-xxs">Belum ada kelas</span>
                    @endif

                    <span class="badge {{ $student->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} px-2 py-0.5 rounded fs-xxs fw-semibold">
                        {{ $student->gender_label }}
                    </span>

                    @if($student->user_id)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded fs-xxs fw-semibold">
                            <i class="bi bi-person-check me-1"></i>Punya Akun
                        </span>
                    @endif
                </div>

                <!-- Kontak Wali -->
                <div class="p-2 rounded-3 bg-light border text-muted fs-xxs d-flex align-items-center justify-content-between mb-2.5">
                    <span><i class="bi bi-people me-1"></i>Wali: <strong>{{ $student->guardian_name ?? 'Belum ada data' }}</strong></span>
                    @if($student->guardian_phone)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $student->guardian_phone)) }}" target="_blank" class="text-success text-decoration-none fw-bold">
                            <i class="bi bi-whatsapp me-0.5"></i>Hubungi
                        </a>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-1.5">
                    <a href="{{ route('panel.murid.show', $student->id) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1 fw-bold fs-xs py-1.5">
                        <i class="bi bi-eye me-1"></i> Detail
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('panel.murid.edit', $student->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold fs-xs py-1.5 text-dark" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('panel.murid.destroy', $student->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus data murid {{ $student->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-pill px-3 fw-bold fs-xs py-1.5" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card card-app border-0 shadow-sm rounded-4 text-center py-5 px-3">
            <x-empty-state icon="bi-people" title="Tidak Ditemukan Data Murid" description="Data murid belum tersedia atau tidak cocok dengan filter pencarian.">
                @if(auth()->user()->isAdmin())
                    <x-slot:action>
                        <a href="{{ route('panel.murid.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fw-bold shadow-xs">
                            <i class="bi bi-person-plus me-1"></i> Tambah Murid Baru
                        </a>
                    </x-slot:action>
                @endif
            </x-empty-state>
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
                        <th class="ps-4">Murid</th>
                        <th>NISN / NIS</th>
                        <th>Rombel Saat Ini</th>
                        <th>Jenis Kelamin</th>
                        <th>Kontak Wali</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle shadow-xs" width="36" height="36" style="object-fit: cover;">
                                    <div>
                                        <a href="{{ route('panel.murid.show', $student->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $student->name }}
                                        </a>
                                        @if($student->user_id)
                                            <span class="badge bg-teal-subtle text-mint smaller d-block" style="width: fit-content; font-size: 0.65rem;">
                                                <i class="bi bi-person-check"></i> Punya Akun
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><code class="text-mint fw-bold">{{ $student->nisn }}</code></div>
                                @if($student->nis)
                                    <div class="text-muted smaller">NIS: {{ $student->nis }}</div>
                                @endif
                            </td>
                            <td>
                                @php $cr = $student->current_classroom; @endphp
                                @if($cr)
                                    <span class="badge bg-light text-dark border fw-semibold">
                                        {{ $cr->name }}
                                    </span>
                                @else
                                    <span class="text-muted small"><em>Belum ada</em></span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $student->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill">
                                    {{ $student->gender_label }}
                                </span>
                            </td>
                            <td>
                                @if($student->guardian_phone)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $student->guardian_phone)) }}" target="_blank" class="text-decoration-none small text-success fw-semibold">
                                        <i class="bi bi-whatsapp"></i> {{ $student->guardian_phone }}
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                                @if($student->guardian_name)
                                    <div class="text-muted smaller">({{ $student->guardian_name }})</div>
                                @endif
                            </td>
                            <td>
                                <x-badge-status :status="$student->status" />
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('panel.murid.show', $student->id) }}" class="btn btn-light border" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('panel.murid.edit', $student->id) }}" class="btn btn-light border" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('panel.murid.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data murid {{ $student->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light border text-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <x-empty-state icon="bi-people" title="Tidak Ditemukan Data Murid" description="Data murid belum tersedia atau tidak cocok dengan filter pencarian.">
                                    @if(auth()->user()->isAdmin())
                                        <x-slot:action>
                                            <a href="{{ route('panel.murid.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fw-bold shadow-xs">
                                                <i class="bi bi-person-plus me-1"></i> Tambah Murid Baru
                                            </a>
                                        </x-slot:action>
                                    @endif
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($students->hasPages())
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
            {{ $students->links() }}
        </div>
    </div>
@endif

@if(auth()->user()->isAdmin())
    <!-- Modal Import Student -->
    <div class="modal fade" id="modalImportStudent" tabindex="-1" aria-labelledby="modalImportStudentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('panel.murid.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-dark fs-6" id="modalImportStudentLabel">
                            <i class="bi bi-file-earmark-arrow-up text-mint me-1.5"></i> Import Data Murid (CSV / Excel)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 fs-xs mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i> Unggah file template CSV data siswa. Pastikan format kolom sesuai dengan template standar.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Pilih File CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control form-control-sm" accept=".csv, .txt, .xlsx, .xls" required>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('panel.murid.export') }}" class="small text-decoration-none text-mint fw-bold">
                                <i class="bi bi-download me-1"></i> Unduh Format Contoh CSV
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-mint text-white rounded-pill px-4 fw-bold shadow-xs">
                            Unggah & Proses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
