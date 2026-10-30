@extends('layouts.panel')

@section('title', 'Data Guru BK')

@section('content')
<x-page-header title="Data Guru BK"
               subtitle="Kelola data tenaga konseling sekolah dan akun login Guru BK.">
    <x-slot:actions>
        <a href="{{ route('panel.guru-bk.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fw-bold shadow-xs fs-xs">
            <i class="bi bi-person-plus me-1"></i> Tambah Guru BK Baru
        </a>
    </x-slot:actions>
</x-page-header>

<!-- Search Card -->
<div class="card card-app mb-3 border-0 shadow-sm rounded-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.guru-bk.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 fs-xs" placeholder="Cari nama atau NIP Guru BK..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs">Cari</button>
            </div>
            @if($search)
                <div class="col-6 col-md-2">
                    <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-sm btn-light border rounded-pill w-100 fs-xs">Reset</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
<!-- ========================================== -->
<div class="d-md-none mb-3">
    @forelse($counselors as $counselor)
        <div class="mobile-card mb-3">
            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                <img src="{{ $counselor->photo_url }}" alt="{{ $counselor->name }}" class="rounded-circle shadow-xs flex-shrink-0" width="52" height="52" style="object-fit: cover;">
                <div class="overflow-hidden flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <h3 class="card-name mb-0 text-truncate">{{ $counselor->full_name_with_title }}</h3>
                        <x-badge-status :status="$counselor->status" class="badge-pill-custom flex-shrink-0" />
                    </div>
                    <div class="card-sub">
                        NIP / NUPTK: <code class="text-mint fw-bold px-1.5 py-0.5 rounded bg-light">{{ $counselor->nip_nuptk ?? '-' }}</code>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge {{ $counselor->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} badge-pill-custom">
                    {{ $counselor->gender_label }}
                </span>

                @if($counselor->user)
                    <span class="badge bg-teal-subtle text-mint badge-pill-custom">
                        <i class="bi bi-person-check me-1"></i>{{ $counselor->user->username }}
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary badge-pill-custom">Belum Terhubung</span>
                @endif
            </div>

            <div class="card-info-box mb-3">
                <div class="d-flex align-items-center justify-content-between py-0.5">
                    <span class="text-muted"><i class="bi bi-envelope me-1.5 text-mint"></i>Email</span>
                    <span class="fw-semibold text-dark text-truncate ms-2">{{ $counselor->email ?? 'Tidak ada' }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 mt-2 border-top">
                    <span class="text-muted"><i class="bi bi-telephone me-1.5 text-mint"></i>No. Telepon</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-dark">{{ $counselor->phone ?? '-' }}</span>
                        @if($counselor->phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $counselor->phone)) }}" target="_blank" class="badge bg-success text-white text-decoration-none rounded-pill px-2.5 py-1">
                                <i class="bi bi-whatsapp"></i> WA
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('panel.guru-bk.edit', $counselor->id) }}" class="btn btn-outline-primary btn-card-action flex-grow-1">
                    <i class="bi bi-pencil me-1"></i> Ubah Data
                </a>
                <form action="{{ route('panel.guru-bk.destroy', $counselor->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus data Guru BK {{ $counselor->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border text-danger btn-card-icon" title="Hapus">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="mobile-card text-center py-5 px-3 mb-3">
            <x-empty-state icon="bi-person-badge" title="Belum Ada Guru BK" description="Tambahkan Guru BK pertama Anda untuk ditugaskan membina rombel belajar.">
                <x-slot:action>
                    <a href="{{ route('panel.guru-bk.create') }}" class="btn btn-mint text-white btn-card-action shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Tambah Guru BK
                    </a>
                </x-slot:action>
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
                        <th class="ps-4">Guru BK</th>
                        <th>NIP / NUPTK</th>
                        <th>L/P</th>
                        <th>Kontak</th>
                        <th>Akun Login</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($counselors as $counselor)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $counselor->photo_url }}" alt="{{ $counselor->name }}" class="rounded-circle shadow-xs" width="36" height="36" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $counselor->full_name_with_title }}</div>
                                        <div class="text-muted smaller">{{ $counselor->email ?? 'Tidak ada email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-mint fw-bold">{{ $counselor->nip_nuptk ?? '-' }}</code></td>
                            <td>
                                <span class="badge {{ $counselor->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill">
                                    {{ $counselor->gender_label }}
                                </span>
                            </td>
                            <td>
                                @if($counselor->phone)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $counselor->phone)) }}" target="_blank" class="text-decoration-none small text-success fw-semibold">
                                        <i class="bi bi-whatsapp"></i> {{ $counselor->phone }}
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($counselor->user)
                                    <span class="badge bg-teal-subtle text-mint rounded-pill px-2.5 py-1">
                                        <i class="bi bi-person-check me-1"></i> {{ $counselor->user->username }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
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
                                        <a href="{{ route('panel.guru-bk.create') }}" class="btn btn-sm btn-mint text-white rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
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
    </div>
</div>

@if($counselors->hasPages())
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
            {{ $counselors->links() }}
        </div>
    </div>
@endif
@endsection
