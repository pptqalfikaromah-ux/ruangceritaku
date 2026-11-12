@extends('layouts.panel')

@section('title', 'Layanan Kelompok (BKP & KKP)')
@section('breadcrumb')
    <span class="current">Layanan Kelompok</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-4 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Bimbingan Kelompok (BKP)
                            </span>
                            <span class="badge bg-purple-subtle text-purple px-2.5 py-1 fs-xxs fw-semibold rounded-pill">
                                Konseling Kelompok (KKP)
                            </span>
                        </div>
                        <h1 class="h4 fw-extrabold text-dark mb-0.5">Bimbingan & Konseling Kelompok</h1>
                        <p class="text-muted fs-xs mb-0">Kelola dan jadwalkan sesi bimbingan bersama serta konseling dinamika kelompok yang diajukan oleh siswa.</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-xs">
                        Total Sesi: <strong class="text-mint">{{ $counters['total'] }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.layanan-kelompok.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 fs-xs" placeholder="Cari No. Tiket, Topik, atau Siswa Pemohon...">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="type" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">Semua Jenis Layanan</option>
                        <option value="bimbingan_kelompok" {{ request('type') === 'bimbingan_kelompok' ? 'selected' : '' }}>Bimbingan Kelompok (BKP)</option>
                        <option value="konseling_kelompok" {{ request('type') === 'konseling_kelompok' ? 'selected' : '' }}>Konseling Kelompok (KKP)</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="status" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="dijadwalkan" {{ request('status') === 'dijadwalkan' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-mint text-white btn-sm w-100 fw-bold rounded-pill shadow-xs fs-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->anyFilled(['q', 'type', 'status']))
                        <a href="{{ route('panel.layanan-kelompok.index') }}" class="btn btn-light border btn-sm rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Info Counter Bar -->
    <div class="d-flex justify-content-between align-items-center px-1">
        <span class="fw-extrabold text-dark fs-sm">Daftar Sesi Kelompok Masuk</span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">
            Menampilkan {{ $groups->firstItem() ?? 0 }}-{{ $groups->lastItem() ?? 0 }} dari {{ $groups->total() }} data
        </span>
    </div>

    <!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px) -->
    <div class="d-md-none mb-3">
        @forelse($groups as $item)
            <div class="mobile-card mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pb-2 border-bottom">
                    <div>
                        <span class="fw-bold font-monospace text-mint fs-xs d-block">{{ $item->ticket_number }}</span>
                        <span class="badge {{ $item->service_type_badge_class }} rounded-pill px-2 py-0.5 fs-xxs fw-bold mt-1">
                            {{ $item->service_type_label }}
                        </span>
                    </div>
                    <span class="badge {{ $item->status_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-semibold">
                        {{ $item->status_label }}
                    </span>
                </div>

                <div class="card-info-box mb-3">
                    <div class="fw-bold text-dark fs-6 mb-1">{{ $item->topic }}</div>
                    <div class="text-muted fs-xs mb-2">{{ Str::limit($item->description, 120) }}</div>

                    <div class="pt-2 border-top d-flex align-items-center justify-content-between fs-xxs">
                        <div>
                            <span class="text-muted">Ketua:</span> 
                            <strong class="text-dark">{{ $item->leader?->name }}</strong> 
                            ({{ $item->leader?->classrooms->first()?->name ?? 'Siswa' }})
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5">
                            <i class="bi bi-people me-1"></i>{{ $item->members->count() + 1 }} Orang
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if($item->status === 'selesai')
                        <a href="{{ route('panel.layanan-kelompok.printReport', $item) }}" target="_blank" class="btn btn-outline-danger btn-card-icon" title="Cetak Berita Acara PDF">
                            <i class="bi bi-file-earmark-pdf fs-5"></i>
                        </a>
                    @endif
                    <a href="{{ route('panel.layanan-kelompok.show', $item) }}" class="btn btn-mint text-white btn-card-action flex-grow-1 shadow-sm">
                        <i class="bi bi-calendar-event me-1"></i> Tinjau & Jadwalkan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <form action="{{ route('panel.layanan-kelompok.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sesi kelompok ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-card-icon" title="Hapus Sesi">
                            <i class="bi bi-trash fs-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="mobile-card text-center py-5 px-3 mb-3">
                <i class="bi bi-people fs-1 d-block mb-2 text-muted opacity-50"></i>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan Kelompok</h6>
                <p class="text-muted fs-xs mb-0">Belum ada permohonan bimbingan atau konseling kelompok yang diajukan.</p>
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP: TABEL MODERN (>= 768px, Muat 1 Layar Penuh) -->
    <div class="d-none d-md-block">
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.03em;">
                        <tr>
                            <th style="width: 14%;" class="ps-3">Tiket & Jenis</th>
                            <th style="width: 18%;">Ketua & Rombel</th>
                            <th style="width: 10%;" class="text-center">Peserta</th>
                            <th style="width: 25%;">Topik & Pokok Bahasan</th>
                            <th style="width: 13%;">Jadwal Sesi</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                            <th style="width: 10%;" class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fs-xs">
                        @forelse($groups as $item)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-bold font-monospace text-mint fs-xs d-block">
                                        {{ $item->ticket_number }}
                                    </span>
                                    <span class="badge {{ $item->service_type_badge_class }} rounded-pill px-2 py-0.5 fs-xxs fw-semibold mt-0.5">
                                        {{ $item->service_type === 'konseling_kelompok' ? 'Konseling (KKP)' : 'Bimbingan (BKP)' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-bold text-dark fs-xs">{{ $item->leader?->name }}</div>
                                    <div class="text-muted fs-xxs">
                                        Kelas: <span class="fw-semibold text-dark">{{ $item->leader?->classrooms->first()?->name ?? 'Siswa' }}</span>
                                        &bull; <code class="text-mint">{{ $item->leader?->nisn ?? '-' }}</code>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 fs-xxs fw-semibold" 
                                          title="Ketua + {{ $item->members->count() }} Anggota Teman">
                                        <i class="bi bi-people-fill me-1 text-mint"></i>{{ $item->members->count() + 1 }} Siswa
                                    </span>
                                </td>

                                <td>
                                    <div class="fw-bold text-dark fs-xs text-truncate" style="max-width: 240px;" title="{{ $item->topic }}">
                                        {{ $item->topic }}
                                    </div>
                                    <div class="text-muted fs-xxs text-truncate" style="max-width: 240px;" title="{{ $item->description }}">
                                        {{ $item->description }}
                                    </div>
                                </td>

                                <td>
                                    @if($item->scheduled_date)
                                        <div class="text-dark fs-xxs fw-semibold">
                                            <i class="bi bi-calendar-check me-0.5 text-mint"></i>{{ $item->scheduled_date->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-muted fs-xxs">
                                            <i class="bi bi-clock me-0.5"></i>{{ $item->start_time ?? '-' }} - {{ $item->end_time ?? '-' }}
                                        </div>
                                    @else
                                        <span class="text-muted fs-xxs">
                                            <i class="bi bi-calendar me-0.5"></i>Usulan: {{ $item->preferred_date ? $item->preferred_date->translatedFormat('d M Y') : 'Kapan saja' }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $item->status_badge_class }} px-2 py-0.5 rounded-pill fs-xxs fw-semibold text-nowrap">
                                        {{ $item->status_label }}
                                    </span>
                                </td>

                                <td class="text-end pe-3">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        @if($item->status === 'selesai')
                                            <a href="{{ route('panel.layanan-kelompok.printReport', $item) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 28px; height: 28px;" title="Cetak Berita Acara PDF">
                                                <i class="bi bi-file-earmark-pdf fs-6"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('panel.layanan-kelompok.show', $item) }}" class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 28px; height: 28px;" title="Tinjau & Jadwalkan">
                                            <i class="bi bi-eye fs-6"></i>
                                        </a>
                                        <form action="{{ route('panel.layanan-kelompok.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan kelompok ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" style="width: 28px; height: 28px;" title="Hapus Data">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-muted opacity-50"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan Layanan Kelompok</h6>
                                    <p class="text-muted fs-xs mb-0">Belum ada permohonan bimbingan atau konseling kelompok dari siswa.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($groups->hasPages())
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
                {{ $groups->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

