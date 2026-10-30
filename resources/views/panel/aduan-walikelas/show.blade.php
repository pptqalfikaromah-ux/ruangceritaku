@extends('layouts.panel')

@section('title', 'Tindak Lanjut Aduan Wali Kelas')
@section('breadcrumb')
    <a href="{{ route('panel.aduan-walikelas.index') }}">Aduan Kasus Wali Kelas</a>
    <span class="divider">/</span>
    <span class="current">Tindak Lanjut</span>
@endsection

@section('content')
<div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <a href="{{ route('panel.aduan-walikelas.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Aduan Wali Kelas
    </a>
    @if($complaint->status === 'selesai')
        <a href="{{ route('panel.aduan-walikelas.printReport', $complaint) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-xs">
            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan Arsip PDF
        </a>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 mb-1">
                        {{ $complaint->category_label }}
                    </span>
                    <h5 class="fw-bold text-dark mb-0">{{ $complaint->title }}</h5>
                </div>
                <span class="badge {{ $complaint->status_badge_class }} rounded-pill px-3 py-1.5 fw-bold">
                    {{ $complaint->status_label }}
                </span>
            </div>

            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="text-muted fs-xs fw-bold text-uppercase">Kronologi & Deskripsi Kejadian</label>
                    <div class="p-3 rounded-3 bg-light border text-dark mt-1" style="white-space: pre-line;">
                        {{ $complaint->description }}
                    </div>
                </div>

                @if($complaint->initial_action)
                    <div class="mb-4">
                        <label class="text-muted fs-xs fw-bold text-uppercase">Tindakan Awal yang Sudah Dilakukan Wali Kelas</label>
                        <div class="p-3 rounded-3 bg-light border text-dark mt-1" style="white-space: pre-line;">
                            {{ $complaint->initial_action }}
                        </div>
                    </div>
                @endif

                @if($complaint->teacher_notes)
                    <div class="p-3 rounded-3 mb-4" style="background: #fffbeb; border: 1.5px solid #fef08a;">
                        <label class="fw-bold text-warning-emphasis fs-xs text-uppercase mb-1">
                            <i class="bi bi-chat-quote-fill me-1"></i> Catatan & Rekomendasi Khusus Wali Kelas untuk Guru BK
                        </label>
                        <div class="text-dark fw-bold mt-1" style="white-space: pre-line;">
                            {{ $complaint->teacher_notes }}
                        </div>
                    </div>
                @endif

                {{-- FORM RESPON / TINDAK LANJUT GURU BK --}}
                <div class="card border-0 rounded-3 shadow-xs p-3" style="background: #f0fdfa; border: 1.5px solid #99f6e4 !important;">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-reply-fill text-mint me-1.5"></i> Formulir Tindak Lanjut Guru BK
                    </h6>

                    <form action="{{ route('panel.aduan-walikelas.update', $complaint) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Status Tindak Lanjut Kasus <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="menunggu_tindak_lanjut" {{ old('status', $complaint->status) === 'menunggu_tindak_lanjut' ? 'selected' : '' }}>Menunggu Tindak Lanjut</option>
                                <option value="sedang_ditangani" {{ old('status', $complaint->status) === 'sedang_ditangani' ? 'selected' : '' }}>Sedang Ditangani Guru BK</option>
                                <option value="selesai" {{ old('status', $complaint->status) === 'selesai' ? 'selected' : '' }}>Selesai Ditindaklanjuti</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Catatan Respon & Langkah Penanganan Guru BK <span class="text-danger">*</span></label>
                            <textarea name="counselor_response" rows="4" class="form-control @error('counselor_response') is-invalid @enderror" placeholder="Tuliskan tindakan yang telah/akan dilakukan (misal: telah dipanggil konseling individu pada jam ke-4, rencana pemanggilan orang tua, dst)..." required>{{ old('counselor_response', $complaint->counselor_response) }}</textarea>
                            <div class="form-text fs-xs text-muted">Catatan respon ini dapat dibaca oleh Wali Kelas pelapor pada portal wali kelas.</div>
                            @error('counselor_response') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-check2-circle me-1.5"></i> Simpan Tindak Lanjut Kasus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle text-primary me-1.5"></i> Informasi Siswa & Pelapor</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="text-muted fs-xs">Nama Murid Terkait:</div>
                    <div class="fw-bold text-dark fs-6">{{ $complaint->student?->name }}</div>
                    <div class="text-muted fs-xs">NISN: {{ $complaint->student?->nisn }} &bull; Kelas {{ $complaint->classroom?->name }}</div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="text-muted fs-xs">Wali Kelas Pelapor:</div>
                    <div class="fw-bold text-dark">{{ $complaint->teacher?->name }}</div>
                    <div class="text-muted fs-xs">NIP: {{ $complaint->teacher?->nip_nuptk ?? '-' }} &bull; Kontak: {{ $complaint->teacher?->phone ?? '-' }}</div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="text-muted fs-xs">Tanggal Kejadian:</div>
                    <div class="fw-bold text-dark">{{ $complaint->incident_date->format('d F Y') }}</div>
                </div>

                <div class="mb-0">
                    <div class="text-muted fs-xs">Status Penanganan:</div>
                    <span class="badge {{ $complaint->status_badge_class }} rounded-pill px-3 py-1 mt-1">
                        {{ $complaint->status_label }}
                    </span>
                    @if($complaint->handledByCounselor)
                        <div class="text-muted fs-xs mt-2">
                            Ditangani: <strong>{{ $complaint->handledByCounselor->name }}</strong>
                            @if($complaint->handled_at)
                                ({{ $complaint->handled_at->format('d/m/Y H:i') }})
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
