@extends('layouts.student')

@section('title', 'Layanan Pengaduan Aman Siswa')
@section('page_title', 'Pengaduan Aman')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item active">Pengaduan Aman</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-xs fw-semibold rounded-pill">Pengaduan Aman</span>
                <span class="badge bg-light text-dark border px-2 py-1 fs-xs fw-medium rounded-pill">Perlindungan Keselamatan Anak</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Pengaduan & Perlindungan Diri</h1>
            <p class="text-muted small mb-0">Laporkan kejadian perundungan, kekerasan, pelecehan, atau ancaman keselamatan dengan aman dan rahasia.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('murid.pengaduan.create') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                <i class="bi bi-shield-plus me-1"></i> Buat Laporan Baru
            </a>
        </div>
    </div>

    <!-- Complaint List -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark fs-sm">Riwayat Laporan Saya</span>
            <span class="badge bg-light text-muted border">Total {{ $complaints->total() }} Laporan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted">
                    <tr>
                        <th style="width: 140px;">No. Tiket</th>
                        <th>Kategori & Masalah</th>
                        <th style="width: 140px;">Waktu & Tempat</th>
                        <th style="width: 130px;" class="text-center">Sifat Laporan</th>
                        <th style="width: 160px;" class="text-center">Status Laporan</th>
                        <th style="width: 100px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($complaints as $item)
                        <tr>
                            <td>
                                <span class="fw-bold font-monospace text-danger fs-xs">{{ $item->ticket_number }}</span>
                                <div class="text-muted fs-xs">{{ $item->created_at->translatedFormat('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->category }}</div>
                                <div class="text-muted fs-xs text-truncate" style="max-width: 320px;">{{ $item->description }}</div>
                            </td>
                            <td>
                                <div class="text-dark fs-xs">{{ $item->incident_date ? $item->incident_date->translatedFormat('d M Y') : 'Waktu Fleksibel' }}</div>
                                <div class="text-muted fs-xs">{{ $item->incident_location ?? 'Sekolah' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill fs-xs">
                                    <i class="bi bi-person-check me-1"></i> Terverifikasi
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item->status_badge_class }} px-2 py-1 rounded-pill fs-xs">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('murid.pengaduan.show', $item->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fs-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check fs-1 d-block mb-2 text-success"></i>
                                Kamu belum memiliki laporan pengaduan yang aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($complaints->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
