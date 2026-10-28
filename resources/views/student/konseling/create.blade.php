@extends('layouts.student')

@section('title', 'Ajukan Konseling Pribadi')
@section('page_title', 'Ajukan Konseling Pribadi')

@section('breadcrumb')
    <a href="{{ route('murid.konseling.ajukan') }}">Ajukan Konseling</a>
    <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
    <span class="active">Konseling Pribadi</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Notice Pemisahan Konseling Pribadi vs Hasil IMS -->
    <div class="alert alert-info bg-info-subtle border-info shadow-sm rounded-3 d-flex align-items-center justify-content-between p-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-info-circle-fill fs-3 text-info"></i>
            <div class="small">
                <strong>Form Ini Khusus Konseling Pribadi (Mandiri):</strong> Kamu bisa menceritakan keluhan, curhat, atau masalah pribadi yang sedang kamu alami di luar instrumen angket.
                <div class="mt-0.5 text-muted">Jika ingin berkonsultasi mengenai butir-butir masalah yang kamu tandai di lembar IMS, silakan gunakan menu khusus tindak lanjut IMS.</div>
            </div>
        </div>
        <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-sm btn-outline-purple text-nowrap rounded-pill px-3" style="border-color: #7c3aed; color: #7c3aed;">
            <i class="bi bi-clipboard2-check me-1"></i> Jadwalkan dari Masalah IMS
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Utama -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark fs-sm">
                        <i class="bi bi-person-heart text-emerald me-1"></i> Formulir Pengajuan Konseling Pribadi (Curhat Mandiri)
                    </span>
                    <span class="badge bg-light text-muted border fs-xs">
                        <i class="bi bi-shield-lock text-success me-1"></i> Rahasia & Aman
                    </span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('murid.konseling.store') }}" class="space-y-4">
                        @csrf

                        <!-- Topik / Tujuan -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-slate-700">
                                Topik / Hal yang Ingin Dikonsultasikan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="purpose" 
                                   value="{{ old('purpose', $prefilledPurpose ?? '') }}" 
                                   class="form-control @error('purpose') is-invalid @enderror" 
                                   placeholder="Contoh: Merasa cemas menghadapi ujian, bingung memilih jurusan, kendala belajar, dll." 
                                   required>
                            @error('purpose')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilihan Metode Konseling (Tatap Muka vs Online Meet) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-slate-700 mb-2">
                                Metode Konseling yang Diinginkan <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2.5">
                                <div class="col-sm-6">
                                    <label class="card h-100 p-3 rounded-3 border text-decoration-none" style="cursor: pointer; background: #fafafa;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <input type="radio" name="counseling_method" value="tatap_muka" class="form-check-input mt-0" {{ old('counseling_method', 'tatap_muka') === 'tatap_muka' ? 'checked' : '' }} required>
                                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="bi bi-door-open text-teal fs-5"></i> Tatap Muka di Sekolah
                                            </span>
                                        </div>
                                        <div class="text-muted small ps-4" style="font-size: 0.75rem;">
                                            Pertemuan langsung di Ruang BK sekolah/madrasah.
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <label class="card h-100 p-3 rounded-3 border text-decoration-none" style="cursor: pointer; background: #fafafa;">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <input type="radio" name="counseling_method" value="online_meet" class="form-check-input mt-0" {{ old('counseling_method') === 'online_meet' ? 'checked' : '' }}>
                                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="bi bi-camera-video text-primary fs-5"></i> Online (Google Meet)
                                            </span>
                                        </div>
                                        <div class="text-muted small ps-4" style="font-size: 0.75rem;">
                                            Sesi daring via Google Meet (link diberikan oleh Guru BK).
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Bidang Masalah & Jenis Layanan -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-slate-700">Bidang Bimbingan</label>
                                <select name="guidance_field_id" class="form-select">
                                    <option value="">-- Pilih Bidang (Opsional) --</option>
                                    @foreach($guidanceFields as $f)
                                        <option value="{{ $f->id }}" {{ old('guidance_field_id', $prefilledFieldId ?? '') == $f->id ? 'selected' : '' }}>
                                            Bidang {{ $f->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-slate-700">Jenis Layanan yang Diharapkan</label>
                                <select name="service_type_id" class="form-select">
                                    <option value="">-- Belum Tahu / Disesuaikan Guru BK --</option>
                                    @foreach($serviceTypes as $srv)
                                        <option value="{{ $srv->id }}" {{ old('service_type_id', $prefilledServiceId ?? '') == $srv->id ? 'selected' : '' }}>
                                            {{ $srv->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi Keluhan / Masalah -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-slate-700">
                                Ceritakan Masalah / Kondisi yang Sedang Kamu Hadapi <span class="text-danger">*</span>
                            </label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="6" 
                                      placeholder="Tuliskan dengan nyaman dan leluasa apa yang kamu rasakan, apa yang terjadi, atau bantuan apa yang kamu harapkan dari Guru BK..." 
                                      required>{{ old('description', $prefilledDescription ?? '') }}</textarea>
                            <div class="form-text text-muted small mt-1">
                                <i class="bi bi-info-circle me-1"></i>Cerita dan keluhanmu hanya dapat dibaca oleh Guru BK untuk pendampingan konseling.
                            </div>
                            @error('description')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Opsi Tambahan: Cantumkan Butir IMS (Opsional) -->
                        <div class="mb-4">
                            <div class="accordion border rounded-3 overflow-hidden shadow-xs" id="accordionImsOptional">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingIms">
                                        <button class="accordion-button collapsed py-2.5 px-3 bg-light fw-bold fs-xs text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIms" aria-expanded="false" aria-controls="collapseIms">
                                            <i class="bi bi-clipboard2-check text-mint me-2 fs-6"></i>
                                            <span>Ingin Mencantumkan Butir Masalah dari IMS? <span class="badge bg-secondary-subtle text-muted ms-1">Opsional</span></span>
                                        </button>
                                    </h2>
                                    <div id="collapseIms" class="accordion-collapse collapse" aria-labelledby="headingIms" data-bs-parent="#accordionImsOptional">
                                        <div class="accordion-body p-3 bg-white border-top">
                                            <p class="fs-xs text-muted mb-2.5">
                                                <i class="bi bi-info-circle me-1 text-mint"></i>
                                                Jika ada pernyataan di bawah ini yang sesuai dengan apa yang kamu rasakan, kamu boleh mencentangnya sebagai referensi pendukung bagi Guru BK:
                                            </p>
                                            <div class="row g-2" style="max-height: 260px; overflow-y: auto;">
                                                @forelse($allImsStatements ?? [] as $stmt)
                                                    <div class="col-12">
                                                        <label class="d-flex align-items-start gap-2 p-2 rounded-2 border border-light-subtle bg-light small mb-0 cursor-pointer hover-bg-teal" style="transition: background 0.15s;">
                                                            <input type="checkbox" name="attached_statements[]" value="{{ $stmt->id }}" class="form-check-input mt-1 flex-shrink-0" {{ in_array($stmt->id, old('attached_statements', [])) ? 'checked' : '' }}>
                                                            <div>
                                                                <span class="badge bg-secondary-subtle text-dark fs-xxs">#Butir {{ $stmt->statement_number }}</span>
                                                                <span class="badge bg-teal-subtle text-teal fs-xxs">{{ $stmt->guidanceField?->name ?? 'Umum' }}</span>
                                                                <div class="fs-xs text-dark mt-0.5">{{ $stmt->statement_text }}</div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @empty
                                                    <div class="col-12 text-center text-muted fs-xs py-2">
                                                        Tidak ada data butir IMS aktif.
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferensi Waktu & Catatan Tambahan -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-slate-700">Usulan Hari / Waktu Pertemuan</label>
                                <input type="text" 
                                       name="preferred_schedule" 
                                       value="{{ old('preferred_schedule') }}" 
                                       class="form-control" 
                                       placeholder="Contoh: Istirahat pertama, Hari Selasa pulang sekolah...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-slate-700">Preferensi Guru BK (Opsional)</label>
                                <select name="counselor_id" class="form-select">
                                    <option value="">-- Bebas / Siapa saja Guru BK yang bertugas --</option>
                                    @foreach($counselors ?? [] as $c)
                                        <option value="{{ $c->id }}" {{ old('counselor_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->user->name ?? 'Guru BK' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-slate-700">Catatan Khusus Tambahan (Opsional)</label>
                            <input type="text" 
                                   name="additional_notes" 
                                   value="{{ old('additional_notes') }}" 
                                   class="form-control" 
                                   placeholder="Contoh: Ingin konseling secara tatap muka di ruang BK pribadi...">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('murid.konseling.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-emerald btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">
                                <i class="bi bi-send-check me-1"></i> Kirimkan Pengajuan Konseling
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Informasi & Bantuan -->
        <div class="col-lg-4 space-y-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-shield-check text-emerald fs-4"></i>
                    <h5 class="fw-bold text-dark mb-0 fs-sm">Asas Kerahasiaan BK</h5>
                </div>
                <p class="fs-xs text-muted mb-3" style="line-height: 1.6;">
                    Setiap hal yang kamu konsultasikan dilindungi kode etik kerahasiaan bimbingan konseling. Guru BK tidak akan membocorkan ceritamu kepada teman sekelas atau pihak luar.
                </p>
                <div class="p-3 bg-light rounded-3 border fs-xs space-y-2">
                    <div class="fw-bold text-slate-800 mb-1">Tahapan Selanjutnya:</div>
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <span class="badge bg-secondary rounded-circle px-1.5 py-0.5">1</span>
                        <span>Guru BK meninjau permohonanmu.</span>
                    </div>
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <span class="badge bg-secondary rounded-circle px-1.5 py-0.5">2</span>
                        <span>Guru BK menetapkan tanggal, jam, dan ruang konseling.</span>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge bg-secondary rounded-circle px-1.5 py-0.5">3</span>
                        <span>Kamu menerima jadwal dan menekan <strong>Konfirmasi Hadir</strong>.</span>
                    </div>
                </div>
            </div>

            <!-- Bantuan Kontak -->
            <div class="card border-0 shadow-sm rounded-3 bg-white p-4">
                <h6 class="fw-bold text-dark mb-2 fs-sm"><i class="bi bi-geo-alt text-emerald me-1"></i> Ruang BK Madrasah</h6>
                <p class="text-muted fs-xs mb-0">
                    Kamu juga bisa langsung datang ke <strong>Ruang Bimbingan Konseling (BK)</strong> pada jam istirahat sekolah jika membutuhkan pendampingan segera.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
