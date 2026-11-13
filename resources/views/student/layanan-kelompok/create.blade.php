@extends('layouts.student')

@section('title', 'Formulir Pengajuan Layanan Mandiri (BKP & KKP)')
@section('breadcrumb')
    <a href="{{ route('murid.layanan-kelompok.index') }}">Layanan Kelompok</a>
    <i class="bi bi-chevron-right fs-xxs text-muted"></i>
    <span class="current">Ajukan Diri</span>
@endsection

@push('styles')
<style>
    .service-card-select {
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .service-card-select:hover {
        border-color: #0d9488;
        transform: translateY(-2px);
    }
    .service-radio:checked + .service-card-select {
        border-color: #0d9488;
        background-color: #f0fdfa;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.12);
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
                <div>
                    <h1 class="h4 fw-extrabold text-dark mb-1">Pengajuan Bimbingan / Konseling Kelompok</h1>
                    <p class="text-muted fs-xs mb-0">Daftarkan dirimu untuk mengikuti sesi kelompok. Guru BK akan menyatukanmu dengan teman-teman yang memiliki kebutuhan topik serupa dan menetapkan jadwalnya.</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('murid.layanan-kelompok.store') }}" method="POST" id="formGroupCounseling">
        @csrf

        <div class="row g-3">
            <!-- Kolom Utama: Pilihan Layanan & Topik -->
            <div class="col-12 col-lg-8 space-y-3">
                <!-- 1. Pilih Jenis Layanan -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">1</span>
                        Pilih Jenis Layanan yang Ingin Diikuti
                    </h6>

                    <div class="row g-2.5">
                        @if($bkpEnabled)
                            <div class="col-12 {{ $kkpEnabled ? 'col-sm-6' : 'col-12' }}">
                                <input type="radio" name="service_type" value="bimbingan_kelompok" id="typeBkp" class="d-none service-radio" {{ $selectedType === 'bimbingan_kelompok' ? 'checked' : '' }}>
                                <label for="typeBkp" class="service-card-select d-block h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <span class="badge bg-mint text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">BKP</span>
                                        <i class="bi bi-check-circle-fill text-mint fs-5"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark fs-xs mb-1">Bimbingan Kelompok</h6>
                                    <p class="text-muted fs-xxs mb-0" style="line-height: 1.5;">
                                        Membahas topik umum: kiat belajar efektif, kepercayaan diri, manajemen waktu, atau persiapan ujian bersama teman sebaya.
                                    </p>
                                </label>
                            </div>
                        @endif

                        @if($kkpEnabled)
                            <div class="col-12 {{ $bkpEnabled ? 'col-sm-6' : 'col-12' }}">
                                <input type="radio" name="service_type" value="konseling_kelompok" id="typeKkp" class="d-none service-radio" {{ $selectedType === 'konseling_kelompok' ? 'checked' : '' }}>
                                <label for="typeKkp" class="service-card-select d-block h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <span class="badge bg-purple text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">KKP</span>
                                        <i class="bi bi-check-circle-fill text-purple fs-5"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark fs-xs mb-1">Konseling Kelompok</h6>
                                    <p class="text-muted fs-xxs mb-0" style="line-height: 1.5;">
                                        Menyelesaikan masalah pribadi yang serupa bersama teman & Guru BK dalam suasana saling menjaga rahasia dan saling mendukung.
                                    </p>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Form Rincian Pengajuan -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">2</span>
                        Topik & Kebutuhan yang Ingin Kamu Bahas
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            Topik atau Hal yang Ingin Dibahas <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="topic" value="{{ old('topic') }}" class="form-control form-control-sm @error('topic') is-invalid @enderror" placeholder="Contoh: Kesulitan mengatur waktu antara belajar dan hobi" required>
                        @error('topic')
                            <div class="invalid-feedback fs-xxs">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            Cerita Singkat / Alasan Mengapa Kamu Butuh Sesi Ini <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" rows="4" class="form-control form-control-sm @error('description') is-invalid @enderror" placeholder="Ceritakan secara singkat apa yang sedang kamu rasakan atau apa harapanmu setelah mengikuti sesi kelompok ini..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback fs-xxs">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Usulan Hari / Tanggal (Opsional)</label>
                            <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" min="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                            <div class="form-text fs-xxs text-muted">Tanggal di mana kamu merasa lebih leluasa.</div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold small text-dark">Preferensi Waktu Luang (Opsional)</label>
                            <input type="text" name="preferred_session" value="{{ old('preferred_session') }}" class="form-control form-control-sm" placeholder="Contoh: Jam istirahat ke-2 atau setelah pulang sekolah">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Identitas Diri Siswa & Panduan -->
            <div class="col-12 col-lg-4 space-y-3">
                <!-- Info Profil Pemohon -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <i class="bi bi-person-badge-fill me-1 text-mint"></i> Data Pemohon
                    </h6>

                    <div class="d-flex align-items-center gap-2.5 mb-3">
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="rounded-circle shadow-xs" width="46" height="46" style="object-fit: cover;">
                        <div>
                            <div class="fw-bold text-dark fs-xs">{{ $student->name }}</div>
                            <div class="text-muted fs-xxs">NISN: {{ $student->nisn }} &bull; Kelas {{ $student->classrooms->first()?->name ?? '-' }}</div>
                            @if($student->zodiac_label)
                                <span class="badge bg-purple-subtle text-purple border-0 rounded-pill px-2 py-0.5 mt-1 fs-xxs">
                                    {{ $student->zodiac_label }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border fs-xs">
                        <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-1.5">
                            <i class="bi bi-info-circle-fill text-mint"></i> Bagaimana alurnya?
                        </div>
                        <ul class="ps-3 mb-0 text-muted fs-xxs" style="line-height: 1.6;">
                            <li>Kamu mengajukan permohonan untuk dirimu sendiri.</li>
                            <li>Guru BK akan menyaring dan mengelompokkan siswa dengan topik yang senada.</li>
                            <li>Ketika jadwal dan daftar teman sekelompok sudah ditetapkan, kamu akan mendapat **notifikasi langsung di dashboard**.</li>
                        </ul>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-mint text-white rounded-pill py-2.5 fw-bold shadow-sm fs-xs">
                        <i class="bi bi-send-check-fill me-1"></i> Kirim Pengajuan Mandiri
                    </button>
                    <a href="{{ route('murid.layanan-kelompok.index') }}" class="btn btn-light border rounded-pill py-2 text-muted fw-semibold fs-xs">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
