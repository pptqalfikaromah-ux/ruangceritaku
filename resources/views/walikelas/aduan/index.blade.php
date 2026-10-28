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

        {{-- MOBILE CARDS VIEW --}}
        <div class="d-md-none p-3">
            @forelse($complaints as $comp)
                <div class="card border rounded-3 p-3 mb-2.5 shadow-xs bg-white">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2">
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 fs-xxs">
                            {{ $comp->category_label }}
                        </span>
                        <span class="badge rounded-pill {{ $comp->status_badge_class }} px-2.5 py-1 fs-xxs">
                            {{ $comp->status_label }}
                        </span>
                    </div>

                    <div class="fw-bold text-dark fs-sm mb-0.5">{{ $comp->title }}</div>
                    <div class="text-muted fs-xs mb-2">
                        <i class="bi bi-person me-1 text-primary"></i><strong>{{ $comp->student?->name }}</strong> (NISN: {{ $comp->student?->nisn }})
                        &bull; <i class="bi bi-calendar-event me-0.5"></i>{{ $comp->incident_date->format('d M Y') }}
                    </div>

                    <div class="small text-muted bg-light p-2 rounded-2 mb-2 fs-xs">
                        {{ Str::limit($comp->description, 90) }}
                    </div>

                    @if($comp->counselor_response)
                        <div class="alert alert-success py-1.5 px-2.5 rounded-2 fs-xxs mb-2 d-flex align-items-center gap-1.5">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <div><strong>Respon BK:</strong> {{ Str::limit($comp->counselor_response, 50) }}</div>
                        </div>
                    @endif

                    <div class="pt-2 border-top">
                        <a href="{{ route('walikelas.aduan.show', $comp) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-bold fs-xs">
                            <i class="bi bi-eye me-1"></i> Lihat Rincian Kasus
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
