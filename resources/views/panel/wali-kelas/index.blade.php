@extends('layouts.panel')

@section('title', 'Data Wali Kelas')
@section('breadcrumb')
    <span class="current">Data Wali Kelas</span>
@endsection

@section('content')
<div class="card card-app mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark fs-6 fs-md-5">
                <i class="bi bi-mortarboard-fill text-warning me-1.5"></i> Data Pendidik & Wali Kelas
            </h5>
            <div class="text-muted fs-xxs fs-md-xs mt-0.5">Kelola identitas wali kelas serta akun login untuk akses pemantauan rombel.</div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('panel.penugasan-wali-kelas.index') }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs fs-xs">
                <i class="bi bi-diagram-3 me-1"></i> Penugasan Rombel
            </a>
            <a href="{{ route('panel.wali-kelas.create') }}" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-xs fs-xs">
                <i class="bi bi-plus-circle me-1"></i> Tambah Wali Kelas
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-body p-3 border-bottom bg-light">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 fs-xs" placeholder="Cari nama, NIP, atau username..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-mint text-white btn-sm w-100 fw-bold rounded-pill shadow-xs fs-xs">Filter</button>
            </div>
            @if(request()->anyFilled(['search', 'status']))
                <div class="col-12 col-md-2">
                    <a href="{{ route('panel.wali-kelas.index') }}" class="btn btn-light border btn-sm w-100 rounded-pill fs-xs">Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
<!-- ========================================== -->
<div class="d-md-none space-y-3 mb-3">
    @forelse($teachers as $index => $teacher)
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <div class="fw-extrabold text-dark fs-6">{{ $teacher->name }}</div>
                        <span class="text-muted fs-xxs">NIP: {{ $teacher->nip_nuptk ?? '-' }}</span>
                    </div>
                    <span class="badge {{ $teacher->status === 'aktif' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary' }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                        {{ ucfirst($teacher->status) }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-1.5 mb-2.5">
                    <span class="badge {{ $teacher->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill fs-xxs">
                        {{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>

                    @if($assignment = $teacher->currentAssignment)
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-0.5 rounded-pill fs-xxs fw-bold">
                            <i class="bi bi-door-open me-1"></i>Kelas {{ $assignment->classroom?->name }}
                        </span>
                    @else
                        <span class="badge bg-light text-muted border px-2 py-0.5 rounded-pill fs-xxs">Belum Ditugaskan</span>
                    @endif

                    @if($teacher->user)
                        <span class="badge bg-teal-subtle text-mint px-2 py-0.5 rounded-pill fs-xxs">
                            <i class="bi bi-person me-0.5"></i>{{ $teacher->user->username }}
                        </span>
                    @endif
                </div>

                <div class="p-2 rounded-3 bg-light border text-muted fs-xxs mb-2.5 space-y-1">
                    <div><i class="bi bi-telephone me-1 text-mint"></i>Telp: <strong class="text-dark">{{ $teacher->phone ?? '-' }}</strong></div>
                    <div><i class="bi bi-envelope me-1 text-mint"></i>Email: <strong class="text-dark">{{ $teacher->email ?? '-' }}</strong></div>
                </div>

                <div class="d-flex gap-1.5">
                    <a href="{{ route('panel.wali-kelas.edit', $teacher) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1 fw-bold fs-xs py-1.5">
                        <i class="bi bi-pencil me-1"></i> Ubah Data
                    </a>
                    <form action="{{ route('panel.wali-kelas.destroy', $teacher) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus data wali kelas ini beserta akun loginnya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light border text-danger rounded-pill px-3 fw-bold fs-xs py-1.5" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card card-app border-0 shadow-sm rounded-4 text-center py-5 px-3">
            <i class="bi bi-person-x fs-2 d-block mb-2 text-muted opacity-50"></i>
            <p class="text-muted fs-xs mb-0">Belum ada data Wali Kelas. Klik <strong>Tambah Wali Kelas</strong> untuk menambahkan.</p>
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
                        <th>Nama Wali Kelas</th>
                        <th>NIP / NUPTK</th>
                        <th>Akun Login</th>
                        <th>Rombel Binaan Aktif</th>
                        <th>No. Telepon / Email</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3.5" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($teachers as $index => $teacher)
                        <tr>
                            <td class="text-center fw-bold text-muted ps-3.5">{{ $teachers->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $teacher->name }}</div>
                                <span class="badge {{ $teacher->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill fs-xxs">
                                    {{ $teacher->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="font-monospace small text-muted">{{ $teacher->nip_nuptk ?? '-' }}</td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-person me-1 text-mint"></i>{{ $teacher->user?->username ?? '-' }}</div>
                                <div class="fs-xxs text-muted">Role: Wali Kelas</div>
                            </td>
                            <td>
                                @if($assignment = $teacher->currentAssignment)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-door-open me-1"></i> Kelas {{ $assignment->classroom?->name }}
                                    </span>
                                @else
                                    <span class="text-muted fs-xs fst-italic">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                <div>{{ $teacher->phone ?? '-' }}</div>
                                <div class="fs-xxs">{{ $teacher->email ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $teacher->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary' }} rounded-pill">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-3.5">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('panel.wali-kelas.edit', $teacher) }}" class="btn btn-light border" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('panel.wali-kelas.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Hapus data wali kelas ini beserta akun loginnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light border text-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada data Wali Kelas. Klik <strong>Tambah Wali Kelas</strong> untuk menambahkan pendidik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($teachers->hasPages())
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
            {{ $teachers->links() }}
        </div>
    </div>
@endif
@endsection
