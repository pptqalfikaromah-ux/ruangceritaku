@extends('layouts.panel')

@section('title', 'Data Wali Kelas')
@section('breadcrumb')
    <span class="current">Data Wali Kelas</span>
@endsection

@section('content')
<div class="card card-app">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-mortarboard-fill text-warning me-1.5"></i> Data Pendidik & Wali Kelas
            </h5>
            <div class="text-muted fs-xs">Kelola identitas wali kelas serta akun login untuk akses pemantauan rombel.</div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('panel.penugasan-wali-kelas.index') }}" class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3 py-2 fw-bold">
                <i class="bi bi-diagram-3 me-1"></i> Penugasan Rombel
            </a>
            <a href="{{ route('panel.wali-kelas.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Wali Kelas
            </a>
        </div>
    </div>

    <div class="card-body p-3 border-bottom bg-light">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, NIP, atau username..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">Filter</button>
            </div>
            @if(request()->anyFilled(['search', 'status']))
                <div class="col-md-2">
                    <a href="{{ route('panel.wali-kelas.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Wali Kelas</th>
                        <th>NIP / NUPTK</th>
                        <th>Akun Login</th>
                        <th>Rombel Binaan Aktif</th>
                        <th>No. Telepon / Email</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $index => $teacher)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $teachers->firstItem() + $index }}</td>
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
                            <td class="text-center">
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

    @if($teachers->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $teachers->links() }}
        </div>
    @endif
</div>
@endsection
