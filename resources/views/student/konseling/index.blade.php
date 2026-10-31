@extends('layouts.student')

@section('title', 'Layanan Konseling Murid')
@section('page_title', 'Konseling Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item active">Konseling Siswa</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Konseling Siswa</span>
                <span class="badge bg-purple-subtle text-purple border-purple px-2 py-1 fs-xs fw-medium rounded-pill">Layanan Terpadu</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Agenda Konseling Saya</h1>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh sesi bimbinganmu, baik yang diajukan secara pribadi maupun tindak lanjut dari Instrumen Masalah Siswa (IMS).</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <!-- 1. Tombol Konseling Pribadi (Mandiri) -->
            <a href="{{ route('murid.konseling.create') }}" class="btn btn-emerald btn-sm rounded-pill px-3.5 py-2 fw-semibold shadow-sm">
                <i class="bi bi-person-heart me-1"></i> + Konseling Pribadi (Mandiri)
            </a>

            @if(app_setting('ims_enabled', '1') === '1')
                <!-- 2. Tombol Jadwal Tindak Lanjut IMS -->
                <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-outline-purple btn-sm rounded-pill px-3.5 py-2 fw-semibold shadow-sm" style="border-color: #7c3aed; color: #7c3aed;">
                    <i class="bi bi-clipboard2-check me-1"></i> Ajukan dari Masalah IMS
                </a>
            @endif
        </div>
    </div>

    @if(app_setting('ims_enabled', '1') === '1')
        <!-- Filter Tab: Bedakan Konseling Pribadi vs Tindak Lanjut IMS (Scrollable di Mobile) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-2.5">
            <ul class="nav nav-pills gap-2 flex-nowrap overflow-x-auto pb-1 pb-sm-0" style="-webkit-overflow-scrolling: touch;">
                <li class="nav-item flex-shrink-0">
                    <a class="nav-link rounded-pill fs-xs py-2 px-3.5 fw-semibold {{ !request('source') ? 'active bg-emerald text-white' : 'text-muted' }}" 
                       href="{{ route('murid.konseling.index') }}">
                        <i class="bi bi-grid me-1.5"></i> Semua Permohonan
                        <span class="badge bg-light text-dark rounded-pill ms-1.5 px-2 py-0.5">{{ $counts['all'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item flex-shrink-0">
                    <a class="nav-link rounded-pill fs-xs py-2 px-3.5 fw-semibold {{ request('source') === 'pribadi' ? 'active bg-primary text-white' : 'text-muted' }}" 
                       href="{{ route('murid.konseling.index', ['source' => 'pribadi']) }}">
                        <i class="bi bi-person-fill me-1.5"></i> Konseling Pribadi (Mandiri)
                        <span class="badge bg-light text-dark rounded-pill ms-1.5 px-2 py-0.5">{{ $counts['pribadi'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item flex-shrink-0">
                    <a class="nav-link rounded-pill fs-xs py-2 px-3.5 fw-semibold {{ request('source') === 'ims' ? 'active bg-purple text-white' : 'text-muted' }}" 
                       style="{{ request('source') === 'ims' ? 'background-color: #7c3aed !important;' : '' }}"
                       href="{{ route('murid.konseling.index', ['source' => 'ims']) }}">
                        <i class="bi bi-clipboard2-check me-1.5"></i> Tindak Lanjut Masalah IMS
                        <span class="badge bg-light text-dark rounded-pill ms-1.5 px-2 py-0.5">{{ $counts['ims'] ?? 0 }}</span>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    <!-- Table / Card List of Requests -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark fs-sm">Daftar Tiket Permohonan Konseling</span>
            <span class="badge bg-light text-muted border">Total {{ $requests->total() }} Tiket</span>
        </div>
        
        {{-- DESKTOP TABLE VIEW --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted">
                    <tr>
                        <th style="width: 130px;">No. Tiket</th>
                        <th style="width: 140px;">Tipe / Sumber</th>
                        <th>Topik / Kebutuhan Konseling</th>
                        <th style="width: 130px;">Bidang BK</th>
                        <th style="width: 140px;">Waktu Diajukan</th>
                        <th style="width: 150px;" class="text-center">Status</th>
                        <th style="width: 150px;" class="text-center">Jadwal Sesi</th>
                        <th style="width: 110px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($requests as $item)
                        <tr>
                            <td>
                                <span class="fw-bold font-monospace text-emerald fs-xs">{{ $item->ticket_number }}</span>
                            </td>
                            <td>
                                @if($item->source === 'ims')
                                    <span class="badge bg-purple-subtle text-purple border-purple px-2 py-1 rounded-pill fs-xs fw-semibold">
                                        <i class="bi bi-clipboard2-check me-1"></i>Hasil IMS
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border-primary px-2 py-1 rounded-pill fs-xs fw-semibold">
                                        <i class="bi bi-person me-1"></i>Pribadi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->purpose }}</div>
                                <div class="text-muted fs-xs text-truncate" style="max-width: 280px;">{{ $item->description }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill fs-xs">
                                    {{ $item->guidanceField?->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark fs-xs">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item->status_badge_class }} px-2 py-1 rounded-pill fs-xs">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->schedule)
                                    <div class="badge bg-emerald-subtle text-emerald fs-xs">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        {{ $item->schedule->scheduled_date->format('d/m') }}, {{ $item->schedule->start_time }}
                                    </div>
                                    @if($item->schedule->student_confirmation === 'confirmed')
                                        <div class="fs-xs text-success fw-bold mt-1"><i class="bi bi-check2"></i> Siap Hadir</div>
                                    @elseif($item->schedule->student_confirmation === 'reschedule_requested')
                                        <div class="fs-xs text-warning fw-bold mt-1">Minta Reschedule</div>
                                    @else
                                        <div class="fs-xs text-primary fw-semibold mt-1">Perlu Konfirmasi</div>
                                    @endif
                                @else
                                    <span class="text-muted fs-xs fst-italic">Menunggu Penetapan</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('murid.konseling.show', $item->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5 fs-xs rounded-pill">
                                    Lihat Tiket
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-heart fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada permohonan konseling pada kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS VIEW (Spacious & Clean Mobile UI) --}}
        <div class="d-md-none p-3 p-sm-3.5">
            @forelse($requests as $item)
                <div class="mobile-card mb-3.5 shadow-xs">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2.5 pb-2.5 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold font-monospace text-emerald fs-xs p-1 bg-light rounded-3">
                                <i class="bi bi-ticket-perforated me-1"></i>#{{ $item->ticket_number }}
                            </span>
                            @if($item->source === 'ims')
                                <span class="badge bg-purple-subtle text-purple border-purple px-2.5 py-1 rounded-pill fs-xxs fw-semibold">
                                    <i class="bi bi-clipboard2-check me-1"></i>Hasil IMS
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border-primary px-2.5 py-1 rounded-pill fs-xxs fw-semibold">
                                    <i class="bi bi-person me-1"></i>Pribadi
                                </span>
                            @endif
                        </div>
                        <span class="badge {{ $item->status_badge_class }} px-3 py-1.5 rounded-pill fs-xs fw-bold">
                            {{ $item->status_label }}
                        </span>
                    </div>

                    <div class="card-name mb-1.5 fs-6 fw-bold text-dark">{{ $item->purpose }}</div>

                    <div class="card-sub mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-light text-dark border px-2.5 py-1 rounded-pill fs-xxs">
                                {{ $item->guidanceField?->name ?? 'Umum' }}
                            </span>
                            <span class="text-muted fs-xs">&bull;</span>
                            <span class="text-muted fs-xs">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    @if($item->description)
                        <div class="card-info-box mb-3 fs-xs p-3 rounded-4 bg-light border">
                            <div class="fw-bold text-muted fs-xxs text-uppercase mb-1.5">Catatan Kebutuhan:</div>
                            <div class="text-dark lh-base">{{ Str::limit($item->description, 130) }}</div>
                        </div>
                    @endif

                    {{-- JADWAL SESI KONSELING --}}
                    <div class="p-3.5 rounded-4 mb-3 border" style="background: #f0fdfa; border-color: #ccfbf1 !important;">
                        <div class="fw-bold text-muted fs-xxs text-uppercase mb-1.5">Jadwal Sesi Konseling:</div>
                        @if($item->schedule)
                            <div class="d-flex align-items-center gap-2 fs-xs text-dark fw-bold mb-2">
                                <i class="bi bi-calendar-check text-emerald fs-6"></i>
                                <span>{{ $item->schedule->scheduled_date->translatedFormat('l, d M Y') }} &bull; {{ $item->schedule->start_time }} WIB</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 fs-xs">
                                <span class="text-muted fs-xxs">Tempat: <strong>{{ $item->schedule->location ?? 'Ruang BK' }}</strong></span>
                                @if($item->schedule->student_confirmation === 'confirmed')
                                    <span class="badge bg-success text-white rounded-pill fs-xxs px-2.5 py-1"><i class="bi bi-check2"></i> Siap Hadir</span>
                                @elseif($item->schedule->student_confirmation === 'reschedule_requested')
                                    <span class="badge bg-warning text-dark rounded-pill fs-xxs px-2.5 py-1">Minta Jadwal Lain</span>
                                @else
                                    <span class="badge bg-primary text-white rounded-pill fs-xxs px-2.5 py-1">Perlu Konfirmasi</span>
                                @endif
                            </div>
                        @else
                            <div class="text-muted fs-xs fst-italic py-1">
                                <i class="bi bi-hourglass-split me-1 text-warning"></i>Jadwal sedang ditinjau & ditetapkan oleh Guru BK.
                            </div>
                        @endif
                    </div>

                    <div class="pt-2.5 border-top">
                        <a href="{{ route('murid.konseling.show', $item->id) }}" class="btn btn-card-action w-100 text-center py-2.5 rounded-pill">
                            <i class="bi bi-eye me-1.5 text-emerald"></i> Lihat Rincian Tiket & Jadwal
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-heart fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada permohonan konseling pada kategori ini.
                </div>
            @endforelse
        </div>

        @if($requests->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
