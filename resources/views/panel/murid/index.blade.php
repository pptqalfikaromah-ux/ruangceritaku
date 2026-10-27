@extends('layouts.panel')

@section('title', 'Data Murid')

@section('content')
<x-page-header title="Data Murid"
               subtitle="Daftar peserta didik dan riwayat rombel. Guru BK hanya dapat mengakses murid binaannya.">
    <x-slot:actions>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('panel.murid.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalImportStudent">
                <i class="bi bi-file-earmark-arrow-up me-1"></i> Import CSV
            </button>
            <a href="{{ route('panel.murid.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus me-1"></i> Tambah Murid
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<!-- Failed Rows Alert if Import had issues -->
@if(session('import_failed_rows'))
    <div class="card border-warning mb-4">
        <div class="card-header bg-warning-subtle text-warning-emphasis fw-bold">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Laporan Baris Gagal Diimpor:
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
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
<div class="card card-app mb-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.murid.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama / NISN / NIS..." value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="classroom_id" class="form-select form-select-sm">
                    <option value="">-- Semua Rombel --</option>
                    @foreach($classrooms as $cr)
                        <option value="{{ $cr->id }}" {{ ($filters['classroom_id'] ?? '') == $cr->id ? 'selected' : '' }}>
                            {{ $cr->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="gender" class="form-select form-select-sm">
                    <option value="">-- Semua Gender --</option>
                    <option value="L" {{ ($filters['gender'] ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ ($filters['gender'] ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ ($filters['status'] ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ ($filters['status'] ?? '') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ ($filters['status'] ?? '') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="nonaktif" {{ ($filters['status'] ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary w-100" title="Filter"><i class="bi bi-funnel"></i></button>
                @if(array_filter($filters))
                    <a href="{{ route('panel.murid.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card card-app">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
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
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                <div>
                                    <a href="{{ route('panel.murid.show', $student->id) }}" class="fw-bold text-slate-800 text-decoration-none">
                                        {{ $student->name }}
                                    </a>
                                    @if($student->user_id)
                                        <span class="badge bg-teal-subtle text-primary-teal smaller d-block" style="width: fit-content; font-size: 0.65rem;">
                                            <i class="bi bi-person-check"></i> Punya Akun
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><code>{{ $student->nisn }}</code></div>
                            @if($student->nis)
                                <div class="text-muted smaller">NIS: {{ $student->nis }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $cr = $student->current_classroom;
                            @endphp
                            @if($cr)
                                <span class="badge bg-light text-dark border fw-semibold">
                                    {{ $cr->name }}
                                </span>
                            @else
                                <span class="text-muted small"><em>Belum ada</em></span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $student->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                                {{ $student->gender_label }}
                            </span>
                        </td>
                        <td>
                            @if($student->guardian_phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $student->guardian_phone)) }}" target="_blank" class="text-decoration-none small text-success">
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
                                        <a href="{{ route('panel.murid.create') }}" class="btn btn-sm btn-primary">
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

    @if($students->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $students->links() }}
        </div>
    @endif
</div>

<!-- Modal Import CSV -->
@if(auth()->user()->isAdmin())
    <div class="modal fade" id="modalImportStudent" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.murid.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Import Data Murid dari CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold small text-slate-800">Unduh Format Template</div>
                                    <div class="text-muted smaller">Gunakan template CSV standar agar format data sesuai.</div>
                                </div>
                                <a href="{{ route('panel.murid.template') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                            </div>
                        </div>

                        <x-form-select name="classroom_id" label="Masukkan Langsung ke Rombel (Opsional)" help="Jika dipilih, murid yang diimpor akan langsung dimasukkan ke rombel ini di tahun ajaran aktif.">
                            <option value="">-- Tanpa Rombel (Bisa Ditugaskan Nanti) --</option>
                            @foreach($classrooms as $cr)
                                <option value="{{ $cr->id }}">{{ $cr->name }} (Kelas {{ $cr->level }})</option>
                            @endforeach
                        </x-form-select>

                        <div class="mb-3">
                            <label for="file" class="form-label fw-semibold small text-slate-700">Pilih File CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" class="form-control" accept=".csv,.txt" required>
                            <div class="form-text small text-muted">Format file .csv dengan encoding UTF-8, maksimal 5MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Mulai Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
