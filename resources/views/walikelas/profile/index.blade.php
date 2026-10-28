@extends('layouts.walikelas')

@section('title', 'Profil Wali Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0.5 fs-5 fs-sm-4">Profil Data Diri</h4>
                <p class="text-muted small mb-0 fs-xs">Kelola informasi data diri Anda sebagai Wali Kelas {{ $classroom?->name ?? '' }}.</p>
            </div>
            <a href="{{ route('walikelas.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 w-100 w-sm-auto text-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-3 border-0 shadow-xs" role="alert">
                <i class="bi bi-check-circle-fill me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-3 border-0 shadow-xs" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-app shadow-sm border-0 rounded-4 bg-white overflow-hidden">
            <div class="p-3 p-sm-3.5 bg-teal-subtle border-bottom d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white text-primary-teal d-flex align-items-center justify-content-center fw-bold fs-3 shadow-xs flex-shrink-0" style="width: 54px; height: 54px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h5 class="fw-bold text-dark mb-0 fs-6 fs-sm-5 text-truncate">{{ $user->name }}</h5>
                    <div class="d-flex align-items-center flex-wrap gap-2 mt-0.5">
                        <span class="badge {{ $classroom ? 'bg-primary' : 'bg-secondary' }} text-white fs-xxs">
                            {{ $classroom ? 'Wali Kelas ' . $classroom->name : 'Wali Kelas (Belum Ditugaskan)' }}
                        </span>
                        <span class="text-muted small fs-xs">Username: <code>{{ $user->username }}</code></span>
                    </div>
                </div>
            </div>

            {{-- INFORMASI PENUGASAN AKTIF --}}
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="p-2 rounded-3 bg-warning-subtle text-warning-emphasis fs-4 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div>
                                <div class="text-muted fs-xxs fw-bold text-uppercase">Status Penugasan Rombel</div>
                                <div class="fw-bold text-dark fs-sm">
                                    @if($classroom)
                                        <span class="text-success fw-extrabold">
                                            <i class="bi bi-check-circle-fill me-1"></i>Kelas {{ $classroom->name }}
                                        </span>
                                    @else
                                        <span class="text-danger fw-semibold">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Belum Ditugaskan ke Rombel
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Tahun / Semester</div>
                        <div class="small fw-bold text-dark">{{ $activeYear?->name ?? '2026/2027' }} &bull; {{ $activeSem?->name ?? 'Ganjil' }}</div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-muted fs-xxs fw-bold text-uppercase">Siswa di Rombel</div>
                        <div class="small fw-bold text-dark">{{ $studentsCount }} Siswa</div>
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('walikelas.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label fw-semibold small text-slate-700">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="nip_nuptk" class="form-label fw-semibold small text-slate-700">NIP / NUPTK</label>
                            <input type="text" name="nip_nuptk" id="nip_nuptk" class="form-control" value="{{ old('nip_nuptk', $teacher?->nip_nuptk ?? '') }}" placeholder="Contoh: 198705152012011004">
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label fw-semibold small text-slate-700">Jenis Kelamin <span class="text-danger">*</span></label>
                            @php $gen = old('gender', $teacher?->gender ?? 'L'); @endphp
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="L" {{ $gen === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $gen === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small text-slate-700">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="walas@sekolah.sch.id">
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold small text-slate-700">No. WhatsApp / HP</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $teacher?->phone ?? '') }}" placeholder="Contoh: 081234567890">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock me-1 text-primary"></i> Ganti Password Akun</h6>
                            <p class="text-muted small mb-2">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password akun.</p>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small text-slate-700">Password Baru</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold w-100 w-sm-auto">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
