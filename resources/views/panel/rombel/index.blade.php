@extends('layouts.panel')

@section('title', 'Data Rombel')

@section('content')
<x-page-header title="Data Rombongan Belajar (Rombel)"
               subtitle="Kelola kelas/rombel siswa dan wali kelas. Guru BK melihat rombel yang ditugaskan kepadanya.">
    <x-slot:actions>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('panel.rombel.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Rombel Baru
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<!-- Filter Card -->
<div class="card card-app mb-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.rombel.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $academicYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                @if($academicYearId)
                    <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
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
                    <th class="ps-4">Nama Rombel</th>
                    <th>Tingkat</th>
                    <th>Jurusan / Peminatan</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th class="text-center">Jumlah Murid</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $rombel)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('panel.rombel.show', $rombel->id) }}" class="fw-bold text-slate-800 text-decoration-none">
                                {{ $rombel->name }}
                            </a>
                        </td>
                        <td>Kelas {{ $rombel->level }}</td>
                        <td>{{ $rombel->major ?? '-' }}</td>
                        <td>{{ $rombel->homeroom_teacher ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $rombel->academicYear?->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}">
                                {{ $rombel->academicYear?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-teal-subtle text-primary-teal px-2.5 py-1.5 fw-bold">
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
                                        <a href="{{ route('panel.rombel.create') }}" class="btn btn-sm btn-primary">
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

    @if($classrooms->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $classrooms->links() }}
        </div>
    @endif
</div>
@endsection
