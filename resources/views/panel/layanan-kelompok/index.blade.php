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
                        <p class="text-muted fs-xs mb-0">Kelola pengajuan mandiri siswa, kelompokkan murid dengan kebutuhan serupa, dan tetapkan jadwal sesi kelompok.</p>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-xs fs-xs" data-bs-toggle="modal" data-bs-target="#modalCreateGroup">
                        <i class="bi bi-plus-circle-fill me-1"></i> Bentuk Kelompok Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs: Antrean Pengajuan Mandiri vs Sesi Kelompok Terbentuk -->
    <ul class="nav nav-pills gap-2 mb-3" id="groupTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('tab', 'requests') === 'requests' ? 'active bg-mint text-white' : 'bg-white text-dark border' }} rounded-pill px-3.5 py-1.5 fs-xs fw-bold" id="tab-requests-btn" data-bs-toggle="pill" data-bs-target="#tab-requests" type="button" role="tab">
                <i class="bi bi-inbox-fill me-1"></i> Antrean Pengajuan Mandiri Murid
                <span class="badge {{ request('tab', 'requests') === 'requests' ? 'bg-white text-mint' : 'bg-warning text-dark' }} ms-1 rounded-pill">
                    {{ $counters['pending_requests'] }}
                </span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('tab') === 'groups' ? 'active bg-mint text-white' : 'bg-white text-dark border' }} rounded-pill px-3.5 py-1.5 fs-xs fw-bold" id="tab-groups-btn" data-bs-toggle="pill" data-bs-target="#tab-groups" type="button" role="tab">
                <i class="bi bi-calendar2-check-fill me-1"></i> Sesi Kelompok Terbentuk & Terjadwal
                <span class="badge {{ request('tab') === 'groups' ? 'bg-white text-mint' : 'bg-light text-dark border' }} ms-1 rounded-pill">
                    {{ $counters['total'] }}
                </span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="groupTabsContent">
        <!-- ======================================================== -->
        <!-- TAB 1: ANTREAN PENGAJUAN MANDIRI MURID                   -->
        <!-- ======================================================== -->
        <div class="tab-pane fade {{ request('tab', 'requests') === 'requests' ? 'show active' : '' }}" id="tab-requests" role="tabpanel">
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark fs-sm">
                            <i class="bi bi-person-lines-fill text-mint me-1"></i> Siswa yang Mengajukan Sesi Kelompok Mandiri
                        </h6>
                        <p class="text-muted fs-xxs mb-0">Pilih satu atau beberapa siswa yang memiliki topik senada untuk disatukan ke dalam 1 kelompok.</p>
                    </div>

                    <div id="batchActionArea" class="d-none">
                        <button type="button" class="btn btn-sm btn-mint text-white rounded-pill px-3 py-1.5 fs-xxs fw-bold shadow-xs" onclick="openBatchGroupModal()">
                            <i class="bi bi-people-fill me-1"></i> Bentuk Kelompok (<span id="selectedCountText">0</span> Siswa Terpilih)
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-xxs text-uppercase text-muted">
                            <tr>
                                <th style="width: 40px;" class="ps-3">
                                    <input type="checkbox" class="form-check-input" id="checkAllRequests" onchange="toggleAllRequests(this)">
                                </th>
                                <th>Tiket / Tanggal</th>
                                <th>Siswa Pemohon</th>
                                <th>Layanan</th>
                                <th>Topik & Cerita Permasalahan</th>
                                <th>Usulan Waktu</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fs-xs">
                            @forelse($pendingRequests as $req)
                                <tr>
                                    <td class="ps-3">
                                        <input type="checkbox" class="form-check-input request-checkbox" 
                                               value="{{ $req->student_id }}" 
                                               data-request-id="{{ $req->id }}"
                                               data-student-name="{{ $req->student->name }}"
                                               data-service-type="{{ $req->service_type }}"
                                               data-topic="{{ $req->topic }}"
                                               onchange="handleRequestCheck()">
                                    </td>
                                    <td>
                                        <span class="fw-bold font-monospace text-mint fs-xxs d-block">{{ $req->ticket_number }}</span>
                                        <span class="text-muted fs-xxs">{{ $req->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $req->student->photo_url }}" alt="{{ $req->student->name }}" class="rounded-circle shadow-xs" width="34" height="34" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $req->student->name }}</div>
                                                <div class="d-flex align-items-center gap-1 mt-0.5">
                                                    <span class="text-muted fs-xxs">Kelas {{ $req->student->classrooms->first()?->name ?? '-' }}</span>
                                                    @if($req->student->zodiac_label)
                                                        <span class="badge-zodiac" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;" title="Zodiak: {{ $req->student->zodiac_name }}">
                                                            {{ $req->student->zodiac_label }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $req->service_type_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                                            {{ $req->service_type_label }}
                                        </span>
                                    </td>
                                    <td style="max-width: 260px;">
                                        <div class="fw-bold text-dark text-truncate" title="{{ $req->topic }}">{{ $req->topic }}</div>
                                        <div class="text-muted fs-xxs text-truncate" title="{{ $req->description }}">{{ $req->description }}</div>
                                    </td>
                                    <td>
                                        @if($req->preferred_date)
                                            <span class="fw-semibold text-dark fs-xxs d-block">📅 {{ $req->preferred_date->format('d/m/Y') }}</span>
                                        @endif
                                        <span class="text-muted fs-xxs">{{ $req->preferred_session ?? 'Fleksibel' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-1.5">
                                            <button type="button" class="btn btn-sm btn-mint text-white rounded-pill px-2.5 py-1 fs-xxs fw-bold shadow-xs" 
                                                    onclick="singleFormGroup({{ $req->student_id }}, '{{ addslashes($req->student->name) }}', '{{ $req->service_type }}', '{{ addslashes($req->topic) }}', {{ $req->id }})">
                                                <i class="bi bi-people-fill me-0.5"></i> Kelompokkan
                                            </button>
                                            <form action="{{ route('panel.layanan-kelompok.destroy-request', $req) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan mandiri tiket {{ $req->ticket_number }} ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 26px; height: 26px;" title="Hapus Pengajuan">
                                                    <i class="bi bi-trash fs-xxs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-muted">
                                        <i class="bi bi-inbox fs-2 text-muted mb-2 d-block"></i>
                                        <div class="fw-bold fs-xs">Tidak Ada Antrean Pengajuan Mandiri</div>
                                        <div class="fs-xxs text-muted">Seluruh pengajuan siswa sudah dikelompokkan atau belum ada siswa yang mengajukan.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pendingRequests->hasPages())
                    <div class="card-footer bg-white py-2 px-3 border-top d-flex justify-content-center">
                        {{ $pendingRequests->appends(['tab' => 'requests'])->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: DAFTAR KELOMPOK TERBENTUK & TERJADWAL             -->
        <!-- ======================================================== -->
        <div class="tab-pane fade {{ request('tab') === 'groups' ? 'show active' : '' }}" id="tab-groups" role="tabpanel">
            <!-- Filter & Search Bar -->
            <div class="card card-app border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('panel.layanan-kelompok.index') }}" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="groups">
                        <div class="col-12 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 fs-xs" placeholder="Cari No. Tiket, Topik, atau Nama Siswa...">
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
                                <a href="{{ route('panel.layanan-kelompok.index', ['tab' => 'groups']) }}" class="btn btn-light border btn-sm rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 31px; height: 31px;" title="Reset Filter">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Kelompok Terbentuk -->
            <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-xxs text-uppercase text-muted">
                            <tr>
                                <th class="ps-3">Tiket / Layanan</th>
                                <th>Topik Bahasan Kelompok</th>
                                <th>Guru BK Pembimbing</th>
                                <th>Jadwal & Tempat</th>
                                <th>Anggota Kelompok</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fs-xs">
                            @forelse($groups as $grp)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-bold font-monospace text-mint fs-xs d-block">{{ $grp->ticket_number }}</span>
                                        <span class="badge {{ $grp->service_type_badge_class }} rounded-pill px-2 py-0.5 fs-xxs fw-bold mt-0.5">
                                            {{ $grp->service_type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-xs">{{ $grp->topic }}</div>
                                        <div class="text-muted fs-xxs text-truncate" style="max-width: 220px;">{{ $grp->description }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-xs">{{ $grp->counselor?->user?->name ?? 'Belum ditentukan' }}</div>
                                        <span class="text-muted fs-xxs">Guru BK</span>
                                    </td>
                                    <td>
                                        @if($grp->scheduled_date)
                                            <div class="fw-bold text-dark fs-xs">📅 {{ $grp->scheduled_date->format('d/m/Y') }}</div>
                                            <div class="text-muted fs-xxs">⏰ {{ $grp->start_time }} - {{ $grp->end_time }} WIB</div>
                                            <div class="text-muted fs-xxs">📍 {{ $grp->location ?? 'Ruang BK' }}</div>
                                        @else
                                            <span class="text-muted fs-xxs italic">Belum dijadwalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-teal-subtle text-mint rounded-pill px-2 py-1 fs-xxs fw-bold d-inline-block mb-1">
                                            {{ $grp->members->count() }} Anggota
                                        </span>
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 220px;">
                                            @foreach($grp->members->take(3) as $mem)
                                                <span class="badge bg-light text-dark border rounded-pill px-1.5 py-0.5 fs-xxs">
                                                    {{ Str::words($mem->name, 1, '') }}
                                                    @if($mem->zodiac_icon)
                                                        <span class="badge-zodiac px-1 py-0 fs-xxs" style="font-size: 0.62rem;">{{ $mem->zodiac_icon }}</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                            @if($grp->members->count() > 3)
                                                <span class="badge bg-light text-muted border rounded-pill px-1.5 py-0.5 fs-xxs">+{{ $grp->members->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $grp->status_badge_class }} rounded-pill px-2.5 py-1 fs-xxs fw-bold">
                                            {{ $grp->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-1.5">
                                            <a href="{{ route('panel.layanan-kelompok.show', $grp) }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fs-xxs fw-bold" title="Detail & Respon">
                                                <i class="bi bi-eye me-1"></i> Detail
                                            </a>
                                            @if($grp->status === 'selesai')
                                                <a href="{{ route('panel.layanan-kelompok.printReport', $grp) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 28px; height: 28px;" title="Cetak Berita Acara PDF">
                                                    <i class="bi bi-file-earmark-pdf fs-xxs"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('panel.layanan-kelompok.destroy', $grp) }}" method="POST" onsubmit="return confirm('Hapus sesi kelompok tiket {{ $grp->ticket_number }} ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 28px; height: 28px;" title="Hapus">
                                                    <i class="bi bi-trash fs-xxs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-muted">
                                        <i class="bi bi-people fs-2 text-muted mb-2 d-block"></i>
                                        <div class="fw-bold fs-xs">Belum Ada Sesi Kelompok Terbentuk</div>
                                        <div class="fs-xxs text-muted">Bentuk kelompok dari antrean pengajuan siswa pada tab pertama.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($groups->hasPages())
                    <div class="card-footer bg-white py-2 px-3 border-top d-flex justify-content-center">
                        {{ $groups->appends(['tab' => 'groups'])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: BENTUK KELOMPOK BARU & PENJADWALAN               -->
<!-- ======================================================== -->
<div class="modal fade" id="modalCreateGroup" tabindex="-1" aria-labelledby="modalCreateGroupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('panel.layanan-kelompok.form-group') }}" method="POST" id="formCreateGroupModal">
                @csrf
                <div class="modal-header border-bottom py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark fs-6" id="modalCreateGroupLabel">
                        <i class="bi bi-people-fill text-mint me-1.5"></i> Bentuk Kelompok & Tetapkan Jadwal Sesi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 space-y-3">
                    <div class="alert alert-info border-0 rounded-3 fs-xs mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i> Setelah kelompok dan jadwal ini disimpan, <strong>notifikasi resmi akan otomatis muncul di akun masing-masing murid anggota</strong> lengkap dengan jadwal dan nama rekan sekelompoknya.
                    </div>

                    <div class="row g-3">
                        <!-- Jenis Layanan -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Jenis Layanan Kelompok <span class="text-danger">*</span></label>
                            <select name="service_type" id="modalServiceType" class="form-select form-select-sm" required>
                                <option value="bimbingan_kelompok">Bimbingan Kelompok (BKP)</option>
                                <option value="konseling_kelompok">Konseling Kelompok (KKP)</option>
                            </select>
                        </div>

                        <!-- Guru BK Pendamping -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Guru BK / Konselor Pendamping <span class="text-danger">*</span></label>
                            <select name="counselor_id" class="form-select form-select-sm" required>
                                @foreach($counselors as $c)
                                    <option value="{{ $c->id }}" {{ $loop->first ? 'selected' : '' }}>
                                        {{ $c->user?->name ?? $c->name ?? 'Guru BK' }} ({{ $c->specialization ?? 'Guru BK' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Topik Kelompok -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Topik Bahasan Kelompok <span class="text-danger">*</span></label>
                            <input type="text" name="topic" id="modalGroupTopic" class="form-control form-control-sm" placeholder="Contoh: Strategi Mengatur Waktu Belajar & Mengurangi Kecemasan Ujian" required>
                        </div>

                        <!-- Deskripsi / Catatan Pembentukan -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Deskripsi / Arahan Singkat</label>
                            <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Tuliskan tujuan umum atau arahan yang perlu disiapkan siswa..."></textarea>
                        </div>

                        <!-- Jadwal: Tanggal, Waktu, Tempat -->
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold small text-dark">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="scheduled_date" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label fw-bold small text-dark">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control form-control-sm" value="09:00" required>
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label fw-bold small text-dark">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control form-control-sm" value="10:30" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Tempat / Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm" value="Ruang Bimbingan Konseling (BK)" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">Tautan Pertemuan Virtual (Opsional)</label>
                            <input type="url" name="meeting_link" class="form-control form-control-sm" placeholder="https://meet.google.com/...">
                        </div>

                        <!-- Pemilihan Siswa Anggota Kelompok -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark d-flex justify-content-between align-items-center mb-1">
                                <span><i class="bi bi-people-fill text-mint me-1"></i> Pilih Siswa Anggota Kelompok <span class="text-danger">*</span></span>
                                <span class="text-muted fs-xxs">Antrean siswa yang telah mengajukan</span>
                            </label>

                            <div class="p-2 mb-2 rounded-3 bg-teal-subtle text-mint fs-xxs">
                                <i class="bi bi-info-circle-fill me-1"></i> Menampilkan antrean siswa yang <strong>telah mengajukan permohonan mandiri</strong>. Centang siswa-siswa yang ingin disatukan dalam kelompok ini.
                            </div>

                            <!-- Hidden container for linked request IDs -->
                            <div id="hiddenRequestIdsContainer"></div>

                            <div class="border rounded-3 p-2.5 bg-light" style="max-height: 240px; overflow-y: auto;">
                                <div class="row g-2" id="modalApplicantListContainer">
                                    @forelse($allPendingRequests as $pr)
                                        @php $st = $pr->student; @endphp
                                        <div class="col-12 col-sm-6 modal-applicant-col" 
                                             data-service-type="{{ $pr->service_type }}" 
                                             data-student-id="{{ $st->id }}"
                                             data-request-id="{{ $pr->id }}">
                                            <div class="form-check p-2 bg-white rounded-2 border h-100 d-flex align-items-center">
                                                <input class="form-check-input modal-student-check ms-1 flex-shrink-0" 
                                                       type="checkbox" 
                                                       name="student_ids[]" 
                                                       value="{{ $st->id }}" 
                                                       data-request-id="{{ $pr->id }}"
                                                       data-service-type="{{ $pr->service_type }}"
                                                       id="modalSt{{ $st->id }}"
                                                       onchange="syncRequestIds()">
                                                <label class="form-check-label ms-2 d-flex align-items-center justify-content-between w-100 fs-xs cursor-pointer mb-0" for="modalSt{{ $st->id }}">
                                                    <div class="overflow-hidden pe-1">
                                                        <span class="fw-bold text-dark d-block text-truncate" style="line-height: 1.2;">{{ $st->name }}</span>
                                                        <div class="text-muted fs-xxs mt-0.5">
                                                            <span>Kelas {{ $st->classrooms->first()?->name ?? '-' }}</span>
                                                            &bull;
                                                            <span class="badge {{ $pr->service_type === 'konseling_kelompok' ? 'bg-purple-subtle text-purple' : 'bg-teal-subtle text-mint' }} px-1.5 py-0.2" style="font-size: 0.62rem;">
                                                                {{ $pr->service_type === 'konseling_kelompok' ? 'KKP' : 'BKP' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($st->zodiac_label)
                                                        <span class="badge-zodiac flex-shrink-0 ms-1" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;" title="Zodiak: {{ $st->zodiac_name }}">
                                                            {{ $st->zodiac_label }}
                                                        </span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-3 text-muted fs-xs">
                                            <i class="bi bi-inbox fs-4 d-block mb-1 text-muted"></i>
                                            Belum ada siswa yang mengajukan permohonan bimbingan/konseling kelompok.
                                        </div>
                                    @endforelse

                                    <div id="noMatchingApplicantAlert" class="col-12 text-center py-3 text-muted fs-xs d-none">
                                        <i class="bi bi-person-x fs-4 d-block mb-1 text-warning"></i>
                                        Tidak ada antrean siswa lain yang mengajukan untuk jenis layanan ini.
                                    </div>
                                </div>
                            </div>

                            <!-- Collapsible Tambahan: Siswa Lainnya (Manual) -->
                            <div class="mt-2">
                                <a class="text-decoration-none fs-xxs fw-bold text-muted d-inline-flex align-items-center" data-bs-toggle="collapse" href="#collapseOtherStudents" role="button" aria-expanded="false">
                                    <i class="bi bi-plus-circle me-1 text-mint"></i> Ingin sertakan siswa lain yang belum mengajukan? (Manual Rekomendasi Guru BK)
                                </a>
                                <div class="collapse mt-2" id="collapseOtherStudents">
                                    <div class="p-2.5 border rounded-3 bg-white" style="max-height: 160px; overflow-y: auto;">
                                        <div class="row g-2">
                                            @php 
                                                $pendingStudentIds = $allPendingRequests->pluck('student_id')->toArray();
                                            @endphp
                                            @foreach($allStudents->whereNotIn('id', $pendingStudentIds) as $st)
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-check p-2 bg-light rounded-2 border">
                                                        <input class="form-check-input modal-student-check ms-1" type="checkbox" name="student_ids[]" value="{{ $st->id }}" id="modalOtherSt{{ $st->id }}" onchange="syncRequestIds()">
                                                        <label class="form-check-label ms-2 d-flex align-items-center justify-content-between w-100 fs-xs cursor-pointer mb-0" for="modalOtherSt{{ $st->id }}">
                                                            <div>
                                                                <span class="fw-semibold text-dark d-block text-truncate" style="line-height: 1.2;">{{ $st->name }}</span>
                                                                <span class="text-muted fs-xxs">Kelas {{ $st->classrooms->first()?->name ?? '-' }}</span>
                                                            </div>
                                                            @if($st->zodiac_label)
                                                                <span class="badge-zodiac flex-shrink-0" style="font-size: 0.65rem; padding: 0.1rem 0.45rem;">
                                                                    {{ $st->zodiac_label }}
                                                                </span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fs-xs" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mint text-white rounded-pill px-4 fw-bold fs-xs shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Simpan & Terbitkan Jadwal ke Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleAllRequests(source) {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        handleRequestCheck();
    }

    function handleRequestCheck() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        const batchArea = document.getElementById('batchActionArea');
        const countText = document.getElementById('selectedCountText');

        if (checked.length > 0) {
            batchArea.classList.remove('d-none');
            countText.textContent = checked.length;
        } else {
            batchArea.classList.add('d-none');
            countText.textContent = '0';
        }
    }

    function filterApplicantsByServiceType(sType) {
        let visibleCount = 0;
        document.querySelectorAll('.modal-applicant-col').forEach(col => {
            const itemType = col.getAttribute('data-service-type');
            if (!sType || itemType === sType) {
                col.classList.remove('d-none');
                visibleCount++;
            } else {
                col.classList.add('d-none');
            }
        });

        const alertEmpty = document.getElementById('noMatchingApplicantAlert');
        if (alertEmpty) {
            if (visibleCount === 0) {
                alertEmpty.classList.remove('d-none');
            } else {
                alertEmpty.classList.add('d-none');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('modalServiceType');
        if (serviceSelect) {
            serviceSelect.addEventListener('change', function() {
                filterApplicantsByServiceType(this.value);
            });
        }
    });

    function syncRequestIds() {
        const hiddenContainer = document.getElementById('hiddenRequestIdsContainer');
        hiddenContainer.innerHTML = '';
        document.querySelectorAll('.modal-student-check:checked').forEach(cb => {
            const reqId = cb.getAttribute('data-request-id');
            if (reqId) {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'request_ids[]';
                inp.value = reqId;
                hiddenContainer.appendChild(inp);
            }
        });
    }

    function openBatchGroupModal() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        if (checked.length === 0) return;

        // Reset check di modal
        document.querySelectorAll('.modal-student-check').forEach(cb => cb.checked = false);

        let suggestedTopic = '';
        let serviceType = 'bimbingan_kelompok';

        checked.forEach(cb => {
            const studentId = cb.value;
            const topic = cb.getAttribute('data-topic');
            const sType = cb.getAttribute('data-service-type');

            if (!suggestedTopic && topic) suggestedTopic = topic;
            if (sType) serviceType = sType;

            const modalCb = document.getElementById('modalSt' + studentId);
            if (modalCb) modalCb.checked = true;
        });

        const typeSelect = document.getElementById('modalServiceType');
        if (typeSelect) {
            typeSelect.value = serviceType;
        }
        if (suggestedTopic) {
            document.getElementById('modalGroupTopic').value = suggestedTopic;
        }

        filterApplicantsByServiceType(serviceType);
        syncRequestIds();

        const modal = new bootstrap.Modal(document.getElementById('modalCreateGroup'));
        modal.show();
    }

    function singleFormGroup(studentId, studentName, serviceType, topic, reqId) {
        document.querySelectorAll('.modal-student-check').forEach(cb => cb.checked = false);

        const sType = serviceType || 'bimbingan_kelompok';
        const typeSelect = document.getElementById('modalServiceType');
        if (typeSelect) {
            typeSelect.value = sType;
        }

        document.getElementById('modalGroupTopic').value = topic || (sType === 'konseling_kelompok' ? 'Sesi Konseling Kelompok' : 'Sesi Bimbingan Kelompok');

        filterApplicantsByServiceType(sType);

        const modalCb = document.getElementById('modalSt' + studentId);
        if (modalCb) {
            modalCb.checked = true;
        }

        syncRequestIds();

        const modal = new bootstrap.Modal(document.getElementById('modalCreateGroup'));
        modal.show();
    }
</script>
@endpush
@endsection
