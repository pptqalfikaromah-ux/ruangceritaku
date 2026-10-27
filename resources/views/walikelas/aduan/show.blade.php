@extends('layouts.walikelas')

@section('title', 'Detail Kasus Murid')
@section('page_title', 'Detail Laporan Kasus Siswa')
@section('page_subtitle', $aduan->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('walikelas.aduan.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Aduan
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 mb-1">
                        {{ $aduan->category_label }}
                    </span>
                    <h5 class="fw-bold text-dark mb-0">{{ $aduan->title }}</h5>
                </div>
                <span class="badge {{ $aduan->status_badge_class }} rounded-pill px-3 py-1.5 fw-bold">
                    {{ $aduan->status_label }}
                </span>
            </div>

            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="text-muted fs-xs fw-bold text-uppercase">Kronologi & Deskripsi Kejadian</label>
                    <div class="p-3 rounded-3 bg-light border text-dark mt-1" style="white-space: pre-line;">
                        {{ $aduan->description }}
                    </div>
                </div>

                @if($aduan->initial_action)
                    <div class="mb-4">
                        <label class="text-muted fs-xs fw-bold text-uppercase">Tindakan Awal yang Telah Diupayakan Wali Kelas</label>
                        <div class="p-3 rounded-3 bg-light border text-dark mt-1" style="white-space: pre-line;">
                            {{ $aduan->initial_action }}
                        </div>
                    </div>
                @endif

                <div class="p-3 rounded-3 mb-4" style="background: #fffbeb; border: 1.5px solid #fef08a;">
                    <label class="fw-bold text-warning-emphasis fs-xs text-uppercase mb-1">
                        <i class="bi bi-chat-quote-fill me-1"></i> Catatan & Rekomendasi Anda untuk Admin / Guru BK
                    </label>
                    <div class="text-dark fw-semibold mt-1" style="white-space: pre-line;">
                        {{ $aduan->teacher_notes }}
                    </div>
                </div>

                {{-- RESPON / TINDAK LANJUT GURU BK --}}
                <div class="p-3.5 rounded-3 border" style="background: #f0fdfa; border-color: #99f6e4 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-check-circle-fill text-mint me-1.5"></i> Respon & Tindak Lanjut Guru BK
                        </h6>
                        @if($aduan->handled_at)
                            <span class="text-muted fs-xs">{{ $aduan->handled_at->format('d M Y, H:i') }}</span>
                        @endif
                    </div>

                    @if($aduan->counselor_response)
                        <div class="p-3 rounded-3 bg-white border text-dark mb-2" style="white-space: pre-line;">
                            {{ $aduan->counselor_response }}
                        </div>
                        <div class="small text-muted">
                            <i class="bi bi-person-badge me-1 text-mint"></i> Ditangani oleh: <strong>{{ $aduan->handledByCounselor?->name ?? 'Konselor BK' }}</strong>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 rounded-3 small mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-hourglass-split fs-5"></i>
                            <div>
                                Laporan ini telah diterima sistem dan sedang dalam antrean peninjauan oleh tim Guru BK madrasah.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-app mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle me-1.5 text-warning"></i> Informasi Kasus</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <div class="text-muted fs-xs">Nama Murid:</div>
                    <div class="fw-bold text-dark fs-6">{{ $aduan->student?->name }}</div>
                    <div class="text-muted fs-xs">NISN: {{ $aduan->student?->nisn }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted fs-xs">Rombel Kelas:</div>
                    <div class="fw-bold text-dark">Kelas {{ $aduan->classroom?->name }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted fs-xs">Tanggal Kejadian:</div>
                    <div class="fw-bold text-dark">{{ $aduan->incident_date->format('d F Y') }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted fs-xs">Tanggal Lapor:</div>
                    <div class="fw-bold text-dark">{{ $aduan->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="mb-0">
                    <div class="text-muted fs-xs">Wali Kelas Pelapor:</div>
                    <div class="fw-bold text-dark">{{ $aduan->teacher?->name }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
