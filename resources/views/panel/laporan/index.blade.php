@extends('layouts.panel')

@section('title', 'Laporan & Rekapitulasi BK')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">
                        <span class="badge bg-teal-subtle text-mint px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Laporan Resmi</span>
                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1 fs-xxs fw-semibold rounded-pill">Data Aktual & Rekapitulasi</span>
                    </div>
                    <h1 class="h5 h4-md fw-extrabold text-dark mb-1">Laporan & Rekapitulasi Bimbingan Konseling</h1>
                    <p class="text-muted fs-xs mb-0">Cetak dokumen laporan berkop resmi sekolah untuk arsip dan evaluasi periodik BK.</p>
                </div>
                <div>
                    <a href="{{ route('panel.laporan.print', request()->query()) }}" target="_blank" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-xs fs-xs text-nowrap d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-printer"></i> Cetak / Ekspor Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Select Report Type -->
    <div class="card card-app border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.laporan.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fs-xs text-muted fw-bold text-uppercase mb-1">Jenis Laporan</label>
                    <select name="type" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="konseling" {{ $type === 'konseling' ? 'selected' : '' }}>Laporan Agenda Konseling</option>
                        <option value="ims" {{ $type === 'ims' ? 'selected' : '' }}>Laporan Rekapitulasi IMS (180 Butir)</option>
                        <option value="program" {{ $type === 'program' ? 'selected' : '' }}>Laporan Program BK (Prota / Promes)</option>
                        <option value="pengaduan" {{ $type === 'pengaduan' ? 'selected' : '' }}>Laporan Pengaduan & Perlindungan Anak</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label fs-xs text-muted fw-bold text-uppercase mb-1">Tahun Ajaran</label>
                    <select name="academic_year_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($academicYears as $y)
                            <option value="{{ $y->id }}" {{ $academicYearId == $y->id ? 'selected' : '' }}>TP {{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label fs-xs text-muted fw-bold text-uppercase mb-1">Semester</label>
                    <select name="semester_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>Semester {{ $s->semester }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fs-xs text-muted fw-bold text-uppercase mb-1">Kelas / Rombel</label>
                    <select name="classroom_id" class="form-select form-select-sm fs-xs" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas (Agregat) --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-sm btn-mint text-white w-100 rounded-pill fw-bold shadow-xs fs-xs py-1.5">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="card card-app border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
        <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark fs-sm">Pratinjau Data Laporan</span>
            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">Asas Kerahasiaan Terlindungi</span>
        </div>
        <div class="table-responsive">
            @if($type === 'konseling')
                <table class="table table-hover align-middle mb-0 fs-xs">
                    <thead class="table-light fs-xxs text-muted text-uppercase" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="min-width: 120px;" class="ps-3.5">No. Tiket</th>
                            <th style="min-width: 160px;">Nama Murid & Kelas</th>
                            <th style="min-width: 200px;">Tujuan / Kebutuhan</th>
                            <th style="min-width: 140px;">Layanan BK</th>
                            <th style="min-width: 120px;" class="text-center">Status</th>
                            <th style="min-width: 140px;">Konselor PIC</th>
                            <th style="min-width: 100px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $row)
                            <tr>
                                <td class="ps-3.5 font-monospace fw-bold text-mint fs-xs">{{ $row->ticket_number }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $row->student->name }}</div>
                                    <div class="text-muted fs-xxs">{{ $row->student->classrooms->first()?->name ?? '-' }}</div>
                                </td>
                                <td>{{ $row->purpose }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $row->serviceType?->name ?? '-' }}</span></td>
                                <td class="text-center"><span class="badge {{ $row->status_badge_class }} rounded-pill fs-xxs">{{ $row->status_label }}</span></td>
                                <td>{{ $row->assignedCounselor?->name ?? 'Belum Ditugaskan' }}</td>
                                <td class="text-end pe-3.5 text-nowrap">
                                    <a href="{{ route('panel.konseling.show', $row->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fs-xxs fw-semibold" title="Buka Detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data konseling pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'ims')
                <table class="table table-hover align-middle mb-0 fs-xs">
                    <thead class="table-light fs-xxs text-muted text-uppercase" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 70px;" class="text-center ps-3.5">Rank</th>
                            <th style="width: 80px;" class="text-center">No Butir</th>
                            <th>Pernyataan Masalah</th>
                            <th style="width: 140px;">Bidang</th>
                            <th style="width: 180px;">Rekomendasi Layanan</th>
                            <th style="width: 130px;" class="text-center pe-3.5">Frekuensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['topProblems'] as $idx => $row)
                            <tr>
                                <td class="text-center fw-bold ps-3.5">{{ $idx + 1 }}</td>
                                <td class="text-center text-muted fw-bold">#{{ $row->statement_number }}</td>
                                <td class="fw-semibold text-dark">{{ $row->statement_text }}</td>
                                <td><span class="badge bg-light text-dark border rounded-pill fs-xxs">{{ $row->field_name }}</span></td>
                                <td><span class="badge bg-teal-subtle text-mint rounded fs-xxs">{{ $row->service_name }}</span></td>
                                <td class="text-center fw-bold text-danger pe-3.5">{{ $row->total_selected }} Siswa</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data instrumen terkirim pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'program')
                <table class="table table-hover align-middle mb-0 fs-xs">
                    <thead class="table-light fs-xxs text-muted text-uppercase" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 120px;" class="ps-3.5">Rencana Bulan</th>
                            <th>Nama Program Layanan</th>
                            <th style="width: 140px;">Bidang</th>
                            <th style="width: 180px;">Layanan BK</th>
                            <th style="width: 110px;" class="text-center">Sasaran</th>
                            <th style="width: 80px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $p)
                            <tr>
                                <td class="fw-semibold text-dark ps-3.5">{{ $p->month_planned ?: 'Fleksibel' }}</td>
                                <td class="fw-bold text-dark">{{ $p->name }}</td>
                                <td><span class="badge bg-primary-subtle text-primary rounded-pill fs-xxs">{{ $p->guidanceField?->name }}</span></td>
                                <td><span class="badge bg-light text-dark border fs-xxs">{{ $p->serviceType?->name }}</span></td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fs-xxs">Kelas {{ $p->target_level }}</span></td>
                                <td class="text-end pe-3.5 text-nowrap">
                                    <form action="{{ route('panel.program-bk.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 26px; height: 26px;" title="Hapus Program">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada program yang direncanakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'pengaduan')
                <table class="table table-hover align-middle mb-0 fs-xs">
                    <thead class="table-light fs-xxs text-muted text-uppercase" style="letter-spacing: 0.04em;">
                        <tr>
                            <th style="width: 130px;" class="ps-3.5">No. Tiket</th>
                            <th style="width: 160px;">Pelapor</th>
                            <th>Kategori Kejadian</th>
                            <th style="width: 120px;" class="text-center">Urgensi</th>
                            <th style="width: 120px;" class="text-center">Status</th>
                            <th style="width: 150px;">Konselor PIC</th>
                            <th style="width: 90px;" class="text-end pe-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $cmp)
                            <tr>
                                <td class="ps-3.5 font-monospace fw-bold text-danger fs-xs">{{ $cmp->ticket_number }}</td>
                                <td>{{ $cmp->is_anonymous ? 'Anonim (Dirahasiakan)' : ($cmp->student?->name ?? 'Murid') }}</td>
                                <td class="fw-semibold text-dark">{{ $cmp->category }}</td>
                                <td class="text-center"><span class="badge {{ $cmp->urgency_badge_class }} text-uppercase fs-xxs">{{ $cmp->urgency_level }}</span></td>
                                <td class="text-center"><span class="badge {{ $cmp->status_badge_class }} rounded-pill fs-xxs">{{ $cmp->status_label }}</span></td>
                                <td>{{ $cmp->handledBy?->name ?? 'Belum Ditugaskan' }}</td>
                                <td class="text-end pe-3.5 text-nowrap">
                                    <a href="{{ route('panel.pengaduan.show', $cmp->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fs-xxs fw-semibold" title="Buka Detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada pengaduan pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
