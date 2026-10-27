@extends('layouts.panel')

@section('title', 'Tahun Ajaran & Semester')

@section('content')
<x-page-header title="Tahun Ajaran & Semester"
               subtitle="Kelola tahun ajaran dan semester aktif sekolah. Hanya SATU semester yang aktif dalam satu waktu.">
    <x-slot:actions>
        @if(auth()->user()->isAdmin())
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddYear">
                <i class="bi bi-plus-lg me-1"></i> Tambah Tahun Ajaran
            </button>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="row">
    @forelse($academicYears as $year)
        <div class="col-md-6 mb-4">
            <div class="card card-app h-100 {{ $year->is_active ? 'border-primary' : '' }}">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="fw-bold mb-0 text-slate-800">{{ $year->name }}</h5>
                        @if($year->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                Tahun Ajaran Aktif
                            </span>
                        @endif
                    </div>
                    @if(auth()->user()->isAdmin())
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#modalEditYear-{{ $year->id }}">
                                        <i class="bi bi-pencil me-2 text-muted"></i> Ubah Nama
                                    </button>
                                </li>
                                @if(!$year->is_active)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('panel.tahun-ajaran.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="card-body p-4">
                    <h6 class="text-muted small fw-semibold text-uppercase mb-3">Daftar Semester</h6>
                    <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                        @foreach($year->semesters as $semester)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 {{ $semester->is_active ? 'bg-light' : '' }}">
                                <div>
                                    <span class="fw-bold text-slate-800">Semester {{ $semester->name }}</span>
                                    @if($semester->is_active)
                                        <span class="badge bg-success ms-2">
                                            <i class="bi bi-check-circle me-1"></i> Sedang Berjalan
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    @if($semester->is_active)
                                        <button class="btn btn-sm btn-outline-success disabled" disabled>
                                            <i class="bi bi-check-lg"></i> Aktif
                                        </button>
                                    @elseif(auth()->user()->isAdmin())
                                        <form action="{{ route('panel.semester.activate', $semester->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan Semester {{ $semester->name }} ({{ $year->name }})? Semester lain akan otomatis dinonaktifkan.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-play-circle me-1"></i> Jadikan Aktif
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Modal Edit Year -->
            @if(auth()->user()->isAdmin())
                <div class="modal fade" id="modalEditYear-{{ $year->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('panel.tahun-ajaran.update', $year->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Ubah Tahun Ajaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <x-form-input name="name" label="Nama Tahun Ajaran" :value="$year->name" placeholder="Contoh: 2025/2026" required help="Format: YYYY/YYYY" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="col-12">
            <div class="card card-app p-5">
                <x-empty-state icon="bi-calendar-x" title="Belum Ada Tahun Ajaran" description="Tambahkan tahun ajaran pertama untuk memulai pengoperasian sistem konseling Ruang Ceritaku.">
                    @if(auth()->user()->isAdmin())
                        <x-slot:action>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddYear">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Tahun Ajaran Sekarang
                            </button>
                        </x-slot:action>
                    @endif
                </x-empty-state>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal Add Year -->
@if(auth()->user()->isAdmin())
    <div class="modal fade" id="modalAddYear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('panel.tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Tahun Ajaran Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-form-input name="name" label="Nama Tahun Ajaran" placeholder="Contoh: 2025/2026" required help="Format harus 4 digit tahun / 4 digit tahun (contoh: 2025/2026). Sistem akan otomatis membuat Semester Ganjil & Genap." />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah & Buat Semester</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
