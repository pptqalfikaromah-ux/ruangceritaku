@extends('layouts.panel')

@section('title', 'Pengaturan Sekolah & Aplikasi')

@section('content')
<x-page-header title="Pengaturan Sekolah & Aplikasi"
               subtitle="Sesuaikan profil sekolah, nama aplikasi, logo, dan footer sistem Ruang Ceritaku.">
</x-page-header>

<div class="row">
    <div class="col-lg-10">
        <form action="{{ route('panel.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profil Sekolah -->
            <div class="card card-app mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-slate-800">
                        <i class="bi bi-building me-1 text-primary-teal"></i> Profil Sekolah
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <x-form-input name="school_name" label="Nama Sekolah" :value="app_setting('school_name', 'SMP Negeri 1 Contoh')" placeholder="Nama resmi instansi pendidikan" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="school_npsn" label="NPSN / NSM" :value="app_setting('school_npsn', '20101234')" placeholder="Nomor Pokok Sekolah Nasional" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-form-select name="school_level" label="Jenjang Pendidikan" required>
                                @foreach(['SD/MI', 'SMP/MTs', 'SMA/MA', 'SMK'] as $lvl)
                                    <option value="{{ $lvl }}" {{ app_setting('school_level', 'SMP/MTs') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </x-form-select>
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="school_phone" label="No. Telepon Sekolah" :value="app_setting('school_phone')" placeholder="(021) 1234567" />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="school_email" label="Email Resmi Sekolah" type="email" :value="app_setting('school_email')" placeholder="info@sekolah.sch.id" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="school_address" class="form-label fw-semibold small text-slate-700">Alamat Lengkap Sekolah</label>
                        <textarea name="school_address" id="school_address" rows="2" class="form-control" placeholder="Jl. Pendidikan No. 1, Kota">{{ old('school_address', app_setting('school_address')) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="headmaster_name" label="Nama Kepala Sekolah" :value="app_setting('headmaster_name')" placeholder="Nama lengkap beserta gelar" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="headmaster_nip" label="NIP Kepala Sekolah" :value="app_setting('headmaster_nip')" placeholder="NIP jika ada" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Aplikasi & Tampilan -->
            <div class="card card-app mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-slate-800">
                        <i class="bi bi-palette me-1 text-primary-teal"></i> Pengaturan Aplikasi & Branding
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="app_name" label="Nama Aplikasi" :value="app_setting('app_name', 'Ruang Ceritaku')" placeholder="Contoh: Ruang Ceritaku" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="theme_color" label="Warna Tema (Aksen CSS)" :value="app_setting('theme_color', '#0d9488')" placeholder="Contoh: #0d9488 (Teal)" help="Aksen warna tema dashboard" />
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <label for="app_logo" class="form-label fw-semibold small text-slate-700">Logo Aplikasi (Pengganti Default)</label>
                            <input type="file" name="app_logo" id="app_logo" class="form-control" accept="image/*">
                            <div class="form-text small text-muted">
                                Jika tidak diunggah, sistem akan menggunakan logo bawaan di <code>public/images/logo.png</code>. Format PNG/JPG transparan direkomendasikan, max 2MB.
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-muted small mb-2">Logo Aktif Saat Ini:</div>
                            <div class="p-2 border rounded-3 bg-light d-inline-block">
                                <x-app-logo height="46" class="img-fluid" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="app_footer" class="form-label fw-semibold small text-slate-700">Teks Footer Aplikasi</label>
                        <input type="text" name="app_footer" id="app_footer" class="form-control" value="{{ old('app_footer', app_setting('app_footer', 'Ruang Ceritaku - Layanan Bimbingan & Konseling Sekolah')) }}" placeholder="Teks copyright footer">
                    </div>
                </div>
            </div>

            <!-- Pengaturan Modul Fitur Siswa -->
            <div class="card card-app mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-slate-800">
                        <i class="bi bi-toggles me-1 text-primary-teal"></i> Modul & Fitur Layanan Siswa
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light">
                        <div class="me-3">
                            <div class="fw-bold text-dark fs-sm d-flex align-items-center gap-2">
                                <i class="bi bi-clipboard2-check text-emerald fs-5"></i> Fitur Instrumen Masalah Siswa (IMS 180 Butir)
                            </div>
                            <div class="text-muted fs-xs mt-1">
                                Jika diaktifkan, menu IMS dan lembar angket asesmen 180 butir masalah akan muncul di seluruh akun murid. Jika dinonaktifkan, seluruh fitur dan menu IMS otomatis disembunyikan dari murid.
                            </div>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" name="ims_enabled" value="1" id="switchImsEnabled" {{ app_setting('ims_enabled', '1') === '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save me-1"></i> Simpan Seluruh Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
