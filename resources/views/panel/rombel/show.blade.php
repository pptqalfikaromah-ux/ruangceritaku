@extends('layouts.panel')

@section('title', 'Detail Rombel ' . $rombel->name)

@section('content')
<x-page-header title="Detail Rombel {{ $rombel->name }}"
               subtitle="Daftar murid dan profil rombongan belajar tahun ajaran {{ $rombel->academicYear?->name }}.">
    <x-slot:actions>
        <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Rombel
        </a>
        @if(auth()->user()->isAdmin())
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddStudent">
                <i class="bi bi-person-plus me-1"></i> Masukkan Murid ke Rombel
            </button>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="row g-4 mb-4">
    <!-- Info Card -->
    <div class="col-lg-4">
        <div class="card card-app h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-slate-800">
                    <i class="bi bi-info-circle me-1 text-primary-teal"></i> Informasi Rombel
                </h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small ps-0" style="width: 40%;">Nama Rombel</td>
                        <td class="fw-bold text-slate-800">{{ $rombel->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Tingkat</td>
                        <td class="fw-semibold">Kelas {{ $rombel->level }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Jurusan</td>
                        <td>{{ $rombel->major ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Wali Kelas</td>
                        <td class="fw-semibold">{{ $rombel->homeroom_teacher ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Tahun Ajaran</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $rombel->academicYear?->name ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Guru BK Pembina</td>
                        <td>
                            @if($rombel->currentAssignment)
                                <span class="badge bg-teal-subtle text-primary-teal">
                                    {{ $rombel->currentAssignment->counselor->name }}
                                </span>
                            @else
                                <span class="text-warning small"><i class="bi bi-exclamation-circle"></i> Belum ditugaskan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted small ps-0">Total Murid</td>
                        <td>
                            <span class="badge bg-primary px-2.5 py-1.5">{{ $students->total() }} Murid</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Student List Card -->
    <div class="col-lg-8">
        <div class="card card-app h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-slate-800">
                    <i class="bi bi-people me-1 text-primary-teal"></i> Anggota Murid di Rombel Ini
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Murid</th>
                            <th>NISN</th>
                            <th>L/P</th>
                            <th>Status</th>
                            @if(auth()->user()->isAdmin())
                                <th class="text-end pe-4">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle" width="30" height="30" style="object-fit: cover;">
                                        <div>
                                            <a href="{{ route('panel.murid.show', $student->id) }}" class="fw-semibold text-slate-800 text-decoration-none">
                                                {{ $student->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td><code>{{ $student->nisn }}</code></td>
                                <td>
                                    <span class="badge {{ $student->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                                        {{ $student->gender }}
                                    </span>
                                </td>
                                <td>
                                    <x-badge-status :status="$student->status" />
                                </td>
                                @if(auth()->user()->isAdmin())
                                    <td class="text-end pe-4">
                                        <form action="{{ route('panel.rombel.student.remove', [$rombel->id, $student->id]) }}" method="POST" onsubmit="return confirm('Keluarkan murid {{ $student->name }} dari rombel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Keluarkan dari rombel">
                                                <i class="bi bi-person-dash"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="py-5 text-center">
                                    <x-empty-state icon="bi-people" title="Belum Ada Murid" description="Belum ada murid yang dimasukkan ke dalam rombel ini.">
                                        @if(auth()->user()->isAdmin())
                                            <x-slot:action>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddStudent">
                                                    <i class="bi bi-person-plus me-1"></i> Masukkan Murid Sekarang
                                                </button>
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
    </div>
</div>

<!-- Modal Add Student to Rombel -->
@if(auth()->user()->isAdmin())
    <div class="modal fade" id="modalAddStudent" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.rombel.student.add', $rombel->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Masukkan Murid ke Rombel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-form-select name="student_id" label="Pilih Murid (Belum Memiliki Rombel di Tahun Ajaran Ini)" required>
                            <option value="">-- Pilih Murid --</option>
                            @foreach($availableStudents as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} (NISN: {{ $st->nisn }}) - {{ $st->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</option>
                            @endforeach
                        </x-form-select>
                        @if($availableStudents->isEmpty())
                            <div class="alert alert-warning small mb-0">
                                Seluruh murid aktif telah terdaftar pada rombel di tahun ajaran ini, atau belum ada murid aktif yang diinput.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>
                            Masukkan Murid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
