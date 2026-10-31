@extends('layouts.panel')

@section('title', 'Detail Pengaduan Siswa - ' . $pengaduan->ticket_number)

@section('breadcrumb')
    <a href="{{ route('panel.pengaduan.index') }}">Pengaduan Siswa</a>
    <i class="bi bi-chevron-right" style="font-size: .65rem; color: var(--text-muted);"></i>
    <span class="current">{{ $pengaduan->ticket_number }}</span>
@endsection

@section('content')
<div class="space-y-4">

    <!-- Top Action & Navigation Bar -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3.5 p-md-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <a href="{{ route('panel.pengaduan.index') }}" 
                       class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-xs flex-shrink-0 mt-1" 
                       style="width: 42px; height: 42px;" 
                       title="Kembali ke Daftar Pengaduan">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                            <span class="badge bg-teal-subtle text-mint font-monospace px-2.5 py-1 rounded-pill fw-bold fs-xs">
                                <i class="bi bi-ticket-perforated me-1"></i>{{ $pengaduan->ticket_number }}
                            </span>
                            <span class="badge {{ $pengaduan->status_badge_class }} px-2.5 py-1 rounded-pill fs-xs fw-semibold">
                                {{ $pengaduan->status_label }}
                            </span>
                            @if($pengaduan->safety_risk_flag)
                                <span class="badge bg-danger text-white px-2.5 py-1 rounded-pill fs-xs fw-bold">
                                    <i class="bi bi-shield-fill-exclamation me-1"></i>Risiko Keselamatan
                                </span>
                            @endif
                        </div>
                        <h1 class="h5 fw-extrabold text-dark mb-0">{{ $pengaduan->category }}</h1>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center">
                    @if($pengaduan->status === 'resolved' || $pengaduan->status === 'closed')
                        <a href="{{ route('panel.pengaduan.printReport', $pengaduan->id) }}" target="_blank" 
                           class="btn btn-outline-danger btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
                            <i class="bi bi-file-earmark-pdf me-1.5"></i> Cetak Laporan Arsip PDF
                        </a>
                    @endif
                    @if($pengaduan->student_id)
                        <a href="{{ route('panel.konseling.index', ['student_id' => $pengaduan->student_id]) }}" 
                           class="btn btn-mint btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
                            <i class="bi bi-calendar-plus me-1.5"></i> Buka Sesi Konseling Murid
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Peringatan Khusus Risiko Keselamatan Anak -->
    @if($pengaduan->safety_risk_flag)
        <div class="alert alert-danger border-0 shadow-xs d-flex align-items-start gap-3 p-3.5 p-md-4 rounded-4" 
             style="background: linear-gradient(135deg, #fff1f2 0%, #fee2e2 100%); border-left: 5px solid #e11d48 !important;">
            <div class="p-2 bg-danger text-white rounded-3 flex-shrink-0">
                <i class="bi bi-shield-fill-exclamation fs-4"></i>
            </div>
            <div>
                <h6 class="fw-extrabold text-danger mb-1">Perhatian Khusus: Terindikasi Risiko Keselamatan Anak!</h6>
                <p class="mb-0 fs-sm text-danger-emphasis" style="line-height: 1.6;">
                    Laporan ini ditandai memiliki potensi ancaman terhadap keselamatan fisik atau psikologis anak. Konselor dan pihak sekolah wajib segera melakukan verifikasi tertutup, perlindungan aman bagi pelapor, serta koordinasi cepat dengan Kepala Sekolah.
                </p>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- LEFT COLUMN: Identitas Pelapor & Kronologi Peristiwa -->
        <div class="col-lg-7 space-y-4">

            <!-- Card 1: Identitas Murid Pelapor -->
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-1.5 rounded-3 bg-teal-subtle text-mint d-inline-flex">
                            <i class="bi bi-person-badge fs-5"></i>
                        </div>
                        <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Identitas Murid Pelapor</span>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fs-xs fw-semibold">
                        <i class="bi bi-patch-check-fill me-1"></i> Identitas Terverifikasi
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 pb-3 border-bottom">
                        <div class="rounded-circle bg-teal-subtle text-mint d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" 
                             style="width: 58px; height: 58px; font-size: 1.4rem;">
                            {{ strtoupper(substr($pengaduan->student?->name ?? $pengaduan->reporter_name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-0.5">
                                <h5 class="fw-extrabold text-dark mb-0">{{ $pengaduan->student?->name ?? $pengaduan->reporter_name }}</h5>
                            </div>
                            <div class="text-muted fs-xs">
                                NISN: <code class="text-mint fw-bold">{{ $pengaduan->student?->nisn ?? '-' }}</code> 
                                &bull; Kelas: <span class="fw-semibold text-dark">{{ $pengaduan->student?->classrooms->first()?->name ?? 'VII-A' }}</span>
                                &bull; JK: <span class="fw-semibold text-dark">{{ $pengaduan->student?->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                        </div>
                        @if($pengaduan->student)
                            <a href="{{ route('panel.murid.show', $pengaduan->student->id) }}" 
                               class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fs-xs fw-semibold">
                                <i class="bi bi-person me-1"></i> Profil Murid
                            </a>
                        @endif
                    </div>

                    <div class="row g-3 pt-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light-subtle border">
                                <span class="text-muted fs-xs d-block mb-1">Status Kesiswaan</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-0.5 fs-xs fw-semibold">
                                    {{ ucfirst($pengaduan->student?->status ?? 'Aktif') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light-subtle border">
                                <span class="text-muted fs-xs d-block mb-1">Kontak Wali / Orang Tua</span>
                                <span class="fw-semibold text-dark fs-xs">
                                    {{ $pengaduan->student?->guardian_phone ?? 'Belum terdata' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Peristiwa / Kejadian -->
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-1.5 rounded-3 bg-teal-subtle text-mint d-inline-flex">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Rincian Peristiwa & Kronologi Kejadian</span>
                    </div>
                </div>

                <div class="card-body p-4 space-y-4">
                    <!-- Meta Grid Kejadian -->
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light-subtle border h-100">
                                <span class="text-muted fs-xs d-block mb-1">
                                    <i class="bi bi-calendar-event me-1 text-mint"></i> Perkiraan Waktu Kejadian
                                </span>
                                <div class="fw-bold text-dark fs-sm">
                                    {{ $pengaduan->incident_date ? $pengaduan->incident_date->translatedFormat('d F Y') : 'Tidak ditentukan spesifik' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3 bg-light-subtle border h-100">
                                <span class="text-muted fs-xs d-block mb-1">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i> Tempat / Lokasi Kejadian
                                </span>
                                <div class="fw-bold text-dark fs-sm">
                                    {{ $pengaduan->incident_location ?? 'Lingkungan Sekolah' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uraian Kronologi Box -->
                    <div>
                        <label class="text-muted fs-xs fw-bold text-uppercase d-block mb-2">
                            <i class="bi bi-chat-quote me-1 text-mint"></i> Uraian / Kronologi yang Dilaporkan Siswa
                        </label>
                        <div class="p-3.5 rounded-3 border text-dark fs-sm" 
                             style="background-color: #f8fafc; border-left: 4px solid var(--primary-teal, #0d9488) !important; white-space: pre-line; line-height: 1.7;">
                            {{ $pengaduan->description }}
                        </div>
                    </div>

                    <!-- Waktu Pengaduan & Riwayat Notifikasi -->
                    <div class="pt-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2 text-muted fs-xs">
                        <div>
                            <i class="bi bi-clock me-1"></i> Dikirim: 
                            <strong class="text-dark">{{ $pengaduan->created_at->translatedFormat('d M Y, H:i') }} WIB</strong>
                        </div>
                        @if($pengaduan->read_at)
                            <div>
                                <i class="bi bi-check2-all text-mint me-1"></i> Dibaca konselor: 
                                <span class="text-dark">{{ $pengaduan->read_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Formulir Tindak Lanjut Konselor & Panduan -->
        <div class="col-lg-5 space-y-4">

            <!-- Card: Formulir Penanganan Konselor -->
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-1.5 rounded-3 bg-teal-subtle text-mint d-inline-flex">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                        <span class="fw-extrabold text-dark" style="font-size: 0.95rem;">Formulir Penanganan Konselor</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('panel.pengaduan.status', $pengaduan) }}">
                        @csrf
                        @method('PUT')

                        <!-- Status Penanganan -->
                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-bold text-uppercase">Status Penanganan Kasus <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm rounded-3 py-2 fw-semibold" required>
                                <option value="pending" {{ old('status', $pengaduan->status) === 'pending' ? 'selected' : '' }}>
                                    Menunggu Verifikasi
                                </option>
                                <option value="investigating" {{ old('status', $pengaduan->status) === 'investigating' ? 'selected' : '' }}>
                                    Sedang Ditindaklanjuti / Investigasi
                                </option>
                                <option value="resolved" {{ old('status', $pengaduan->status) === 'resolved' ? 'selected' : '' }}>
                                    Selesai Ditangani
                                </option>
                                <option value="closed" {{ old('status', $pengaduan->status) === 'closed' ? 'selected' : '' }}>
                                    Ditutup
                                </option>
                            </select>
                        </div>

                        <!-- Konselor Penanggung Jawab -->
                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-bold text-uppercase">Konselor Penanggung Jawab</label>
                            <select name="handled_by_counselor_id" class="form-select form-select-sm rounded-3 py-2">
                                <option value="">-- Pilih Guru BK / Konselor --</option>
                                @foreach($counselors as $c)
                                    <option value="{{ $c->id }}" {{ old('handled_by_counselor_id', $pengaduan->handled_by_counselor_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sakelar Risiko Keselamatan Anak -->
                        <div class="p-3 rounded-3 mb-3" style="background-color: #fff1f2; border: 1.5px solid #fecdd3;">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="safety_risk_flag" value="1" id="safetyRiskSwitch" {{ old('safety_risk_flag', $pengaduan->safety_risk_flag) ? 'checked' : '' }}>
                                <label class="form-check-label fs-xs text-danger fw-extrabold" for="safetyRiskSwitch">
                                    Tandai Ada Risiko Keselamatan Anak
                                </label>
                            </div>
                            <span class="text-muted fs-xs d-block" style="line-height: 1.4;">
                                Aktifkan jika membutuhkan eskalasi cepat dan perlindungan fisik/mental anak dari ancaman berulang.
                            </span>
                        </div>

                        <!-- Catatan Tindak Lanjut & Investigasi -->
                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-bold text-uppercase">Catatan Tindak Lanjut & Investigasi <span class="text-danger">*</span></label>
                            <textarea name="handling_notes" 
                                      class="form-control rounded-3 fs-sm" 
                                      rows="5" 
                                      placeholder="Tuliskan tindakan yang telah diambil, hasil wawancara tertutup, klarifikasi saksi, serta langkah perlindungan siswa..." 
                                      required>{{ old('handling_notes', $pengaduan->handling_notes) }}</textarea>
                            <div class="form-text fs-xs text-muted">Wajib diisi sebagai rekam jejak formal penanganan kasus anak di sekolah.</div>
                        </div>

                        <button type="submit" class="btn btn-mint w-100 rounded-pill py-2.5 fw-bold shadow-xs">
                            <i class="bi bi-save me-1.5"></i> Simpan Catatan Penanganan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Petunjuk SOP Konselor Card -->
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <span class="fw-bold text-dark fs-sm">
                        <i class="bi bi-info-circle text-primary me-1.5"></i> SOP Penanganan Pengaduan
                    </span>
                </div>
                <div class="card-body p-4 fs-xs text-muted space-y-2.5" style="line-height: 1.5;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-shield-check text-mint fs-6 mt-0.5"></i>
                        <div><strong>Kerahasiaan Tertutup:</strong> Jangan memanggil siswa di depan teman sebayanya agar terhindar dari intimidasi susulan.</div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-people text-primary fs-6 mt-0.5"></i>
                        <div><strong>Koordinasi Terbatas:</strong> Libatkan wali kelas hanya jika diperlukan untuk observasi perilaku siswa di kelas.</div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-calendar2-heart text-danger fs-6 mt-0.5"></i>
                        <div><strong>Konseling Lanjutan:</strong> Gunakan tombol <em>Buka Sesi Konseling Murid</em> di atas untuk menjadwalkan konseling individu resmi.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
