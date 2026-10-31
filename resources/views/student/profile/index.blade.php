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
            <div class="p-3.5 p-sm-4 bg-teal-subtle border-bottom d-flex align-items-center gap-3.5" style="background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);">
                <div class="position-relative flex-shrink-0">
                    <img src="{{ $student->photo_url }}" 
                         id="previewAvatar" 
                         alt="{{ $student->name }}" 
                         class="rounded-circle shadow-sm border border-2 border-white flex-shrink-0" 
                         width="72" 
                         height="72" 
                         style="object-fit: cover; background: #fff;">
                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-white px-2 py-0.5" style="font-size: 0.65rem;">Aktif</span>
                </div>
                <div class="min-w-0 flex-grow-1">
                    <h5 class="fw-bold text-dark mb-1 fs-6 fs-sm-5 text-truncate" style="letter-spacing: 0.015em;">{{ $student->name }}</h5>
                    <div class="d-flex align-items-center flex-wrap gap-2 mt-1.5">
                        <span class="badge bg-teal text-white rounded-pill px-3 py-1.5" style="background:#0d9488; font-size: 0.76rem;">
                            <i class="bi bi-building me-1"></i>Kelas {{ $classroom?->name ?? 'Siswa' }}
                        </span>
                        <span class="text-muted small fs-xs">NISN: <code class="text-dark fw-bold px-1.5 py-0.5 bg-light rounded">{{ $student->nisn }}</code></span>
                        <span class="text-muted small fs-xs">&bull; Status: <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">Aktif</span></span>
                        <span id="headerZodiacBadge" class="badge rounded-pill px-3 py-1.5 fw-bold text-white shadow-xs" style="background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%); font-size: 0.76rem; {{ $student->zodiac ? '' : 'display:none;' }}">
                            <span id="headerZodiacIcon" class="me-1">{{ $student->zodiac_icon ?? '✨' }}</span> Zodiak: <span id="headerZodiacName">{{ $student->zodiac ?? '-' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('murid.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-3.5 p-sm-4">
                @csrf
                @method('PUT')

                <!-- INFORMASI AKADEMIK RESMI (READ-ONLY) -->
                <div class="p-3.5 p-sm-4 rounded-4 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <span class="fw-bold text-dark fs-xs text-uppercase letter-spacing-1 d-inline-flex align-items-center">
                            <i class="bi bi-shield-lock-fill text-success me-1.5 fs-6"></i> Data Resmi Sekolah (Dikunci oleh Admin)
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-1.5 rounded-pill" style="font-size: 0.74rem;">Data dikunci oleh Admin</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs mb-1">NISN</div>
                            <div class="fw-bold text-dark fs-xs font-monospace p-2 bg-white rounded-3 border">{{ $student->nisn }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs mb-1">NIS Lokal</div>
                            <div class="fw-bold text-dark fs-xs font-monospace p-2 bg-white rounded-3 border">{{ $student->nis ?? '-' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs mb-1">Rombel / Kelas</div>
                            <div class="fw-bold text-dark fs-xs p-2 bg-white rounded-3 border">{{ $classroom?->name ?? 'Belum terdaftar' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted fs-xxs mb-1">Tahun Ajaran / Sem</div>
                            <div class="fw-bold text-dark fs-xs p-2 bg-white rounded-3 border">{{ $activeYear?->name ?? '-' }} ({{ $activeSem?->name ?? '-' }})</div>
                        </div>
                        <div class="col-12 col-md-6 mt-2 pt-2 border-top">
                            <div class="text-muted fs-xxs mb-1">Tempat & Tanggal Lahir</div>
                            <div class="fw-bold text-dark fs-xs p-2 bg-white rounded-3 border">
                                <span id="readOnlyBirthPlace">{{ $student->birth_place ?? '-' }}</span>, 
                                <span id="readOnlyBirthDate">{{ $student->birth_date ? $student->birth_date->isoFormat('D MMMM Y') : 'Belum diisi' }}</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-2 pt-2 border-top">
                            <div class="text-muted fs-xxs mb-1">Zodiak (Otomatis)</div>
                            <div class="fw-bold text-dark fs-xs p-2 bg-white rounded-3 border d-flex align-items-center gap-2">
                                <span id="readOnlyZodiacIcon" class="fs-6" style="color: #8b5cf6;">{{ $student->zodiac_icon ?? '✨' }}</span>
                                <span id="readOnlyZodiacName" class="badge rounded-pill bg-purple-subtle text-purple px-2.5 py-1 fw-bold fs-xs">{{ $student->zodiac ?? 'Belum terdeteksi' }}</span>
                                <span class="text-muted fs-xxs ms-auto">Mengikuti tgl lahir</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3.5">
                    <!-- FOTO PROFIL -->
                    <div class="col-12">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Foto Profil</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="file" 
                                   name="photo" 
                                   id="photoInput" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp" 
                                   onchange="previewImage(this)">
                        </div>
                        <div class="form-text text-muted fs-xxs mt-1.5 ps-1">Format gambar: JPG, PNG, WEBP. Maksimal 2MB.</div>
                        @error('photo')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA LENGKAP -->
                    <div class="col-12 col-md-8">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $student->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" {{ old('gender', $student->gender) === 'L' ? 'checked' : '' }} required>
                                <label class="form-check-label fs-xs fw-semibold ms-1" for="genderL">Laki-laki</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="genderP" value="P" {{ old('gender', $student->gender) === 'P' ? 'checked' : '' }} required>
                                <label class="form-check-label fs-xs fw-semibold ms-1" for="genderP">Perempuan</label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TEMPAT & TANGGAL LAHIR -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Tempat Lahir</label>
                        <input type="text" 
                               name="birth_place" 
                               class="form-control @error('birth_place') is-invalid @enderror" 
                               value="{{ old('birth_place', $student->birth_place) }}" 
                               placeholder="Contoh: Semarang">
                        @error('birth_place')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <label for="birthDateInput" class="form-label fw-bold small text-slate-700 mb-0">Tanggal Lahir</label>
                            <span id="fieldZodiacBadge" class="badge rounded-pill px-2.5 py-1 fw-bold fs-xxs shadow-xs" style="background: linear-gradient(135deg, #f5f3ff 0%, #fdf2f8 100%); color: #7c3aed; border: 1px solid #ddd6fe; {{ $student->zodiac ? '' : 'display:none;' }}">
                                <span id="fieldZodiacIcon">{{ $student->zodiac_icon ?? '✨' }}</span> Zodiak: <span id="fieldZodiacName">{{ $student->zodiac ?? '-' }}</span>
                            </span>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                                <i class="bi bi-calendar-event text-mint"></i>
                            </span>
                            <input type="date" 
                                   name="birth_date" 
                                   id="birthDateInput"
                                   class="form-control border-start-0 @error('birth_date') is-invalid @enderror" 
                                   value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                                   onchange="updateZodiacDisplay(this.value)"
                                   oninput="updateZodiacDisplay(this.value)">
                        </div>
                        @error('birth_date')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted fs-xxs mt-1" id="zodiacHelpText">
                            @if($student->zodiac)
                                Zodiak Anda: <strong class="text-purple">{{ $student->zodiac_icon }} {{ $student->zodiac }}</strong> (otomatis mengikuti tanggal lahir).
                            @else
                                Pilih tanggal lahir Anda untuk memunculkan zodiak otomatis.
                            @endif
                        </div>
                    </div>

                    <!-- AGAMA -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Agama</label>
                        <input type="text" 
                               name="religion" 
                               class="form-control @error('religion') is-invalid @enderror" 
                               value="{{ old('religion', $student->religion ?? 'Islam') }}">
                        @error('religion')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL (OPSIONAL) -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Email Akun (Opsional)</label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="nama@email.com">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ALAMAT LENGKAP -->
                    <div class="col-12">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                        <textarea name="address" 
                                  rows="3" 
                                  class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kab">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NAMA WALI & KONTAK WALI -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">Nama Orang Tua / Wali</label>
                        <input type="text" 
                               name="guardian_name" 
                               class="form-control @error('guardian_name') is-invalid @enderror" 
                               value="{{ old('guardian_name', $student->guardian_name) }}" 
                               placeholder="Nama ayah / ibu / wali murid">
                        @error('guardian_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold small text-slate-700 mb-1.5">No. WhatsApp / HP Orang Tua / Wali</label>
                        <input type="text" 
                               name="guardian_phone" 
                               class="form-control @error('guardian_phone') is-invalid @enderror" 
                               value="{{ old('guardian_phone', $student->guardian_phone) }}" 
                               placeholder="Contoh: 081234567890">
                        @error('guardian_phone')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- GANTI KATA SANDI (OPSIONAL) -->
                    <div class="col-12">
                        <hr class="my-3 border-slate-200">
                        <label class="form-label fw-bold small text-slate-700 mb-1">
                            <i class="bi bi-key-fill text-warning me-1"></i> Ganti Password Akun
                        </label>
                        <div class="text-muted fs-xxs mb-2.5">Kosongkan jika Anda tidak berniat mengubah password akun login Anda.</div>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Ketik password baru jika ingin diganti (minimal 6 karakter)">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2.5 mt-4 pt-3.5 border-top">
                    <a href="{{ route('murid.dashboard') }}" class="btn btn-light border rounded-pill px-4 py-2 fw-semibold text-muted">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-emerald rounded-pill px-4 py-2.5 fw-bold text-white shadow-sm" style="background:#0d9488; border-color:#0d9488;">
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

    function calculateZodiac(dateStr) {
        if (!dateStr) return null;
        const parts = dateStr.split('-');
        if (parts.length < 3) return null;
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        if (!month || !day || isNaN(month) || isNaN(day)) return null;

        let name = '';
        switch (month) {
            case 1:  name = day <= 19 ? 'Capricorn' : 'Aquarius'; break;
            case 2:  name = day <= 18 ? 'Aquarius' : 'Pisces'; break;
            case 3:  name = day <= 20 ? 'Pisces' : 'Aries'; break;
            case 4:  name = day <= 19 ? 'Aries' : 'Taurus'; break;
            case 5:  name = day <= 20 ? 'Taurus' : 'Gemini'; break;
            case 6:  name = day <= 20 ? 'Gemini' : 'Cancer'; break;
            case 7:  name = day <= 22 ? 'Cancer' : 'Leo'; break;
            case 8:  name = day <= 22 ? 'Leo' : 'Virgo'; break;
            case 9:  name = day <= 22 ? 'Virgo' : 'Libra'; break;
            case 10: name = day <= 22 ? 'Libra' : 'Scorpio'; break;
            case 11: name = day <= 21 ? 'Scorpio' : 'Sagittarius'; break;
            case 12: name = day <= 21 ? 'Sagittarius' : 'Capricorn'; break;
            default: return null;
        }

        const icons = {
            'Aries': '♈', 'Taurus': '♉', 'Gemini': '♊', 'Cancer': '♋',
            'Leo': '♌', 'Virgo': '♍', 'Libra': '♎', 'Scorpio': '♏',
            'Sagittarius': '♐', 'Capricorn': '♑', 'Aquarius': '♒', 'Pisces': '♓'
        };

        return { name: name, icon: icons[name] || '✨' };
    }

    function updateZodiacDisplay(dateVal) {
        const z = calculateZodiac(dateVal);
        const headerBadge = document.getElementById('headerZodiacBadge');
        const headerIcon  = document.getElementById('headerZodiacIcon');
        const headerName  = document.getElementById('headerZodiacName');
        
        const fieldBadge  = document.getElementById('fieldZodiacBadge');
        const fieldIcon   = document.getElementById('fieldZodiacIcon');
        const fieldName   = document.getElementById('fieldZodiacName');

        const readOnlyIcon = document.getElementById('readOnlyZodiacIcon');
        const readOnlyName = document.getElementById('readOnlyZodiacName');
        const helpText     = document.getElementById('zodiacHelpText');

        if (z) {
            if (headerBadge) {
                headerBadge.style.display = 'inline-flex';
                if (headerIcon) headerIcon.textContent = z.icon;
                if (headerName) headerName.textContent = z.name;
            }
            if (fieldBadge) {
                fieldBadge.style.display = 'inline-flex';
                if (fieldIcon) fieldIcon.textContent = z.icon;
                if (fieldName) fieldName.textContent = z.name;
            }
            if (readOnlyIcon) readOnlyIcon.textContent = z.icon;
            if (readOnlyName) {
                readOnlyName.textContent = z.name;
                readOnlyName.className = 'badge rounded-pill bg-purple-subtle text-purple px-2.5 py-1 fw-bold fs-xs';
            }
            if (helpText) {
                helpText.innerHTML = `Zodiak Anda: <strong class="text-purple">${z.icon} ${z.name}</strong> (otomatis mengikuti tanggal lahir).`;
            }
        } else {
            if (headerBadge) headerBadge.style.display = 'none';
            if (fieldBadge) fieldBadge.style.display = 'none';
            if (readOnlyIcon) readOnlyIcon.textContent = '✨';
            if (readOnlyName) {
                readOnlyName.textContent = 'Belum terdeteksi';
                readOnlyName.className = 'text-muted fs-xs';
            }
            if (helpText) {
                helpText.textContent = 'Pilih tanggal lahir Anda untuk memunculkan zodiak otomatis.';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const birthInput = document.getElementById('birthDateInput');
        if (birthInput && birthInput.value) {
            updateZodiacDisplay(birthInput.value);
        }
    });
</script>
@endpush
@endsection
