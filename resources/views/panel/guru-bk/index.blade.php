@extends('layouts.panel')

@section('title', 'Data Guru BK')

@section('content')
<x-page-header title="Data Guru BK"
               subtitle="Kelola data tenaga konseling sekolah dan akun login Guru BK.">
    <x-slot:actions>
        <a href="{{ route('panel.guru-bk.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-person-plus me-1"></i> Tambah Guru BK Baru
        </a>
    </x-slot:actions>
</x-page-header>

<!-- Search Card -->
<div class="card card-app mb-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.guru-bk.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP Guru BK..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Cari</button>
            </div>
            @if($search)
                <div class="col-md-2">
                    <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="card card-app">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Guru BK</th>
                    <th>NIP / NUPTK</th>
                    <th>L/P</th>
                    <th>Kontak</th>
                    <th>Akun Login</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($counselors as $counselor)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $counselor->photo_url }}" alt="{{ $counselor->name }}" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-slate-800">{{ $counselor->full_name_with_title }}</div>
                                    <div class="text-muted smaller">{{ $counselor->email ?? 'Tidak ada email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $counselor->nip_nuptk ?? '-' }}</code></td>
                        <td>
                            <span class="badge {{ $counselor->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}">
                                {{ $counselor->gender_label }}
                            </span>
                        </td>
                        <td>
                            @if($counselor->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $counselor->phone)) }}" target="_blank" class="text-decoration-none small text-success">
                                    <i class="bi bi-whatsapp"></i> {{ $counselor->phone }}
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($counselor->user)
                                <span class="badge bg-teal-subtle text-primary-teal">
                                    <i class="bi bi-person-check me-1"></i> {{ $counselor->user->username }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    Belum Terhubung
                                </span>
                            @endif
                        </td>
                        <td>
                            <x-badge-status :status="$counselor->status" />
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('panel.guru-bk.edit', $counselor->id) }}" class="btn btn-light border" title="Ubah">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('panel.guru-bk.destroy', $counselor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data Guru BK {{ $counselor->name }}?')">
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
                        <td colspan="7" class="py-5 text-center">
                            <x-empty-state icon="bi-person-badge" title="Belum Ada Guru BK" description="Tambahkan Guru BK pertama Anda untuk ditugaskan membina rombel belajar.">
                                <x-slot:action>
                                    <a href="{{ route('panel.guru-bk.create') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-person-plus me-1"></i> Tambah Guru BK
                                    </a>
                                </x-slot:action>
                            </x-empty-state>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($counselors->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $counselors->links() }}
        </div>
    @endif
</div>
@endsection
