@extends('layouts.panel')

@section('title', 'Monitoring Agenda Konseling')

@section('content')
<div class="space-y-3 space-y-md-4">
    <!-- Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-2 mb-md-3">
        <div class="card-body p-2.5 p-md-4">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-1.5 p-md-2.5 rounded-3 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                        <i class="bi bi-calendar2-heart fs-5 fs-md-3"></i>
                    </div>
                    <div>
                        <div class="d-none d-sm-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-0.5 fs-xxs fw-bold rounded-pill">Layanan Bimbingan Konseling</span>
                            <span class="badge bg-primary-subtle text-primary px-2.5 py-0.5 fs-xxs fw-semibold rounded-pill">Persetujuan & Penjadwalan</span>
                        </div>
                        <h1 class="fw-extrabold text-dark mb-0 fs-6 fs-md-5">Agenda Konseling Murid</h1>
                        <p class="text-muted small mb-0 mt-0.5 d-none d-md-block">Kelola, tinjau keluhan, setujui rencana jadwal, dan pantau seluruh sesi konseling siswa.</p>
                    </div>
                </div>
                <div class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs fs-md-xs text-nowrap">
                    {{ $requests->total() }} Sesi
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Message -->
    <x-alert />

    <!-- Status Tabs Filter Pills (Mobile Scrollable) -->
    <div class="d-flex gap-1.5 overflow-x-auto pb-1 scrollbar-none" style="-webkit-overflow-scrolling: touch; scrollbar-width: none;">
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'all'])) }}" 
           class="btn btn-sm {{ (!$status || $status === 'all') ? 'btn-mint shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            Semua ({{ $counters['all'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" 
           class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan ({{ $counters['pending'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'scheduled'])) }}" 
           class="btn btn-sm {{ $status === 'scheduled' ? 'btn-info text-dark shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-calendar-event me-1"></i> Disetujui Rencana ({{ $counters['scheduled'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" 
           class="btn btn-sm {{ $status === 'approved' ? 'btn-primary text-white shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-check-circle me-1"></i> Disetujui ({{ $counters['approved'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'completed'])) }}" 
           class="btn btn-sm {{ $status === 'completed' ? 'btn-success text-white shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-patch-check me-1"></i> Selesai ({{ $counters['completed'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'needs_information'])) }}" 
           class="btn btn-sm {{ $status === 'needs_information' ? 'btn-secondary text-white shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-question-circle me-1"></i> Butuh Info ({{ $counters['needs_information'] }})
        </a>
        <a href="{{ route('panel.konseling.index', array_merge(request()->except('status'), ['status' => 'rejected'])) }}" 
           class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger text-white shadow-sm' : 'btn-light border text-dark' }} rounded-pill px-3 py-1.5 text-nowrap fs-xs fw-bold">
            <i class="bi bi-x-circle me-1"></i> Ditolak ({{ $counters['rejected'] }})
        </a>
    </div>

    <!-- Filters & Search Card (Mobile Optimized) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-2.5 p-md-3">
            <form method="GET" action="{{ route('panel.konseling.index') }}">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 rounded-end-pill" placeholder="Cari No. Tiket, Nama Murid...">
                        </div>
                    </div>

                    <!-- Mobile Filter Trigger Row -->
                    <div class="col-12 d-md-none">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 flex-grow-1 d-flex align-items-center justify-content-between text-muted fs-xs fw-semibold" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#mobileFilterCollapse" 
                                    aria-expanded="{{ request()->hasAny(['classroom_id', 'counselor_id', 'source']) ? 'true' : 'false' }}">
                                <span><i class="bi bi-sliders me-1.5 text-mint"></i> Opsi Filter Tambahan</span>
                                <i class="bi bi-chevron-down fs-xxs"></i>
                            </button>
                            <button type="submit" class="btn btn-sm btn-mint rounded-pill px-3 fw-bold fs-xs">
                                Cari
                            </button>
                            @if(request()->hasAny(['q', 'classroom_id', 'counselor_id', 'source']))
                                <a href="{{ route('panel.konseling.index') }}" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 31px; height: 31px;" title="Reset Filter">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Filter Dropdowns (Inline on Desktop, Collapsible on Mobile) -->
                    <div class="col-12 col-md-8 collapse d-md-block {{ request()->hasAny(['classroom_id', 'counselor_id', 'source']) ? 'show' : '' }}" id="mobileFilterCollapse">
                        <div class="row g-2 pt-2 pt-md-0">
                            <div class="col-sm-4 col-md-3">
                                <select name="classroom_id" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classrooms as $cls)
                                        <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                            {{ $cls->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <select name="counselor_id" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                                    <option value="">-- Semua Konselor --</option>
                                    @foreach($counselors as $cns)
                                        <option value="{{ $cns->id }}" {{ $counselorId == $cns->id ? 'selected' : '' }}>
                                            {{ $cns->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <select name="source" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                                    <option value="">-- Semua Sumber --</option>
                                    <option value="pribadi" {{ request('source') === 'pribadi' ? 'selected' : '' }}>Konseling Pribadi</option>
                                    <option value="ims" {{ request('source') === 'ims' ? 'selected' : '' }}>Tindak Lanjut IMS</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-none d-md-flex gap-1.5">
                                <button type="submit" class="btn btn-sm btn-mint rounded-pill px-3 fw-bold w-100 shadow-xs">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                @if(request()->hasAny(['q', 'classroom_id', 'counselor_id', 'source']))
                                    <a href="{{ route('panel.konseling.index') }}" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 31px; height: 31px;" title="Reset Filter">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 1. TAMPILAN MOBILE: ANDROID CARD LIST FEED (d-md-none) -->
    <div class="d-md-none space-y-2.5">
        @forelse($requests as $item)
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="font-monospace fw-bold text-mint fs-xs">#{{ $item->ticket_number }}</span>
                    <x-badge-status :status="$item->status" />
                </div>

                <div class="d-flex align-items-center gap-2.5 mb-2.5">
                    <img src="{{ $item->student->photo_url }}" alt="{{ $item->student->name }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                    <div style="flex: 1; min-width: 0;">
                        <div class="fw-bold text-dark text-truncate">{{ $item->student->name }}</div>
                        <div class="text-muted fs-xxs">
                            NISN: {{ $item->student->nisn }} &bull; 
                            <span class="fw-semibold text-dark">{{ $item->student->classrooms->first()?->name ?? 'Tanpa Rombel' }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-2.5 rounded-3 bg-light mb-2.5">
                    <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                        @if($item->source === 'ims')
                            <span class="badge bg-purple-subtle text-purple border-purple px-2 py-0 fs-xxs fw-semibold">
                                <i class="bi bi-clipboard2-check me-0.5"></i>IMS
                            </span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border-primary px-2 py-0 fs-xxs fw-semibold">
                                <i class="bi bi-person me-0.5"></i>Pribadi
                            </span>
                            @if(!empty($item->attached_ims_statements))
                                <span class="badge bg-warning-subtle text-warning-emphasis border px-1.5 py-0 fs-xxs fw-semibold">
                                    <i class="bi bi-paperclip"></i> {{ count($item->attached_ims_statements) }} IMS
                                </span>
                            @endif
                        @endif

                        <span class="badge bg-white text-muted border px-2 py-0 fs-xxs">
                            {{ $item->guidanceField?->name ?? 'Bidang Pribadi' }}
                        </span>
                    </div>
                    <div class="fw-bold text-dark fs-xs mb-1" style="line-height: 1.4;">
                        {{ $item->purpose }}
                    </div>
                    <div class="text-muted fs-xxs d-flex align-items-center gap-2">
                        <span><i class="bi bi-clock me-0.5"></i>Usulan: {{ $item->preferred_schedule ?? 'Fleksibel' }}</span>
                        <span>&bull;</span>
                        <span>
                            @if($item->counseling_method === 'online_meet')
                                <i class="bi bi-camera-video text-info"></i> Online
                            @else
                                <i class="bi bi-geo-alt text-success"></i> Tatap Muka
                            @endif
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div class="text-muted fs-xxs">
                        <i class="bi bi-calendar3 me-0.5"></i> {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                    </div>
                    <div class="d-flex align-items-center gap-1.5">
                        @if($item->status === 'completed')
                            <a href="{{ route('panel.konseling.printReport', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fs-xs fw-bold" title="Cetak Laporan PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        @endif
                        <form action="{{ route('panel.konseling.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus agenda konseling {{ $item->ticket_number }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Hapus">
                                <i class="bi bi-trash3 fs-xs"></i>
                            </button>
                        </form>
                        <a href="{{ route('panel.konseling.show', $item->id) }}" class="btn btn-sm btn-mint rounded-pill px-3 py-1 fs-xs fw-bold shadow-xs">
                            Buka Sesi <i class="bi bi-chevron-right ms-0.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
                <div class="p-3 rounded-circle bg-teal-subtle text-mint d-inline-flex mx-auto mb-2" style="width: 56px; height: 56px; align-items: center; justify-content: center;">
                    <i class="bi bi-calendar2-x fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Agenda Konseling</h6>
                <p class="text-muted fs-xs mb-0">Tidak ada pengajuan agenda konseling yang sesuai kriteria pencarian.</p>
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP & TABLET: TABEL LENGKAP (d-none d-md-block) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-extrabold text-dark fs-sm">Daftar Agenda Konseling Murid</span>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill">Menampilkan {{ $requests->firstItem() ?? 0 }}-{{ $requests->lastItem() ?? 0 }} dari {{ $requests->total() }} agenda</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-uppercase text-muted" style="letter-spacing: 0.03em;">
                    <tr>
                        <th style="width: 140px;" class="ps-4">No. Tiket</th>
                        <th>Identitas Murid</th>
                        <th>Topik & Kebutuhan</th>
                        <th style="width: 140px;">Metode & Waktu</th>
                        <th style="width: 150px;">Konselor PIC</th>
                        <th style="width: 150px;" class="text-center">Status</th>
                        <th style="width: 140px;" class="text-center">Jadwal Sesi</th>
                        <th style="width: 140px;" class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-sm">
                    @forelse($requests as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold font-monospace text-mint fs-xs">{{ $item->ticket_number }}</span>
                                <div class="text-muted fs-xxs mt-0.5">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <img src="{{ $item->student->photo_url }}" alt="{{ $item->student->name }}" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->student->name }}</div>
                                        <div class="text-muted fs-xs">
                                            NISN: {{ $item->student->nisn }} &bull; 
                                            <span class="text-dark fw-semibold">{{ $item->student->classrooms->first()?->name ?? 'Tanpa Rombel' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1.5 mb-1">
                                    @if($item->source === 'ims')
                                        <span class="badge bg-purple-subtle text-purple border-purple px-2 py-0 fs-xxs fw-semibold">
                                            <i class="bi bi-clipboard2-check me-0.5"></i>IMS
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border-primary px-2 py-0 fs-xxs fw-semibold">
                                            <i class="bi bi-person me-0.5"></i>Pribadi
                                        </span>
                                        @if(!empty($item->attached_ims_statements))
                                            <span class="badge bg-warning-subtle text-warning-emphasis border px-1.5 py-0 fs-xxs fw-semibold" title="{{ count($item->attached_ims_statements) }} Butir IMS Dilampirkan">
                                                <i class="bi bi-paperclip"></i> {{ count($item->attached_ims_statements) }} IMS
                                            </span>
                                        @endif
                                    @endif

                                    <span class="badge bg-light text-muted border px-2 py-0 fs-xxs">
                                        {{ $item->guidanceField?->name ?? 'Bidang Pribadi' }}
                                    </span>
                                </div>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 240px;" title="{{ $item->purpose }}">
                                    {{ $item->purpose }}
                                </div>
                            </td>
                            <td>
                                @if($item->counseling_method === 'online_meet' || ($item->schedule && $item->schedule->counseling_method === 'online_meet'))
                                    <span class="badge bg-info-subtle text-info-emphasis border-info px-2 py-0.5 rounded-pill fs-xxs fw-semibold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-camera-video"></i> Online (Meet)
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded-pill fs-xxs fw-semibold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-geo-alt"></i> Tatap Muka
                                    </span>
                                @endif
                                <div class="text-muted fs-xxs mt-1">Usulan: {{ $item->preferred_schedule ?? 'Fleksibel' }}</div>
                            </td>
                            <td>
                                @if($item->assignedCounselor)
                                    <span class="fw-semibold text-dark fs-xs">{{ $item->assignedCounselor->name }}</span>
                                @else
                                    <span class="text-muted fs-xs fst-italic">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <x-badge-status :status="$item->status" />
                            </td>
                            <td class="text-center">
                                @if($item->schedule)
                                    <div class="badge bg-light text-dark border fs-xs">
                                        <i class="bi bi-calendar3 me-1 text-mint"></i>
                                        {{ $item->schedule->scheduled_date->format('d/m') }}, {{ $item->schedule->start_time }}
                                    </div>
                                    <div class="fs-xxs text-muted mt-1 text-truncate" style="max-width: 130px;">
                                        {{ $item->schedule->counseling_method === 'online_meet' ? 'Google Meet' : $item->schedule->location }}
                                    </div>
                                @else
                                    <span class="text-muted fs-xs fst-italic">Belum Ditetapkan</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    <a href="{{ route('panel.konseling.show', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fs-xs fw-bold" title="Detail Sesi">
                                        Detail
                                    </a>
                                    @if($item->status === 'completed')
                                        <a href="{{ route('panel.konseling.printReport', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fs-xs fw-bold" title="Cetak Laporan PDF Resmi">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('panel.konseling.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda konseling {{ $item->ticket_number }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 fs-xs" title="Hapus Agenda">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada pengajuan agenda konseling yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($requests->hasPages())
        <div class="card border-0 shadow-sm rounded-4 bg-white py-3 px-4">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
