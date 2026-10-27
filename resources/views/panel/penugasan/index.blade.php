@extends('layouts.panel')

@section('title', 'Penugasan Guru BK')

@section('content')
<x-page-header title="Penugasan Guru BK ke Rombel"
               subtitle="Petakan guru BK pembina pada rombel per semester. Penugasan inilah yang menjadi dasar pembatasan data murid bagi Guru BK.">
    <x-slot:actions>
        @if($isAdmin)
            <a href="{{ route('panel.penugasan.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-diagram-3 me-1"></i> Tugaskan Guru BK
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<!-- Filter Semester -->
<div class="card card-app mb-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.penugasan.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Pilih Semester Penugasan --</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->full_name }} {{ $sem->is_active ? '(Aktif Saat Ini)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($semesterId)
                <div class="col-md-2">
                    <a href="{{ route('panel.penugasan.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- Tampilan Ringkas: Guru BK -> Rombel Binaan -> Jumlah Murid -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h6 class="fw-bold text-slate-800 mb-3">
            <i class="bi bi-person-lines-fill me-1 text-primary-teal"></i>
            Ringkasan Penugasan: Guru BK & Rombel Binaan
        </h6>
    </div>
    @forelse($counselorsSummary as $counselor)
        <div class="col-md-6 col-xl-4">
            <div class="card card-app h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $counselor->photo_url }}" alt="{{ $counselor->name }}" class="rounded-circle shadow-sm" width="46" height="46" style="object-fit: cover;">
                        <div>
                            <h6 class="fw-bold text-slate-800 mb-0">{{ $counselor->full_name_with_title }}</h6>
                            <div class="text-muted smaller">NIP: {{ $counselor->nip_nuptk ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold">Rombel Binaan:</span>
                            <span class="badge bg-teal-subtle text-primary-teal">{{ $counselor->assignments->count() }} Rombel</span>
                        </div>
                        @if($counselor->assignments->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @foreach($counselor->assignments as $asg)
                                    <span class="badge bg-light text-dark border p-1.5 small" title="Rombel {{ $asg->classroom->name }}">
                                        {{ $asg->classroom->name }} ({{ $asg->classroom->students->count() }} siswa)
                                    </span>
                                @endforeach
                            </div>
                            @php
                                $totalMuridBinaan = $counselor->assignments->sum(fn ($a) => $a->classroom->students->count());
                            @endphp
                            <div class="small text-muted text-end">
                                Total Murid Dibina: <strong class="text-dark">{{ $totalMuridBinaan }}</strong>
                            </div>
                        @else
                            <div class="text-muted smaller fst-italic">Belum ada rombel yang ditugaskan pada semester ini.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-muted">Belum ada data penugasan untuk semester ini.</div>
        </div>
    @endforelse
</div>

<!-- Detailed Table of Assignments -->
<div class="card card-app">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="fw-bold mb-0 text-slate-800">
            <i class="bi bi-list-task me-1 text-primary-teal"></i> Detail Daftar Penugasan
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Guru BK</th>
                    <th>Rombel / Kelas</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran & Semester</th>
                    <th>Catatan Penugasan</th>
                    @if($isAdmin)
                        <th class="text-end pe-4">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $assignment->counselor->photo_url }}" alt="{{ $assignment->counselor->name }}" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-slate-800">{{ $assignment->counselor->full_name_with_title }}</div>
                                    <div class="text-muted smaller">{{ $assignment->counselor->nip_nuptk ? 'NIP: ' . $assignment->counselor->nip_nuptk : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('panel.rombel.show', $assignment->classroom_id) }}" class="fw-bold text-slate-800 text-decoration-none">
                                {{ $assignment->classroom->name }}
                            </a>
                        </td>
                        <td>Kelas {{ $assignment->classroom->level }}</td>
                        <td>
                            <span class="badge {{ $assignment->semester->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}">
                                {{ $assignment->semester->full_name }}
                            </span>
                        </td>
                        <td>{{ $assignment->notes ?? '-' }}</td>
                        @if($isAdmin)
                            <td class="text-end pe-4">
                                <form action="{{ route('panel.penugasan.destroy', $assignment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penugasan {{ $assignment->counselor->name }} di rombel {{ $assignment->classroom->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Penugasan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="py-5 text-center">
                            <x-empty-state icon="bi-diagram-3" title="Belum Ada Penugasan" description="Belum ada penugasan Guru BK ke rombel untuk semester ini.">
                                @if($isAdmin)
                                    <x-slot:action>
                                        <a href="{{ route('panel.penugasan.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-lg me-1"></i> Buat Penugasan Baru
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

    @if($assignments->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection
