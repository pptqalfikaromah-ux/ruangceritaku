@extends('layouts.panel')

@section('title', 'Data Rombel')

@section('content')
<x-page-header title="Data Rombongan Belajar (Rombel)"
               subtitle="Kelola kelas/rombel siswa dan wali kelas. Guru BK melihat rombel yang ditugaskan kepadanya.">
    <x-slot:actions>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('panel.rombel.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fw-bold shadow-xs fs-xs">
                <i class="bi bi-plus-lg me-1"></i> Tambah Rombel Baru
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<!-- Filter Card -->
<div class="card card-app mb-3 border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.rombel.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-8 col-md-5">
                <select name="academic_year_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-2">
                @if($academicYearId)
                    <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-light border rounded-pill w-100 fs-xs fw-semibold">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
<!-- ========================================== -->
<div class="d-md-none space-y-3 mb-3">
    @forelse($classrooms as $rombel)
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <a href="{{ route('panel.rombel.show', $rombel->id) }}" class="fw-extrabold text-dark fs-6 text-decoration-none d-block">
                            Kelas {{ $rombel->name }}
                        </a>
                        <span class="text-muted fs-xxs">Tingkat {{ $rombel->level }} &bull; {{ $rombel->major ?? 'Reguler' }}</span>
                    </div>
                    <span class="badge bg-teal-subtle text-mint px-2.5 py-1 rounded-pill fs-xxs fw-bold">
                        <i class="bi bi-people-fill me-1"></i>{{ $rombel->students_count }} Murid
                    </span>
                </div>

                <div class="mb-2.5 space-y-1">
                    <div class="text-muted fs-xs d-flex align-items-center gap-1.5">
                        <i class="bi bi-person-video3 text-mint"></i>
                        <span>Wali Kelas: <strong class="text-dark">{{ $rombel->homeroom_teacher ?? 'Belum Ditugaskan' }}</strong></span>
                    </div>
                    <div class="text-muted fs-xs d-flex align-items-center gap-1.5">
                        <i class="bi bi-calendar3 text-muted"></i>
                        <span>Tahun Ajaran: 
                            <span class="badge {{ $rombel->academicYear?->is_active ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }} rounded-pill fs-xxs">
                                {{ $rombel->academicYear?->name ?? '-' }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="d-flex gap-1.5 pt-1">
                    <a href="{{ route('panel.rombel.show', $rombel->id) }}" class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1 fw-bold fs-xs py-1.5">
                        <i class="bi bi-eye me-1"></i> Lihat Murid
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('panel.rombel.edit', $rombel->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold fs-xs py-1.5 text-dark" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('panel.rombel.destroy', $rombel->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus rombel {{ $rombel->name }}?')">
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
            <x-empty-state icon="bi-door-closed" title="Belum Ada Data Rombel" description="Data rombel belajar belum tersedia untuk filter yang dipilih.">
                @if(auth()->user()->isAdmin())
                    <x-slot:action>
                        <a href="{{ route('panel.rombel.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Rombel
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
                        <th class="ps-4">Nama Rombel</th>
                        <th>Tingkat</th>
                        <th>Jurusan / Peminatan</th>
                        <th>Wali Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-center">Jumlah Murid</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($classrooms as $rombel)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('panel.rombel.show', $rombel->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $rombel->name }}
                                </a>
                            </td>
                            <td>Kelas {{ $rombel->level }}</td>
                            <td>{{ $rombel->major ?? '-' }}</td>
                            <td>{{ $rombel->homeroom_teacher ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $rombel->academicYear?->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }} rounded-pill">
                                    {{ $rombel->academicYear?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fw-bold rounded-pill">
                                    {{ $rombel->students_count }} Murid
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('panel.rombel.show', $rombel->id) }}" class="btn btn-light border" title="Lihat Murid">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('panel.rombel.edit', $rombel->id) }}" class="btn btn-light border" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('panel.rombel.destroy', $rombel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rombel {{ $rombel->name }}?')">
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
                                <x-empty-state icon="bi-door-closed" title="Belum Ada Data Rombel" description="Data rombel belajar belum tersedia untuk filter yang dipilih.">
                                    @if(auth()->user()->isAdmin())
                                        <x-slot:action>
                                            <a href="{{ route('panel.rombel.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
                                                <i class="bi bi-plus-lg me-1"></i> Tambah Rombel
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

@if($classrooms->hasPages())
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
            {{ $classrooms->links() }}
        </div>
    </div>
@endif
@endsection
