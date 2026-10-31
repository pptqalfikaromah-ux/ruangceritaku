@extends('layouts.walikelas')

@section('title', 'Aduan Kasus Murid ke BK')
@section('page_title', 'Aduan Kasus Murid ke Guru BK')
@section('page_subtitle', 'Laporan kasus insidental untuk ditindaklanjuti konselor sekolah')

@section('content')
<div class="card card-app">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-flag-fill text-danger me-1.5"></i> Riwayat Aduan & Laporan Kasus Siswa Kelas {{ $classroom?->name ?? '' }}
            </h6>
            <div class="text-muted fs-xs">Wali kelas dapat melaporkan kejadian insidental murid dan memberikan catatan agar Guru BK segera menindaklanjuti.</div>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
            <a href="{{ route('walikelas.aduan.create') }}" class="btn btn-warning text-dark btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-sm w-100 w-md-auto text-center">
                <i class="bi bi-plus-circle-fill me-1"></i> Buat Laporan Kasus Baru
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        {{-- DESKTOP TABLE VIEW --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kategori Kasus</th>
                        <th>Judul Kejadian</th>
                        <th>Status Tindak Lanjut</th>
                        <th>Respon Guru BK</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $index => $comp)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $complaints->firstItem() + $index }}</td>
                            <td class="small fw-semibold text-muted">{{ $comp->incident_date->format('d M Y') }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $comp->student?->name }}</div>
                                <div class="text-muted fs-xs">NISN: {{ $comp->student?->nisn }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                    {{ $comp->category_label }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $comp->title }}</div>
                                <div class="text-muted fs-xxs">{{ Str::limit($comp->description, 45) }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $comp->status_badge_class }} px-2.5 py-1">
                                    {{ $comp->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($comp->counselor_response)
                                    <div class="small text-success fw-semibold text-truncate" style="max-width: 180px;">
                                        <i class="bi bi-check2-circle me-1"></i>{{ Str::limit($comp->counselor_response, 40) }}
                                    </div>
                                    <div class="text-muted fs-xxs">Oleh: {{ $comp->handledByCounselor?->name ?? 'Guru BK' }}</div>
                                @else
                                    <span class="text-muted fs-xs fst-italic">Belum ada respon</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('walikelas.aduan.show', $comp) }}" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 fw-bold text-muted">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check fs-2 d-block mb-2 text-success opacity-50"></i>
                                Belum ada laporan kasus anak yang diajukan. Lingkungan kelas terpantau aman dan kondusif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS VIEW (Spacious & Clean Mobile UI) --}}
        <div class="d-md-none p-3 p-sm-3.5">
            @forelse($complaints as $comp)
                <div class="mobile-card mb-3.5 shadow-xs">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2.5 pb-2.5 border-bottom">
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fs-xs fw-semibold">
                            <i class="bi bi-tag me-1 text-muted"></i>{{ $comp->category_label }}
                        </span>
                        <span class="badge rounded-pill {{ $comp->status_badge_class }} px-3 py-1.5 fs-xs fw-bold">
                            {{ $comp->status_label }}
                        </span>
                    </div>

                    <div class="card-name mb-1.5 fs-6 fw-bold text-dark">{{ $comp->title }}</div>
                    
                    <div class="card-sub mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark"><i class="bi bi-person-fill text-primary me-1"></i>{{ $comp->student?->name }}</span>
                            <span class="text-muted fs-xs">NISN: {{ $comp->student?->nisn }}</span>
                        </div>
                        <div class="text-muted fs-xs mt-1.5">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>Kejadian: <strong class="text-dark">{{ $comp->incident_date->format('d M Y') }}</strong>
                        </div>
                    </div>

                    <div class="card-info-box mb-3 fs-xs p-3 rounded-4 bg-light border">
                        <div class="fw-bold text-muted fs-xxs text-uppercase mb-1.5">Kronologi Singkat:</div>
                        <div class="text-dark lh-base">{{ Str::limit($comp->description, 130) }}</div>
                    </div>

                    @if($comp->counselor_response)
                        <div class="p-3.5 rounded-4 mb-3 border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                            <div class="d-flex align-items-center gap-1.5 fs-xs fw-bold text-success mb-1.5">
                                <i class="bi bi-check-circle-fill"></i> Respon & Solusi Guru BK:
                            </div>
                            <div class="fs-xs text-dark lh-base">{{ Str::limit($comp->counselor_response, 110) }}</div>
                            <div class="fs-xxs text-muted mt-1.5">Ditangani oleh: <strong>{{ $comp->handledByCounselor?->name ?? 'Guru BK' }}</strong></div>
                        </div>
                    @endif

                    <div class="pt-2.5 border-top">
                        <a href="{{ route('walikelas.aduan.show', $comp) }}" class="btn btn-card-action w-100 text-center py-2.5 rounded-pill">
                            <i class="bi bi-eye me-1.5 text-warning"></i> Lihat Rincian Kasus
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-shield-check fs-1 d-block mb-2 text-success opacity-50"></i>
                    Belum ada laporan kasus anak yang diajukan. Lingkungan kelas terpantau aman dan kondusif.
                </div>
            @endforelse
        </div>
    </div>

    @if($complaints->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection
