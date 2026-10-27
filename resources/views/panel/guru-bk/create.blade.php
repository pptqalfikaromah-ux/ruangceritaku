@extends('layouts.panel')

@section('title', 'Tambah Guru BK Baru')

@section('content')
<x-page-header title="Tambah Guru BK Baru"
               subtitle="Tambahkan tenaga konseling dan opsi pembuatan akun pengguna aplikasi.">
    <x-slot:actions>
        <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-10">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.guru-bk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3">Biodata Guru BK</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <x-form-input name="name" label="Nama Lengkap (Tanpa Gelar)" placeholder="Contoh: Siti Rahmawati" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="title" label="Gelar Akademik" placeholder="Contoh: S.Pd., Kons." help="Gelar di belakang nama" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="nip_nuptk" label="NIP / NUPTK" placeholder="Nomor Induk Pegawai / Pendidik" />
                        </div>
                        <div class="col-md-6">
                            <x-form-select name="gender" label="Jenis Kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </x-form-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="phone" label="No. Handphone / WhatsApp" placeholder="Contoh: 081234567890" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="email" label="Alamat Email" type="email" placeholder="email@sekolah.sch.id" />
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label fw-semibold small text-slate-700">Foto Profil</label>
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                                <div class="form-text small text-muted">Format JPG/PNG, maksimal 2MB.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-form-select name="status" label="Status Guru BK" required>
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </x-form-select>
                        </div>
                    </div>

                    <!-- Option Create Account -->
                    <div class="p-4 rounded-3 bg-light border mt-4 mb-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="createAccountSwitch" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }} onchange="toggleAccountFields(this.checked)">
                            <label class="form-check-label fw-bold text-slate-800" for="createAccountSwitch">
                                <i class="bi bi-shield-lock me-1 text-primary-teal"></i> Buatkan Akun Login Langsung untuk Guru BK Ini
                            </label>
                            <div class="text-muted smaller">Akun akan otomatis memiliki role <strong>guru_bk</strong> dan terhubung langsung dengan profil ini.</div>
                        </div>

                        <div id="accountFields" style="display: {{ old('create_account') ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form-input name="username" label="Username Akun" placeholder="Contoh: sitirahmawati" help="Digunakan untuk login ke panel" />
                                </div>
                                <div class="col-md-6">
                                    <x-form-input name="password" label="Password Awal" type="password" placeholder="Minimal 6 karakter" help="Guru BK wajib mengganti password saat login pertama" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Data Guru BK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAccountFields(isChecked) {
        const fields = document.getElementById('accountFields');
        if (fields) {
            fields.style.display = isChecked ? 'block' : 'none';
        }
    }
</script>
@endpush
