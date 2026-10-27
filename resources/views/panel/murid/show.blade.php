@extends('layouts.panel')

@section('title', 'Detail Murid - ' . $murid->name)

@section('content')
<x-page-header title="Detail Profil Murid"
               subtitle="Informasi lengkap murid, data wali, serta riwayat rombel per tahun ajaran.">
    <x-slot:actions>
        <a href="{{ route('panel.murid.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('panel.murid.edit', $murid->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil me-1"></i> Ubah Data Murid
            </a>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="row g-4">
    <!-- Left Column: Profile Card -->
    <div class="col-lg-4">
        <div class="card card-app text-center p-4">
            <div class="mb-3 position-relative d-inline-block mx-auto">
                <img src="{{ $murid->photo_url }}" alt="{{ $murid->name }}" class="rounded-circle shadow-sm" width="110" height="110" style="object-fit: cover; border: 3px solid var(--primary-light);">
            </div>
            <h5 class="fw-bold text-slate-800 mb-1">{{ $murid->name }}</h5>
            <div class="text-muted small mb-3">NISN: <code>{{ $murid->nisn }}</code></div>

            <div class="d-flex justify-content-center gap-2 mb-4">
                <x-badge-status :status="$murid->status" />
                <span class="badge {{ $murid->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                    {{ $murid->gender_label }}
                </span>
            </div>

            <!-- Login Account Card -->
            <div class="p-3 rounded-3 bg-light border text-start">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold small text-slate-700">Akun Login Siswa</span>
                    @if($murid->user_id)
                        <span class="badge bg-success-subtle text-success">Tersedia</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary">Belum Dibuat</span>
                    @endif
                </div>
                @if($murid->user)
                    <div class="small text-muted mb-1">Username: <strong>{{ $murid->user->username }}</strong></div>
                    <div class="small text-muted mb-1">Status: <strong>{{ $murid->user->is_active ? 'Aktif' : 'Nonaktif' }}</strong></div>
                    <div class="small text-muted">Ganti Password: <strong>{{ $murid->user->must_change_password ? 'Wajib saat login' : 'Sudah diubah' }}</strong></div>
                @else
                    <p class="smaller text-muted mb-0">Akun login murid dapat digenerate secara otomatis melalui menu Manajemen Akun oleh Administrator.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Detail Information & Rombel History -->
    <div class="col-lg-8">
        <!-- Biodata -->
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-slate-800">
                    <i class="bi bi-person-lines-fill me-1 text-primary-teal"></i> Biodata Lengkap
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">NISN</div>
                        <div class="fw-bold text-slate-800">{{ $murid->nisn }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">NIS Sekolah</div>
                        <div class="fw-semibold">{{ $murid->nis ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Tempat, Tanggal Lahir</div>
                        <div class="fw-semibold">
                            {{ $murid->birth_place ?? '-' }},
                            {{ $murid->birth_date ? $murid->birth_date->isoFormat('D MMMM Y') : '-' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Agama</div>
                        <div class="fw-semibold">{{ $murid->religion ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Alamat Tinggal</div>
                        <div class="fw-semibold">{{ $murid->address ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Nama Orang Tua / Wali</div>
                        <div class="fw-semibold">{{ $murid->guardian_name ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">No. HP / WhatsApp Wali</div>
                        <div class="fw-semibold">
                            @if($murid->guardian_phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $murid->guardian_phone)) }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="bi bi-whatsapp me-1"></i> {{ $murid->guardian_phone }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rombel History -->
        <div class="card card-app">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-slate-800">
                    <i class="bi bi-clock-history me-1 text-primary-teal"></i> Riwayat Rombel / Kelas per Tahun Ajaran
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tahun Ajaran</th>
                            <th>Rombel / Kelas</th>
                            <th>Tingkat</th>
                            <th>Wali Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($murid->classrooms as $cr)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge {{ $cr->academicYear?->is_active ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}">
                                        {{ $cr->academicYear?->name ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-slate-800">{{ $cr->name }}</span>
                                </td>
                                <td>Kelas {{ $cr->level }}</td>
                                <td>{{ $cr->homeroom_teacher ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada riwayat rombel yang tercatat untuk murid ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
