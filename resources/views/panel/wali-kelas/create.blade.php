@extends('layouts.panel')

@section('title', 'Tambah Wali Kelas')
@section('breadcrumb')
    <a href="{{ route('panel.wali-kelas.index') }}">Data Wali Kelas</a>
    <span class="divider">/</span>
    <span class="current">Tambah</span>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-app">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-person-plus-fill text-warning me-1.5"></i> Formulir Tambah Data & Akun Wali Kelas
                </h6>
                <div class="text-muted fs-xs">Wali kelas akan memiliki akses login khusus untuk memantau rombel binaan dan menyampaikan aduan kasus murid.</div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('panel.wali-kelas.store') }}" method="POST">
                    @csrf

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">1. Identitas Pendidik</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold small text-dark">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Budi Santoso, S.Pd." value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="gender" class="form-label fw-bold small text-dark">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="nip_nuptk" class="form-label fw-bold small text-dark">NIP / NUPTK</label>
                            <input type="text" name="nip_nuptk" id="nip_nuptk" class="form-control @error('nip_nuptk') is-invalid @enderror" placeholder="18 digit NIP atau NUPTK" value="{{ old('nip_nuptk') }}">
                            @error('nip_nuptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label fw-bold small text-dark">No. WhatsApp / HP</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label fw-bold small text-dark">Email Pendidik</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="guru@sekolah.sch.id" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold small text-dark">Status Keaktifan <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" style="max-width: 200px;" required>
                            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">2. Akun Login Sistem (Role: Wali Kelas)</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-bold small text-dark">Username Login <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="Contoh: budi.santoso" value="{{ old('username') }}" required>
                            </div>
                            <div class="form-text fs-xs text-muted">Digunakan wali kelas saat login di Portal Wali Kelas.</div>
                            @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold small text-dark">Password Akun <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div class="form-text fs-xs text-muted">Password bawaan untuk masuk ke aplikasi.</div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('panel.wali-kelas.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-check-circle-fill me-1.5"></i> Simpan Data & Akun Wali Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
