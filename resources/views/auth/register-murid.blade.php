@extends('layouts.guest')

@section('title', 'Daftar Akun - ' . app_setting('app_name', 'Ruang Ceritaku'))

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4 px-3" style="background: radial-gradient(circle at 50% 10%, rgba(204, 251, 241, 0.45) 0%, rgba(240, 253, 250, 0.7) 40%, rgba(241, 245, 249, 1) 100%);">
    
    <!-- Top Navigation Link -->
    <div class="mb-3 text-start" style="max-width: 620px; width: 100%;">
        <a href="{{ route('login.murid') }}" class="text-decoration-none text-muted small fw-bold d-inline-flex align-items-center gap-1.5 hover-teal">
            <i class="bi bi-arrow-left"></i> Sudah Punya Akun? Masuk ke Portal
        </a>
    </div>

    <div class="card card-app border-0 shadow-lg overflow-hidden position-relative" style="max-width: 620px; width: 100%; border-radius: 1.25rem; border: 1px solid var(--border-color) !important; background: #ffffff;">
        <!-- Top Gradient Band -->
        <div style="height: 5px; background: linear-gradient(90deg, #0d9488 0%, #10b981 50%, #f59e0b 100%);"></div>

        <div class="card-body p-3.5 p-sm-4 p-md-4.5">
            <!-- App Logo & Title Header -->
            <div class="text-center mb-3.5">
                <a href="{{ route('landing') }}" class="text-decoration-none d-inline-flex flex-column align-items-center mb-1">
                    <div class="p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-xs mb-1" style="background: #ffffff; border: 1.5px solid #ccfbf1;">
                        <img src="{{ app_logo() }}" alt="{{ app_setting('app_name', 'Ruang Ceritaku') }}" style="height: 38px; width: auto; object-fit: contain;">
                    </div>
                    <span class="fs-6 fw-extrabold text-dark mt-1" style="letter-spacing: -0.02em;">{{ app_setting('app_name', 'Ruang Ceritaku') }}</span>
                    <span class="text-muted small fs-xs">{{ app_setting('school_name', 'Sekolah') }}</span>
                </a>
                <div class="mt-2">
                    <h5 class="fw-extrabold text-dark mb-1">Formulir Pendaftaran Akun</h5>
                    <p class="text-muted small mb-0">Lengkapi data diri dan ajukan username akun Anda untuk memulai bimbingan konseling.</p>
                </div>
            </div>

            <!-- Role Notice Banner -->
            <div class="p-2.5 rounded-3 mb-3.5 border d-flex align-items-center gap-2.5" style="background: #f0fdfa; border-color: #ccfbf1 !important;">
                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 32px; height: 32px; background: #ffffff; color: var(--primary);">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </div>
                <div class="small">
                    <span class="fw-bold text-dark d-block" style="font-size: 0.82rem;">Daftar Akun Siswa</span>
                    <span class="text-muted" style="font-size: 0.72rem;">Akun Guru BK dan Wali Kelas dikelola langsung oleh Administrator sekolah.</span>
                </div>
            </div>

            <!-- Alerts -->
            <x-alert />

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-3 border-0 shadow-xs" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 text-danger"></i>
                        <div class="small">
                            <strong>Terdapat kesalahan pengisian formulir:</strong>
                            <ul class="mb-0 ps-3 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register.murid.submit') }}" method="POST" id="formRegister">
                @csrf

                <!-- SECTION 1: DATA IDENTITAS SISWA -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2 pb-1 border-bottom">
                        <div class="p-1 rounded bg-teal-subtle text-mint fw-bold fs-xs px-2">1</div>
                        <span class="fw-extrabold text-dark small text-uppercase tracking-wide" style="font-size: 0.82rem;">Data Identitas Murid</span>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold small text-slate-700">
                            Nama Lengkap Siswa <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                <i class="bi bi-person text-mint"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0 rounded-end-pill @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Muhammad Farhan"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <div class="row g-2.5 mb-3">
                        <!-- NISN -->
                        <div class="col-12 col-sm-6">
                            <label for="nisn" class="form-label fw-bold small text-slate-700">
                                NISN <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                    <i class="bi bi-card-text text-mint"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 rounded-end-pill @error('nisn') is-invalid @enderror"
                                       id="nisn"
                                       name="nisn"
                                       value="{{ old('nisn') }}"
                                       placeholder="10 digit nomor NISN"
                                       maxlength="15"
                                       required>
                            </div>
                            <div class="form-text text-muted fs-xs">Nomor Induk Siswa Nasional resmi</div>
                        </div>

                        <!-- NIS (Opsional) -->
                        <div class="col-12 col-sm-6">
                            <label for="nis" class="form-label fw-bold small text-slate-700">
                                NIS Lokal <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                    <i class="bi bi-upc-scan text-mint"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 rounded-end-pill @error('nis') is-invalid @enderror"
                                       id="nis"
                                       name="nis"
                                       value="{{ old('nis') }}"
                                       placeholder="Nomor induk sekolah">
                            </div>
                        </div>
                    </div>

                    <!-- DROPDOWN ROMBEL / KELAS (AGAR TIDAK SALAH KAMAR) -->
                    <div class="mb-3">
                        <label for="classroom_id" class="form-label fw-bold small text-slate-700">
                            Pilih Rombel / Kelas Anda <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                <i class="bi bi-door-open-fill text-mint"></i>
                            </span>
                            <select name="classroom_id"
                                    id="classroom_id"
                                    class="form-select border-start-0 rounded-end-pill @error('classroom_id') is-invalid @enderror"
                                    required>
                                <option value="" disabled {{ old('classroom_id') ? '' : 'selected' }}>-- Pilih Rombel yang Tersedia di Sekolah --</option>
                                @foreach ($classrooms as $c)
                                    <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                                        Kelas {{ $c->name }} (Tingkat {{ $c->level }}{{ $c->major ? ' - ' . $c->major : '' }})
                                        @if($c->academicYear) &bull; TA {{ $c->academicYear->name }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-text text-muted fs-xs mt-1">
                            <i class="bi bi-info-circle text-primary me-1"></i>Pilih sesuai rombel Anda agar langsung terhubung ke <strong>Wali Kelas</strong> dan <strong>Guru BK</strong> Anda.
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-slate-700 d-block">
                            Jenis Kelamin <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender_l" value="L" {{ old('gender', 'L') === 'L' ? 'checked' : '' }} required>
                                <label class="form-check-label small" for="gender_l">
                                    <i class="bi bi-gender-male text-primary me-1"></i> Laki-laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender_p" value="P" {{ old('gender') === 'P' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="gender_p">
                                    <i class="bi bi-gender-female text-danger me-1"></i> Perempuan
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div class="row g-2.5 mb-3">
                        <div class="col-12 col-sm-6">
                            <label for="birth_place" class="form-label fw-bold small text-slate-700">
                                Tempat Lahir <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <input type="text"
                                   class="form-control rounded-pill px-3"
                                   id="birth_place"
                                   name="birth_place"
                                   value="{{ old('birth_place') }}"
                                   placeholder="Contoh: Banyumas">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="birth_date" class="form-label fw-bold small text-slate-700">
                                Tanggal Lahir <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <input type="date"
                                   class="form-control rounded-pill px-3"
                                   id="birth_date"
                                   name="birth_date"
                                   value="{{ old('birth_date') }}">
                        </div>
                    </div>

                    <!-- Nomor WhatsApp / Kontak -->
                    <div class="mb-3">
                        <label for="guardian_phone" class="form-label fw-bold small text-slate-700">
                            No. WhatsApp / HP Siswa atau Wali <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                <i class="bi bi-whatsapp text-mint"></i>
                            </span>
                            <input type="tel"
                                   class="form-control border-start-0 rounded-end-pill"
                                   id="guardian_phone"
                                   name="guardian_phone"
                                   value="{{ old('guardian_phone') }}"
                                   placeholder="Contoh: 081234567890">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: AKUN LOGIN YANG DIAJUKAN -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2 pb-1 border-bottom">
                        <div class="p-1 rounded bg-teal-subtle text-mint fw-bold fs-xs px-2">2</div>
                        <span class="fw-extrabold text-dark small text-uppercase tracking-wide" style="font-size: 0.82rem;">Kredensial Akun Login yang Diajukan</span>
                    </div>

                    <!-- Username Akun -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold small text-slate-700">
                            Username Akun yang Diajukan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                <i class="bi bi-at text-mint fs-5"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0 rounded-end-pill @error('username') is-invalid @enderror"
                                   id="username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Contoh: farhan_07"
                                   required>
                        </div>
                        <div class="form-text text-muted fs-xs mt-1">
                            Gunakan kombinasi huruf, angka, atau tanda garis bawah tanpa spasi (minimal 3 karakter).
                        </div>
                    </div>

                    <!-- Password & Konfirmasi Password -->
                    <div class="row g-2.5 mb-2">
                        <div class="col-12 col-sm-6">
                            <label for="password" class="form-label fw-bold small text-slate-700">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                    <i class="bi bi-shield-lock text-mint"></i>
                                </span>
                                <input type="password"
                                       class="form-control border-start-0 border-end-0 ps-1 @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Minimal 4 karakter"
                                       required>
                                <button class="btn btn-light border border-start-0 rounded-end-pill pe-3 text-muted" type="button" onclick="togglePasswordVisibility('password', 'iconPass1')">
                                    <i class="bi bi-eye" id="iconPass1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="password_confirmation" class="form-label fw-bold small text-slate-700">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted rounded-start-pill ps-3">
                                    <i class="bi bi-check2-circle text-mint"></i>
                                </span>
                                <input type="password"
                                       class="form-control border-start-0 border-end-0 ps-1"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Ulangi password"
                                       required>
                                <button class="btn btn-light border border-start-0 rounded-end-pill pe-3 text-muted" type="button" onclick="togglePasswordVisibility('password_confirmation', 'iconPass2')">
                                    <i class="bi bi-eye" id="iconPass2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-mint py-2.5 rounded-pill fw-bold shadow-sm" style="font-size: 0.95rem; letter-spacing: 0.02em;">
                        <i class="bi bi-person-plus-fill me-1.5"></i> Daftar Akun
                    </button>
                </div>
            </form>

            <div class="text-center mt-3 pt-3 border-top border-light">
                <span class="text-muted small">Sudah memiliki akun terdaftar sebelumnya?</span>
                <div class="mt-1">
                    <a href="{{ route('login.murid') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.8rem;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="text-center text-muted small mt-3">
        &copy; {{ date('Y') }} {{ app_setting('app_name', 'Ruang Ceritaku') }} &bull; {{ app_setting('school_name', 'Sekolah') }}
    </div>
</div>

<style>
    .hover-teal:hover {
        color: var(--primary) !important;
    }
</style>

<script>
    function togglePasswordVisibility(fieldId, iconId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
