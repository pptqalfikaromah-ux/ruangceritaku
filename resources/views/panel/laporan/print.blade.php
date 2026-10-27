<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - {{ app_setting('app_name', 'Ruang Ceritaku') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid max-w-4xl">
        <!-- Floating Print Button -->
        <div class="no-print mb-4 p-3 bg-light rounded border d-flex justify-content-between align-items-center">
            <span class="fs-sm text-muted">Pratinjau Cetak Dokumen Resmi BK</span>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm bg-success border-0">
                    Cetak Halaman Ini (Print / PDF)
                </button>
                <button onclick="window.close()" class="btn btn-light btn-sm border">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Kop Surat Resmi -->
        <div class="kop-surat d-flex align-items-center justify-content-center gap-3 text-center mb-4 pb-3 border-bottom border-dark border-2">
            <img src="{{ app_logo() }}" alt="Logo" style="height: 65px; width: auto; max-width: 80px; object-fit: contain;">
            <div>
                <h6 class="fw-bold text-uppercase mb-1" style="font-size: 0.85rem;">Kementerian Agama Republik Indonesia</h6>
                <h4 class="fw-bold text-uppercase mb-1" style="font-size: 1.25rem;">{{ app_setting('school_name', 'Nama Madrasah / Sekolah') }}</h4>
                <div class="fs-xs text-muted">
                    {{ app_setting('school_address', 'Alamat Sekolah Lengkap') }}
                    @if(app_setting('school_phone')) &bull; Telp: {{ app_setting('school_phone') }} @endif
                    @if(app_setting('school_email')) &bull; Email: {{ app_setting('school_email') }} @endif
                </div>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-4">
            <h5 class="fw-bold text-uppercase mb-1 text-decoration-underline">
                @if($type === 'konseling')
                    Laporan Pelaksanaan & Agenda Konseling Siswa
                @elseif($type === 'ims')
                    Laporan Rekapitulasi Instrumen Masalah Siswa (IMS 180 Butir)
                @elseif($type === 'program')
                    Laporan Rencana Program Bimbingan Dan Konseling
                @else
                    Laporan Penanganan Pengaduan Anak & Kasus Siswa
                @endif
            </h5>
            <div class="fs-xs text-muted">
                Tahun Ajaran: {{ $selectedYear?->name ?? 'Semua' }} &bull; Semester: {{ $selectedSemester?->semester ?? 'Semua' }}
                @if($selectedClassroom)
                    &bull; Kelas: {{ $selectedClassroom->name }}
                @endif
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-responsive mb-5">
            @if($type === 'konseling')
                <table class="table table-bordered table-sm fs-xs align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 100px;">No. Tiket</th>
                            <th>Nama Murid</th>
                            <th style="width: 80px;">Kelas</th>
                            <th>Tujuan / Kebutuhan Konseling</th>
                            <th style="width: 130px;">Layanan BK</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 130px;">Konselor PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $idx => $row)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="font-monospace text-center">{{ $row->ticket_number }}</td>
                                <td class="fw-semibold">{{ $row->student->name }}</td>
                                <td class="text-center">{{ $row->student->classrooms->first()?->name ?? '-' }}</td>
                                <td>{{ $row->purpose }}</td>
                                <td>{{ $row->serviceType?->name ?? '-' }}</td>
                                <td class="text-center">{{ $row->status_label }}</td>
                                <td>{{ $row->assignedCounselor?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-3">Tidak ada data tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'ims')
                <div class="mb-2 fs-xs fw-semibold">Jumlah Responden Valid: {{ $reportData['respondents'] }} Murid</div>
                <table class="table table-bordered table-sm fs-xs align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">Rank</th>
                            <th style="width: 70px;">No Butir</th>
                            <th>Pernyataan Masalah (Top Kebutuhan)</th>
                            <th style="width: 110px;">Bidang BK</th>
                            <th style="width: 160px;">Rekomendasi Layanan</th>
                            <th style="width: 100px;">Frekuensi Siswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['topProblems'] as $idx => $row)
                            <tr>
                                <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                                <td class="text-center font-monospace">#{{ $row->statement_number }}</td>
                                <td>{{ $row->statement_text }}</td>
                                <td class="text-center">{{ $row->field_name }}</td>
                                <td>{{ $row->service_name }}</td>
                                <td class="text-center fw-bold">{{ $row->total_selected }} Siswa</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3">Belum ada data asesmen terkirim.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'program')
                <table class="table table-bordered table-sm fs-xs align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 110px;">Rencana Bulan</th>
                            <th>Nama Program Layanan BK</th>
                            <th style="width: 110px;">Bidang</th>
                            <th style="width: 150px;">Layanan BK</th>
                            <th style="width: 80px;">Sasaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $idx => $p)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td>{{ $p->month_planned ?: 'Fleksibel' }}</td>
                                <td class="fw-semibold">{{ $p->name }}</td>
                                <td class="text-center">{{ $p->guidanceField?->name }}</td>
                                <td>{{ $p->serviceType?->name }}</td>
                                <td class="text-center">Kelas {{ $p->target_level }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3">Belum ada data program tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($type === 'pengaduan')
                <table class="table table-bordered table-sm fs-xs align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 120px;">No. Tiket</th>
                            <th style="width: 140px;">Pelapor</th>
                            <th>Kategori Kejadian</th>
                            <th style="width: 90px;">Urgensi</th>
                            <th style="width: 110px;">Status</th>
                            <th style="width: 140px;">Konselor PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $idx => $cmp)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="font-monospace text-center">{{ $cmp->ticket_number }}</td>
                                <td>{{ $cmp->is_anonymous ? 'Anonim (Dirahasiakan)' : ($cmp->student?->name ?? 'Murid') }}</td>
                                <td>{{ $cmp->category }}</td>
                                <td class="text-center text-uppercase">{{ $cmp->urgency_level }}</td>
                                <td class="text-center">{{ $cmp->status_label }}</td>
                                <td>{{ $cmp->handledBy?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3">Tidak ada data pengaduan tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Kolom Pengesahan Tanda Tangan -->
        <div class="row pt-3 text-center fs-xs">
            <div class="col-6">
                <div class="text-muted mb-5">Mengetahui,<br>Kepala {{ app_setting('school_name', 'Madrasah / Sekolah') }}</div>
                <div class="fw-bold text-dark text-decoration-underline">{{ app_setting('headmaster_name', '( .................................................... )') }}</div>
                <div class="text-muted">NIP. {{ app_setting('headmaster_nip', '....................................................') }}</div>
            </div>
            <div class="col-6">
                <div class="text-muted mb-5">{{ date('d F Y') }}<br>Koordinator Guru BK / Konselor</div>
                <div class="fw-bold text-dark text-decoration-underline">( .................................................... )</div>
                <div class="text-muted">NIP. ....................................................</div>
            </div>
        </div>
    </div>
</body>
</html>
