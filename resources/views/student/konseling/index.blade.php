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

            <!-- 2. Tombol Jadwal Tindak Lanjut IMS -->
            <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-outline-purple btn-sm rounded-pill px-3.5 py-2 fw-semibold shadow-sm" style="border-color: #7c3aed; color: #7c3aed;">
                <i class="bi bi-clipboard2-check me-1"></i> Ajukan dari Masalah IMS
            </a>
        </div>
    </div>

    <!-- Filter Tab: Bedakan Konseling Pribadi vs Tindak Lanjut IMS -->
    <div class="card border-0 shadow-sm rounded-3 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link rounded-pill fs-sm py-1.5 px-3 {{ !request('source') ? 'active bg-emerald text-white' : 'text-muted' }}" 
                   href="{{ route('murid.konseling.index') }}">
                    <i class="bi bi-grid me-1"></i> Semua Permohonan
                    <span class="badge bg-light text-dark rounded-pill ms-1">{{ $counts['all'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill fs-sm py-1.5 px-3 {{ request('source') === 'pribadi' ? 'active bg-primary text-white' : 'text-muted' }}" 
                   href="{{ route('murid.konseling.index', ['source' => 'pribadi']) }}">
                    <i class="bi bi-person-fill me-1"></i> Konseling Pribadi (Mandiri)
                    <span class="badge bg-light text-dark rounded-pill ms-1">{{ $counts['pribadi'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill fs-sm py-1.5 px-3 {{ request('source') === 'ims' ? 'active bg-purple text-white' : 'text-muted' }}" 
                   style="{{ request('source') === 'ims' ? 'background-color: #7c3aed !important;' : '' }}"
                   href="{{ route('murid.konseling.index', ['source' => 'ims']) }}">
                    <i class="bi bi-clipboard2-check me-1"></i> Tindak Lanjut Masalah IMS
                    <span class="badge bg-light text-dark rounded-pill ms-1">{{ $counts['ims'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Table / Card List of Requests -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark fs-sm">Daftar Tiket Permohonan Konseling</span>
            <span class="badge bg-light text-muted border">Total {{ $requests->total() }} Tiket</span>
        </div>
        <div class="table-responsive">
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
        @if($requests->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
