@extends('layouts.panel')

@section('title', 'Laporan & Rekapitulasi BK')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Laporan Resmi</span>
                <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-xs fw-medium rounded-pill">Data Aktual & Rekapitulasi</span>
            </div>
            <h1 class="h4 fw-bold text-dark mb-1">Laporan & Rekapitulasi Bimbingan Konseling</h1>
            <p class="text-muted small mb-0">Cetak dokumen laporan berkop resmi madrasah untuk arsip dan evaluasi periodik BK.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panel.laporan.print', request()->query()) }}" target="_blank" class="btn btn-primary btn-sm bg-emerald border-0 text-white shadow-sm">
                <i class="bi bi-printer me-1"></i> Cetak / Ekspor Laporan
            </a>
        </div>
    </div>

    <!-- Filter & Select Report Type -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('panel.laporan.index') }}" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <label class="form-label fs-xs text-muted fw-semibold mb-1">Jenis Laporan</label>
                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="konseling" {{ $type === 'konseling' ? 'selected' : '' }}>Laporan Agenda Konseling</option>
                        <option value="ims" {{ $type === 'ims' ? 'selected' : '' }}>Laporan Rekapitulasi IMS (180 Butir)</option>
                        <option value="program" {{ $type === 'program' ? 'selected' : '' }}>Laporan Program BK (Prota / Promes)</option>
                        <option value="pengaduan" {{ $type === 'pengaduan' ? 'selected' : '' }}>Laporan Pengaduan & Perlindungan Anak</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fs-xs text-muted fw-semibold mb-1">Tahun Ajaran</label>
                    <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($academicYears as $y)
                            <option value="{{ $y->id }}" {{ $academicYearId == $y->id ? 'selected' : '' }}>TP {{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fs-xs text-muted fw-semibold mb-1">Semester</label>
                    <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>Semester {{ $s->semester }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fs-xs text-muted fw-semibold mb-1">Kelas / Rombel</label>
                    <select name="classroom_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas (Agregat Sekolah) --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary bg-emerald border-0 w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark fs-sm">Pratinjau Data Laporan Terpilih</span>
            <span class="badge bg-light text-muted border">Asas Kerahasiaan Terlindungi</span>
        </div>
        <div class="table-responsive">
            @if($type === 'konseling')
                <table class="table table-hover align-middle mb-0 fs-sm">
                    <thead class="table-light fs-xs text-muted text-uppercase">
                        <tr>
                            <th style="width: 120px;">No. Tiket</th>
                            <th>Nama Murid & Kelas</th>
                            <th>Tujuan / Kebutuhan</th>
                            <th style="width: 140px;">Layanan BK</th>
                            <th style="width: 130px;" class="text-center">Status</th>
                            <th style="width: 140px;">Konselor PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $row)
                            <tr>
                                <td class="font-monospace fw-bold text-emerald fs-xs">{{ $row->ticket_number }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $row->student->name }}</div>
                                    <div class="text-muted fs-xs">{{ $row->student->classrooms->first()?->name ?? '-' }}</div>
                                </td>
                                <td>{{ $row->purpose }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $row->serviceType?->name ?? '-' }}</span></td>
                                <td class="text-center"><span class="badge {{ $row->status_badge_class }} rounded-pill fs-xs">{{ $row->status_label }}</span></td>
                                <td>{{ $row->assignedCounselor?->name ?? 'Belum Ditugaskan' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data konseling pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'ims')
                <table class="table table-hover align-middle mb-0 fs-sm">
                    <thead class="table-light fs-xs text-muted text-uppercase">
                        <tr>
                            <th style="width: 70px;" class="text-center">Rank</th>
                            <th style="width: 80px;" class="text-center">No Butir</th>
                            <th>Pernyataan Masalah</th>
                            <th style="width: 140px;">Bidang</th>
                            <th style="width: 180px;">Rekomendasi Layanan</th>
                            <th style="width: 130px;" class="text-center">Frekuensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['topProblems'] as $idx => $row)
                            <tr>
                                <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                <td class="text-center text-muted fw-bold">#{{ $row->statement_number }}</td>
                                <td class="fw-medium text-dark">{{ $row->statement_text }}</td>
                                <td><span class="badge bg-light text-dark border rounded-pill">{{ $row->field_name }}</span></td>
                                <td><span class="badge bg-emerald-subtle text-emerald rounded">{{ $row->service_name }}</span></td>
                                <td class="text-center fw-bold text-danger">{{ $row->total_selected }} Siswa</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data instrumen terkirim pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'program')
                <table class="table table-hover align-middle mb-0 fs-sm">
                    <thead class="table-light fs-xs text-muted text-uppercase">
                        <tr>
                            <th style="width: 120px;">Rencana Bulan</th>
                            <th>Nama Program Layanan</th>
                            <th style="width: 140px;">Bidang</th>
                            <th style="width: 180px;">Layanan BK</th>
                            <th style="width: 110px;" class="text-center">Sasaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $p)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $p->month_planned ?: 'Fleksibel' }}</td>
                                <td class="fw-bold text-dark">{{ $p->name }}</td>
                                <td><span class="badge bg-primary-subtle text-primary rounded-pill">{{ $p->guidanceField?->name }}</span></td>
                                <td><span class="badge bg-light text-dark border">{{ $p->serviceType?->name }}</span></td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary">Kelas {{ $p->target_level }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada program yang direncanakan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'pengaduan')
                <table class="table table-hover align-middle mb-0 fs-sm">
                    <thead class="table-light fs-xs text-muted text-uppercase">
                        <tr>
                            <th style="width: 130px;">No. Tiket</th>
                            <th style="width: 160px;">Pelapor</th>
                            <th>Kategori Kejadian</th>
                            <th style="width: 130px;" class="text-center">Urgensi</th>
                            <th style="width: 130px;" class="text-center">Status</th>
                            <th style="width: 150px;">Konselor PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $cmp)
                            <tr>
                                <td class="font-monospace fw-bold text-danger fs-xs">{{ $cmp->ticket_number }}</td>
                                <td>{{ $cmp->is_anonymous ? 'Anonim (Dirahasiakan)' : ($cmp->student?->name ?? 'Murid') }}</td>
                                <td class="fw-medium text-dark">{{ $cmp->category }}</td>
                                <td class="text-center"><span class="badge {{ $cmp->urgency_badge_class }} text-uppercase fs-xs">{{ $cmp->urgency_level }}</span></td>
                                <td class="text-center"><span class="badge {{ $cmp->status_badge_class }} rounded-pill fs-xs">{{ $cmp->status_label }}</span></td>
                                <td>{{ $cmp->handledBy?->name ?? 'Belum Ditugaskan' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada pengaduan pada filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
