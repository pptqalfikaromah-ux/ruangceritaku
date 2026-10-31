@extends('layouts.student')

@section('title', 'Ajukan Konseling Pribadi')
@section('page_title', 'Konseling Pribadi (Mandiri)')

@section('breadcrumb')
    <a href="{{ route('murid.dashboard') }}">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('murid.konseling.index') }}">Riwayat Konseling</a>
    <span class="mx-1">/</span>
    <span class="text-dark">Ajukan Mandiri</span>
@endsection

@section('content')
<div class="space-y-4">
    <div class="row g-3 g-md-4">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white py-3.5 px-3.5 px-sm-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fw-bold text-dark fs-sm">
                        <i class="bi bi-person-heart text-emerald me-1.5"></i> Formulir Pengajuan Konseling Pribadi
                    </span>
                    <span class="badge bg-light text-muted border fs-xs rounded-pill px-3 py-1.5">
                        <i class="bi bi-shield-lock text-success me-1"></i> 100% Rahasia & Aman
                    </span>
                </div>
                <div class="card-body p-3.5 p-sm-4">
                    <form method="POST" action="{{ route('murid.konseling.store') }}" class="space-y-4">
                        @csrf

                        <!-- Dropdown 4 Bidang Masalah Konseling -->
                        <div class="mb-3.5">
                            <label class="form-label fw-bold small text-slate-700 mb-1.5">
                                Bidang Masalah yang Ingin Dikonsultasikan <span class="text-danger">*</span>
                            </label>
                            <select name="guidance_field_id" id="guidanceFieldSelect" class="form-select @error('guidance_field_id') is-invalid @enderror" required onchange="updatePurpose(this)">
                                <option value="" disabled {{ old('guidance_field_id') ? '' : 'selected' }}>-- Pilih 1 dari 4 Bidang Masalah --</option>
                                @foreach($guidanceFields as $gf)
                                    @php
                                        $fieldDesc = match($gf->code) {
                                            'BID-01' => 'Bimbingan Pribadi (Kepercayaan diri, emosi, kecemasan, masalah personal)',
                                            'BID-02' => 'Bimbingan Sosial (Pertemanan, keluarga, adaptasi lingkungan, komunikasi)',
                                            'BID-03' => 'Bimbingan Belajar (Motivasi belajar, kesulitan mapel, ujian, manajemen waktu)',
                                            'BID-04' => 'Bimbingan Karier (Minat bakat, cita-cita, jurusan, studi lanjutan)',
                                            default  => $gf->name,
                                        };
                                    @endphp
                                    <option value="{{ $gf->id }}" data-name="{{ $gf->name }}" {{ old('guidance_field_id') == $gf->id ? 'selected' : '' }}>
                                        {{ $gf->name }} &bull; {{ $fieldDesc }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="purpose" id="purposeInput" value="{{ old('purpose', 'Konseling Masalah Pribadi') }}">
                            @error('guidance_field_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted fs-xxs mt-1.5 ps-1">Pilih bidang bimbingan yang paling menggambarkan kendala yang sedang kamu rasakan.</div>
                        </div>

                        <!-- Pilihan Metode Konseling (Tatap Muka vs Online Meet) -->
                        <div class="mb-3.5">
                            <label class="form-label fw-bold small text-slate-700 mb-2">
                                Metode Konseling yang Diinginkan <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="card h-100 p-3.5 rounded-4 border text-decoration-none transition-all" style="cursor: pointer; background: #fafafa;">
                                        <div class="d-flex align-items-center gap-2.5 mb-1.5">
                                            <input type="radio" name="counseling_method" value="tatap_muka" class="form-check-input mt-0" {{ old('counseling_method', 'tatap_muka') === 'tatap_muka' ? 'checked' : '' }} required style="width: 18px; height: 18px;">
                                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="bi bi-door-open text-teal fs-5"></i> Tatap Muka di Sekolah
                                            </span>
                                        </div>
                                        <div class="text-muted small ps-4" style="font-size: 0.78rem; line-height: 1.5;">
                                            Pertemuan langsung di Ruang BK sekolah/madrasah.
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <label class="card h-100 p-3.5 rounded-4 border text-decoration-none transition-all" style="cursor: pointer; background: #fafafa;">
                                        <div class="d-flex align-items-center gap-2.5 mb-1.5">
                                            <input type="radio" name="counseling_method" value="online_meet" class="form-check-input mt-0" {{ old('counseling_method') === 'online_meet' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="bi bi-camera-video text-primary fs-5"></i> Online (Google Meet)
                                            </span>
                                        </div>
                                        <div class="text-muted small ps-4" style="font-size: 0.78rem; line-height: 1.5;">
                                            Sesi daring via Google Meet (link diberikan oleh Guru BK).
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan Konselor / Guru BK -->
                        <div class="mb-3.5">
                            <label class="form-label fw-bold small text-slate-700 mb-1.5">Pilihan Guru BK / Konselor (Opsional)</label>
                            <select name="counselor_id" class="form-select">
                                <option value="">-- Bebas / Siapa saja Guru BK yang bertugas --</option>
                                @foreach($counselors ?? [] as $c)
                                    <option value="{{ $c->id }}" {{ old('counselor_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted fs-xxs mt-1.5 ps-1">Kamu bisa memilih Guru BK yang membuatmu paling nyaman bercerita.</div>
                        </div>

                        <!-- Deskripsi Keluhan / Masalah -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-slate-700 mb-1.5">
                                Ceritakan Masalah / Keluhan yang Sedang Kamu Hadapi <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="7" 
                                      placeholder="Tuliskan dengan nyaman dan leluasa apa yang kamu rasakan, apa yang terjadi, atau bantuan apa yang kamu harapkan dari Guru BK..." 
                                      required>{{ old('description', $prefilledDescription ?? '') }}</textarea>
                            <div class="form-text text-muted small mt-1.5 ps-1" style="line-height: 1.5;">
                                <i class="bi bi-info-circle me-1"></i>Cerita dan keluhanmu dijaga kerahasiaannya dan hanya dapat dibaca oleh Guru BK untuk pendampingan.
                            </div>
                            @error('description')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons: Responsif Mobile -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 pt-3.5 border-top">
                            <a href="{{ route('murid.konseling.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 text-center order-2 order-sm-1 fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                            </a>
                            <button type="submit" class="btn btn-emerald rounded-pill px-4 py-2.5 fw-bold shadow-sm order-1 order-sm-2 text-white">
                                <i class="bi bi-send-check me-1.5"></i> Kirimkan Pengajuan Konseling
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Bantuan & Privasi -->
        <div class="col-lg-4 space-y-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3.5 p-sm-4">
                <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold fs-sm">
                    <i class="bi bi-shield-check text-emerald fs-5"></i> Jaminan Kerahasiaan BK
                </div>
                <p class="text-muted fs-xs mb-3" style="line-height: 1.6;">
                    Setiap keluhan atau curhatan yang kamu sampaikan dilindungi oleh asas kerahasiaan Bimbingan dan Konseling. Teman atau pihak lain tidak akan mengetahuinya.
                </p>
                <div class="p-2.5 rounded-3 bg-light fs-xs text-muted">
                    <div class="fw-semibold text-dark mb-1"><i class="bi bi-chat-quote me-1 text-mint"></i>Alur Berikutnya:</div>
                    <ol class="ps-3 mb-0 space-y-1">
                        <li>Guru BK meninjau ceritamu.</li>
                        <li>Guru BK menetapkan jadwal pertemuan.</li>
                        <li>Kamu mendapat notifikasi tiket di aplikasi.</li>
                    </ol>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-3.5" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: #fff;">
                <div class="fw-bold fs-sm mb-1 d-flex align-items-center gap-1.5">
                    <i class="bi bi-heart-pulse-fill text-warning"></i> Butuh Bantuan Mendesak?
                </div>
                <p class="fs-xs text-white-50 mb-2">
                    Jika kamu sedang dalam kondisi darurat, sangat tertekan, atau membutuhkan pendampingan segera:
                </p>
                <div class="fs-xs fw-semibold bg-white bg-opacity-20 p-2 rounded-2">
                    Langsung datangi Ruang BK di sekolah atau hubungi kontak darurat madrasah.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
