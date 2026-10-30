@extends('layouts.student')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <span style="color:var(--text-dark);font-weight:700;">Profil Saya</span>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0.5 fs-5">Profil Data Diri Siswa</h4>
                <p class="text-muted small mb-0 fs-xs">Kelola informasi pribadi, kontak, dan kata sandi akun Anda secara mandiri.</p>
            </div>
            <a href="{{ route('murid.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 w-100 w-sm-auto text-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-3 border-0 shadow-xs" role="alert">
                <i class="bi bi-check-circle-fill me-1.5 text-success"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-3 border-0 shadow-xs" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Perhatian:</div>
                <ul class="mb-0 ps-3 fs-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4">
            <!-- Header Profil -->
            <div class="p-3.5 p-sm-4 bg-teal-subtle border-bottom d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);">
                <div class="position-relative">
                    <img src="{{ $student->photo_url }}" 
                         id="previewAvatar" 
                         alt="{{ $student->name }}" 
                         class="rounded-circle shadow-sm border border-2 border-white flex-shrink-0" 
                         width="68" 
                         height="68" 
                         style="object-fit: cover; background: #fff;">
                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-white" style="font-size: 0.6rem;">Aktif</span>
                </div>
                <div class="min-w-0">
                    <h5 class="fw-bold text-dark mb-0 fs-6 fs-sm-5 text-truncate">{{ $student->name }}</h5>
                    <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                        <span class="badge bg-teal text-white fs-xxs rounded-pill px-2.5 py-1" style="background:#0d9488;">
                            <i class="bi bi-building me-1"></i>Kelas {{ $classroom?->name ?? 'Siswa' }}
                        </span>
                        <span class="text-muted small fs-xs">NISN: <code class="text-dark fw-bold">{{ $student->nisn }}</code></span>
                        <span class="text-muted small fs-xs">&bull; Status: <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span></span>
                    </div>
                </div>
            </div>

            <form action="{{ route('murid.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-3.5 p-sm-4">
                @csrf
                @method('PUT')

                <!-- INFORMASI AKADEMIK RESMI (READ-ONLY) -->
                <div class="p-3 rounded-3 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-dark fs-xs text-uppercase letter-spacing-1">
                            <i class="bi bi-shield-check text-success me-1"></i> Data Resmi Madrasah (Terkunci)
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary fs-xxs">Hanya dapat diubah oleh Admin / TU</span>
                    </div>
                    <div class="row g-2.5">
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs">NISN</div>
                            <div class="fw-bold text-dark fs-xs font-monospace">{{ $student->nisn }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs">NIS Lokal</div>
                            <div class="fw-bold text-dark fs-xs font-monospace">{{ $student->nis ?? '-' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs">Rombel / Kelas</div>
                            <div class="fw-bold text-dark fs-xs">{{ $classroom?->name ?? 'Belum terdaftar' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs">Tahun Ajaran / Sem</div>
                            <div class="fw-bold text-dark fs-xs">{{ $activeYear?->name ?? '-' }} ({{ $activeSem?->name ?? '-' }})</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- FOTO PROFIL -->
                    <div class="col-12">
                        <label class="form-label fw-bold small text-slate-700">Foto Profil</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="file" 
                                   name="photo" 
                                   id="photoInput" 
                                   class="form-control form-control-sm @error('photo') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp" 
                                   onchange="previewImage(this)">
                        </div>
                        <div class="form-text text-muted fs-xxs mt-1">Format gambar: JPG, PNG, WEBP. Maksimal 2MB.</div>
                        @error('photo')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA LENGKAP -->
                    <div class="col-12 col-md-8">
                        <label class="form-label fw-bold small text-slate-700">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                               value="{{ old('name', $student->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small text-slate-700">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-3 mt-1.5">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" {{ old('gender', $student->gender) === 'L' ? 'checked' : '' }} required>
                                <label class="form-check-label fs-xs" for="genderL">Laki-laki</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="genderP" value="P" {{ old('gender', $student->gender) === 'P' ? 'checked' : '' }} required>
                                <label class="form-check-label fs-xs" for="genderP">Perempuan</label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TEMPAT & TANGGAL LAHIR -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">Tempat Lahir</label>
                        <input type="text" 
                               name="birth_place" 
                               class="form-control form-control-sm @error('birth_place') is-invalid @enderror" 
                               value="{{ old('birth_place', $student->birth_place) }}" 
                               placeholder="Contoh: Semarang">
                        @error('birth_place')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">Tanggal Lahir</label>
                        <input type="date" 
                               name="birth_date" 
                               class="form-control form-control-sm @error('birth_date') is-invalid @enderror" 
                               value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
                        @error('birth_date')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- AGAMA -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">Agama</label>
                        <input type="text" 
                               name="religion" 
                               class="form-control form-control-sm @error('religion') is-invalid @enderror" 
                               value="{{ old('religion', $student->religion ?? 'Islam') }}">
                        @error('religion')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL (OPSIONAL) -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">Email Akun (Opsional)</label>
                        <input type="email" 
                               name="email" 
                               class="form-control form-control-sm @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ALAMAT LENGKAP -->
                    <div class="col-12">
                        <label class="form-label fw-bold small text-slate-700">Alamat Tempat Tinggal</label>
                        <textarea name="address" 
                                  rows="2" 
                                  class="form-control form-control-sm @error('address') is-invalid @enderror" 
                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kab">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA WALI & KONTAK WALI -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">Nama Orang Tua / Wali</label>
                        <input type="text" 
                               name="guardian_name" 
                               class="form-control form-control-sm @error('guardian_name') is-invalid @enderror" 
                               value="{{ old('guardian_name', $student->guardian_name) }}" 
                               placeholder="Nama ayah / ibu / wali murid">
                        @error('guardian_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700">No. WhatsApp / HP Orang Tua / Wali</label>
                        <input type="text" 
                               name="guardian_phone" 
                               class="form-control form-control-sm @error('guardian_phone') is-invalid @enderror" 
                               value="{{ old('guardian_phone', $student->guardian_phone) }}" 
                               placeholder="Contoh: 081234567890">
                        @error('guardian_phone')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- GANTI KATA SANDI (OPSIONAL) -->
                    <div class="col-12">
                        <hr class="my-2 border-slate-200">
                        <label class="form-label fw-bold small text-slate-700 mb-1">
                            <i class="bi bi-key-fill text-warning me-1"></i> Ganti Password Akun
                        </label>
                        <div class="text-muted fs-xxs mb-2">Kosongkan jika Anda tidak berniat mengubah password akun login Anda.</div>
                        <input type="password" 
                               name="password" 
                               class="form-control form-control-sm @error('password') is-invalid @enderror" 
                               placeholder="Ketik password baru jika ingin diganti (minimal 6 karakter)">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2.5 mt-4 pt-3 border-top">
                    <a href="{{ route('murid.dashboard') }}" class="btn btn-light border btn-sm rounded-pill px-4 fw-semibold text-muted">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-emerald btn-sm rounded-pill px-4 py-2 fw-bold text-white shadow-sm" style="background:#0d9488; border-color:#0d9488;">
                        <i class="bi bi-save2 me-1.5"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewAvatar').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
