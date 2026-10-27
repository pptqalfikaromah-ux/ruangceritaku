@extends('layouts.panel')

@section('title', 'Aduan Kasus dari Wali Kelas')
@section('breadcrumb')
    <span class="current">Aduan Kasus Wali Kelas</span>
@endsection

@section('content')
<div class="card card-app">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-megaphone-fill text-danger me-1.5"></i> Laporan & Aduan Kasus dari Wali Kelas
            </h5>
            <div class="text-muted fs-xs">Daftar kasus insidental murid yang dilaporkan oleh Wali Kelas agar segera ditindaklanjuti oleh Tim Guru BK.</div>
        </div>

        <form method="GET" class="d-flex align-items-center gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="menunggu_tindak_lanjut" {{ request('status') === 'menunggu_tindak_lanjut' ? 'selected' : '' }}>Menunggu Tindak Lanjut</option>
                <option value="sedang_ditangani" {{ request('status') === 'sedang_ditangani' ? 'selected' : '' }}>Sedang Ditangani</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kasus, murid, atau wali kelas..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">Cari</button>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Tanggal</th>
                        <th>Wali Kelas Pelapor</th>
                        <th>Murid Terkait</th>
                        <th>Rombel</th>
                        <th>Kategori Kasus</th>
                        <th>Pokok Masalah</th>
                        <th class="text-center">Status Tindak Lanjut</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $index => $comp)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $complaints->firstItem() + $index }}</td>
                            <td class="small fw-semibold text-muted">{{ $comp->incident_date->format('d M Y') }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $comp->teacher?->name }}</div>
                                <div class="text-muted fs-xxs">NIP: {{ $comp->teacher?->nip_nuptk ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $comp->student?->name }}</div>
                                <div class="text-muted fs-xs">NISN: {{ $comp->student?->nisn }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                    Kelas {{ $comp->classroom?->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                    {{ $comp->category_label }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $comp->title }}</div>
                                <div class="text-muted fs-xxs">{{ Str::limit($comp->teacher_notes, 45) }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $comp->status_badge_class }} px-2.5 py-1">
                                    {{ $comp->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('panel.aduan-walikelas.show', $comp) }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold">
                                    <i class="bi bi-reply-fill me-1"></i> Respon
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada laporan kasus murid dari Wali Kelas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($complaints->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection
