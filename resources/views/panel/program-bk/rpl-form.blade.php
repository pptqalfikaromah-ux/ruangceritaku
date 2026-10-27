@extends('layouts.panel')

@section('title', 'Penyusunan Rencana Pelaksanaan Layanan (RPL)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('panel.program-bk.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Dokumen RPL</span>
                    <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs fw-medium rounded-pill">Format Standar BK Madrasah</span>
                </div>
                <h1 class="h4 fw-bold text-dark mb-0">Penyusunan RPL Bimbingan & Konseling</h1>
            </div>
        </div>
    </div>

    <!-- RPL Form -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <span class="fw-semibold text-dark fs-sm">Formulir Identitas & Rencana Pelaksanaan Layanan</span>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('panel.program-bk.rpl.store') }}" class="space-y-4">
                @csrf

                <!-- Section 1: Identitas Layanan -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Topik / Materi Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="topic" value="{{ old('topic', $selectedProgram?->name) }}" class="form-control form-control-sm" placeholder="Contoh: Mengembangkan Rasa Percaya Diri dalam Belajar" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Tautkan ke Program BK (Opsional)</label>
                        <select name="bk_program_id" class="form-select form-select-sm">
                            <option value="">-- Berdiri Sendiri / Tidak Terikat --</option>
                            @foreach($programs as $p)
                                <option value="{{ $p->id }}" {{ ($selectedProgram?->id == $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->month_planned ?: 'Bulan Bebas' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Bidang Bimbingan <span class="text-danger">*</span></label>
                        <select name="guidance_field_id" class="form-select form-select-sm" required>
                            @foreach($guidanceFields as $f)
                                <option value="{{ $f->id }}" {{ ($selectedProgram?->guidance_field_id == $f->id) ? 'selected' : '' }}>
                                    Bidang {{ $f->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Jenis Layanan BK <span class="text-danger">*</span></label>
                        <select name="service_type_id" class="form-select form-select-sm" required>
                            @foreach($serviceTypes as $srv)
                                <option value="{{ $srv->id }}" {{ ($selectedProgram?->service_type_id == $srv->id) ? 'selected' : '' }}>
                                    {{ $srv->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Sasaran Kelas / Semester <span class="text-danger">*</span></label>
                        <input type="text" name="target_class" value="{{ old('target_class', 'Kelas VII / Ganjil') }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Alokasi Waktu <span class="text-danger">*</span></label>
                        <input type="text" name="allocation_time" value="{{ old('allocation_time', '1 Kali Pertemuan x 40 Menit') }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fs-xs text-muted fw-semibold">Guru BK / Konselor Pelaksana <span class="text-danger">*</span></label>
                        <select name="counselor_id" class="form-select form-select-sm" required>
                            @foreach($counselors as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} (NIP: {{ $c->nip ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="text-muted">

                <!-- Section 2: Tujuan & Materi -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Tujuan Umum Layanan <span class="text-danger">*</span></label>
                        <textarea name="general_goals" class="form-control form-control-sm" rows="3" placeholder="Murid mampu memahami konsep diri dan mengenali potensi belajarnya secara positif..." required>{{ old('general_goals', $selectedProgram?->goals) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Tujuan Khusus Layanan</label>
                        <textarea name="specific_goals" class="form-control form-control-sm" rows="3" placeholder="1. Murid dapat menyebutkan faktor penghambat percaya diri.&#10;2. Murid dapat mempraktikkan tips komunikasi aktif di kelas.">{{ old('specific_goals') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fs-xs text-muted fw-semibold">Materi Pokok Layanan</label>
                        <textarea name="materials" class="form-control form-control-sm" rows="2" placeholder="Konsep percaya diri, kiat menghadapi rasa cemas, studi kasus...">{{ old('materials') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-xs text-muted fw-semibold">Metode / Pendekatan</label>
                        <textarea name="methods" class="form-control form-control-sm" rows="2" placeholder="Ceramah interaktif, curah pendapat (brainstorming), diskusi kelompok...">{{ old('methods') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-xs text-muted fw-semibold">Media & Alat Bantu</label>
                        <textarea name="media" class="form-control form-control-sm" rows="2" placeholder="Slide presentasi, lembar kerja murid (LKPD), video inspiratif...">{{ old('media') }}</textarea>
                    </div>
                </div>

                <hr class="text-muted">

                <!-- Section 3: Langkah Kegiatan & Evaluasi -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Langkah-Langkah Kegiatan (Tahap Awal, Inti, Penutup)</label>
                        <textarea name="steps" class="form-control form-control-sm" rows="5" placeholder="A. Tahap Awal: Pembukaan, salam, presensi, apersepsi.&#10;B. Tahap Inti: Eksplorasi materi, tanya jawab, simulasi.&#10;C. Tahap Penutup: Refleksi, kesimpulan, tindak lanjut.">{{ old('steps') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fs-xs text-muted fw-semibold">Evaluasi Proses & Evaluasi Hasil</label>
                        <textarea name="evaluation" class="form-control form-control-sm" rows="5" placeholder="1. Evaluasi Proses: Mengamati antusiasme murid dan dinamika kelas.&#10;2. Evaluasi Hasil: Mengukur pemahaman baru dan komitmen melalui angket refleksi.">{{ old('evaluation') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('panel.program-bk.index') }}" class="btn btn-light btn-sm border">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                        <i class="bi bi-save me-1"></i> Simpan & Terbitkan RPL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
