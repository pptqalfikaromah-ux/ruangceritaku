@extends('layouts.panel')

@section('title', 'Monitoring Agenda Konseling')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3.5 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                        <i class="bi bi-calendar2-heart fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xs fw-bold rounded-pill">Layanan Bimbingan Konseling</span>
                            <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fs-xs fw-semibold rounded-pill">Persetujuan & Penjadwalan</span>
                        </div>
                        <h1 class="h5 fw-extrabold text-dark mb-0">Agenda Konseling Murid</h1>
                        <p class="text-muted small mb-0 mt-0.5">Kelola, tinjau keluhan, setujui rencana jadwal, dan pantau seluruh sesi konseling siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Message -->
    <x-alert />

    <!-- Status Tabs Filter Pills -->
    <div class="d-flex gap-2 overflow-x-auto pb-2 scrollbar-none">
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
            <i class="bi bi-patch-check me-1"></i> Sudah Tertangani ({{ $counters['completed'] }})
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

    <!-- Filters & Search Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.konseling.index') }}" class="row g-2 align-items-center">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 rounded-end-pill" placeholder="Cari No. Tiket, Nama Murid, Topik...">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="classroom_id" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="counselor_id" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                        <option value="">-- Semua Konselor --</option>
                        @foreach($counselors as $cns)
                            <option value="{{ $cns->id }}" {{ $counselorId == $cns->id ? 'selected' : '' }}>
                                {{ $cns->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="source" class="form-select form-select-sm rounded-pill px-3" onchange="this.form.submit()">
                        <option value="">-- Semua Sumber --</option>
                        <option value="pribadi" {{ request('source') === 'pribadi' ? 'selected' : '' }}>Konseling Pribadi</option>
                        <option value="ims" {{ request('source') === 'ims' ? 'selected' : '' }}>Tindak Lanjut IMS</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1.5">
                    <button type="submit" class="btn btn-sm btn-mint rounded-pill px-3 fw-bold w-100 shadow-xs">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'classroom_id', 'counselor_id', 'source', 'start_date', 'end_date']))
                        <a href="{{ route('panel.konseling.index') }}" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 31px; height: 31px;" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table Pengajuan Konseling -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                        <th style="width: 100px;" class="text-end pe-4">Aksi</th>
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
                            <td class="text-end pe-4">
                                <a href="{{ route('panel.konseling.show', $item->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fs-xs fw-bold">
                                    Detail <i class="bi bi-chevron-right ms-0.5"></i>
                                </a>
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
        @if($requests->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
