@extends('layouts.panel')

@section('title', 'Manajemen Akun Pengguna')

@section('content')
<x-page-header title="Manajemen Akun Pengguna"
               subtitle="Kelola akun pengguna panel (Admin, Guru BK, Wali Kelas, Murid), reset password, dan buat akun murid massal.">
    <x-slot:actions>
        <div class="d-flex flex-wrap gap-2 w-100 justify-content-start justify-content-md-end">
            <button type="button" class="btn btn-sm btn-outline-success flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#modalExportCredentials">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Kredensial
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#modalGenerateStudentAccounts">
                <i class="bi bi-person-lines-fill me-1"></i> Generate Akun Massal
            </button>
            <button type="button" class="btn btn-sm btn-primary flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#modalAddUser">
                <i class="bi bi-person-plus me-1"></i> Tambah Akun
            </button>
        </div>
    </x-slot:actions>
</x-page-header>

<!-- Filter & Search Card -->
<div class="card card-app mb-4">
    <div class="card-body p-3">
        <form action="{{ route('panel.akun.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" {{ ($role ?? '') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="guru_bk" {{ ($role ?? '') === 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                    <option value="wali_kelas" {{ ($role ?? '') === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    <option value="murid" {{ ($role ?? '') === 'murid' ? 'selected' : '' }}>Murid</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if($search || $role || $status !== null)
                    <a href="{{ route('panel.akun.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Account List Card -->
<div class="card card-app">
    <!-- Desktop View: Table -->
    <div class="d-none d-md-block table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Wajib Ganti Password</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $roleBadge = match($user->role) {
                            'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                            'guru_bk' => 'bg-teal-subtle text-primary-teal border border-teal-subtle',
                            'wali_kelas' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                            'murid' => 'bg-primary-subtle text-primary border border-primary-subtle',
                            default => 'bg-light text-dark',
                        };
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-teal-subtle text-primary-teal d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 36px; height: 36px; min-width: 36px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-slate-800">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-light text-muted border smaller">Akun Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $roleBadge }} fw-semibold px-2.5 py-1.5">
                                {{ $user->role_label }}
                            </span>
                        </td>
                        <td>
                            @if($user->must_change_password)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    <i class="bi bi-clock-history me-1"></i> Ya
                                </span>
                            @else
                                <span class="badge bg-light text-muted border">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <x-badge-status :status="$user->is_active ? 'aktif' : 'nonaktif'" />
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#modalResetPassword-{{ $user->id }}" title="Reset Password">
                                    <i class="bi bi-key"></i>
                                </button>
                                <button type="button" class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#modalEditUser-{{ $user->id }}" title="Ubah Akun">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('panel.akun.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-light border {{ $user->is_active ? 'text-warning' : 'text-success' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $user->is_active ? 'bi-dash-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('panel.akun.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
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
                            <x-empty-state icon="bi-shield-lock" title="Belum Ada Akun Pengguna" description="Tidak ditemukan akun pengguna sesuai filter yang dipilih." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View: Clean Card List -->
    <div class="d-block d-md-none p-3">
        @forelse($users as $user)
            @php
                $roleBadge = match($user->role) {
                    'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
                    'guru_bk' => 'bg-teal-subtle text-primary-teal border border-teal-subtle',
                    'wali_kelas' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                    'murid' => 'bg-primary-subtle text-primary border border-primary-subtle',
                    default => 'bg-light text-dark',
                };
            @endphp
            <div class="card border rounded-3 p-3 mb-3 shadow-none bg-white">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-teal-subtle text-primary-teal d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; min-width: 38px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-slate-800 fs-6">{{ $user->name }}</div>
                            <div class="text-muted small">Username: <code>{{ $user->username }}</code></div>
                        </div>
                    </div>
                    <x-badge-status :status="$user->is_active ? 'aktif' : 'nonaktif'" />
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 my-2 pt-2 border-top">
                    <span class="badge {{ $roleBadge }} fw-semibold px-2 py-1">
                        {{ $user->role_label }}
                    </span>
                    @if($user->id === auth()->id())
                        <span class="badge bg-light text-muted border">Akun Anda</span>
                    @endif
                    @if($user->email)
                        <span class="small text-muted text-truncate" style="max-width: 180px;">
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                        </span>
                    @endif
                    @if($user->must_change_password)
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-auto">
                            <i class="bi bi-clock-history me-1"></i> Reset Sandi
                        </span>
                    @endif
                </div>

                <div class="d-flex gap-2 mt-2 pt-2 border-top">
                    <button type="button" class="btn btn-sm btn-outline-warning flex-fill" data-bs-toggle="modal" data-bs-target="#modalResetPassword-{{ $user->id }}">
                        <i class="bi bi-key me-1"></i> Sandi
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#modalEditUser-{{ $user->id }}">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('panel.akun.toggle-status', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi {{ $user->is_active ? 'bi-dash-circle text-warning' : 'bi-check-circle text-success' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('panel.akun.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5" title="Hapus Akun">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-4 text-center">
                <x-empty-state icon="bi-shield-lock" title="Belum Ada Akun Pengguna" description="Tidak ditemukan akun pengguna sesuai filter yang dipilih." />
            </div>
        @endforelse
    </div>

    <!-- Modals for each user (Edit & Reset Password) -->
    @foreach($users as $user)
        <!-- Modal Edit User -->
        <div class="modal fade" id="modalEditUser-{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-start">
                    <form action="{{ route('panel.akun.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Ubah Akun: {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <x-form-input name="name" label="Nama Lengkap" :value="$user->name" required />
                            <x-form-input name="username" label="Username" :value="$user->username" required />
                            <x-form-input name="email" label="Email" type="email" :value="$user->email" />
                            <x-form-select name="role" label="Role Pengguna" required>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="guru_bk" {{ $user->role === 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                                <option value="wali_kelas" {{ $user->role === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="murid" {{ $user->role === 'murid' ? 'selected' : '' }}>Murid</option>
                            </x-form-select>
                            <x-form-select name="is_active" label="Status Akun" required>
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </x-form-select>
                            <x-form-input name="password" label="Ganti Password (Kosongkan jika tidak diubah)" type="password" help="Minimal 6 karakter" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Reset Password -->
        <div class="modal fade" id="modalResetPassword-{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-start">
                    <form action="{{ route('panel.akun.reset-password', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Reset Password Akun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted mb-3">
                                Reset password untuk pengguna <strong>{{ $user->name }}</strong> (Username: <code>{{ $user->username }}</code>). Pengguna akan diwajibkan mengganti password baru ini saat pertama kali login.
                            </p>
                            <x-form-input name="new_password" label="Password Baru" type="password" placeholder="Masukkan password baru" required help="Minimal 6 karakter" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

<!-- Modal Add User -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('panel.akun.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Akun Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <x-form-input name="name" label="Nama Lengkap" placeholder="Nama pengguna" required />
                    <x-form-input name="username" label="Username Login" placeholder="Contoh: guru_siti" required help="Minimal 3 karakter tanpa spasi" />
                    <x-form-input name="email" label="Email (Opsional)" type="email" placeholder="email@sekolah.sch.id" />
                    <x-form-input name="password" label="Password Awal" type="password" placeholder="Minimal 6 karakter" required />
                    <x-form-select name="role" label="Role Pengguna" required>
                        <option value="admin">Administrator</option>
                        <option value="guru_bk">Guru BK</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="murid">Murid</option>
                    </x-form-select>
                    <x-form-select name="is_active" label="Status Akun" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </x-form-select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Akun Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Generate Student Accounts -->
<div class="modal fade" id="modalGenerateStudentAccounts" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('panel.akun.generate-students') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Generate Akun Murid Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Sistem akan secara otomatis membuatkan akun login bagi murid yang <strong>belum memiliki akun</strong>:
                        <ul class="mb-0 mt-1 ps-3">
                            <li><strong>Username:</strong> NISN masing-masing murid</li>
                            <li><strong>Role:</strong> Murid</li>
                            <li><strong>Wajib Ganti Password:</strong> Ya (saat pertama kali login)</li>
                        </ul>
                    </div>

                    <x-form-select name="classroom_id" label="Filter Berdasarkan Rombel">
                        <option value="">-- Seluruh Murid di Sekolah --</option>
                        @foreach($classrooms as $cr)
                            <option value="{{ $cr->id }}">{{ $cr->name }} (Kelas {{ $cr->level }})</option>
                        @endforeach
                    </x-form-select>

                    <x-form-input name="default_password" label="Password Awal Murid" value="password123" required help="Password seragam yang diberikan kepada murid sebelum mereka menggantinya." />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-lightning-charge me-1"></i> Mulai Generate Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Export Credentials -->
<div class="modal fade" id="modalExportCredentials" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('panel.akun.export-credentials') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Export Kredensial Akun Murid (CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Unduh file CSV berisi daftar akun murid (Username NISN, Rombel, dan Password Awal) untuk dibagikan kepada peserta didik.
                    </p>

                    <x-form-select name="classroom_id" label="Pilih Rombel">
                        <option value="">-- Seluruh Rombel --</option>
                        @foreach($classrooms as $cr)
                            <option value="{{ $cr->id }}">{{ $cr->name }} (Kelas {{ $cr->level }})</option>
                        @endforeach
                    </x-form-select>

                    <x-form-input name="default_password" label="Password Awal (Tercetak di CSV)" value="password123" required />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Unduh File Kredensial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
