<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konseling Individual - {{ $student->name }} - {{ $konseling->ticket_number }}</title>
    <link rel="icon" href="{{ app_logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style id="pageOrientationStyle">
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
            line-height: 1.35;
        }
        .page {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 4px;
        }
        .page.f4-paper {
            max-width: 820px;
        }

        /* ACTIONS BAR (NO PRINT) */
        .no-print {
            max-width: 800px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .btn {
            padding: 7px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print {
            background: #0d9488;
            color: #fff;
        }
        .btn-print:hover {
            background: #0f766e;
        }
        .btn-back {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
        }
        .kop-line1 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .kop-line2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 1px 0;
        }
        .kop-school {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .kop-address {
            font-size: 10pt;
            margin: 0;
            color: #222;
        }
        .kop-divider {
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 8px;
            margin-bottom: 18px;
        }

        /* JUDUL LAPORAN */
        .report-title-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .report-subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        /* TABEL LAPORAN FORMAL */
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 10.5pt;
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }
        .col-num {
            width: 5%;
            text-align: center;
            font-weight: bold;
        }
        .col-label {
            width: 32%;
            font-weight: normal;
        }
        .col-content {
            width: 63%;
            text-align: justify;
        }

        /* TANDA TANGAN */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10.5pt;
        }
        .signature-table td {
            border: none;
            padding: 4px;
            vertical-align: top;
            text-align: center;
        }

        /* RESPONSIVE UNTUK LAYAR SMARTPHONE / HP */
        @media (max-width: 768px) {
            body {
                padding: 8px 6px;
                background: #e2e8f0;
                font-size: 10pt;
            }
            .no-print {
                padding: 10px 12px;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                margin-bottom: 10px;
            }
            .no-print .controls-group {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                width: 100%;
            }
            .no-print .controls-group .btn {
                flex: 1 1 auto;
                justify-content: center;
            }
            .page {
                padding: 16px 12px;
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                border-radius: 6px;
            }
            .kop-logo {
                width: 50px;
                height: 50px;
            }
            .kop-school {
                font-size: 11pt;
            }
            .kop-address {
                font-size: 7.5pt;
                line-height: 1.3;
            }
            .kop-divider {
                margin-top: 6px;
                margin-bottom: 12px;
            }
            .report-title {
                font-size: 11pt;
            }
            .table-laporan {
                font-size: 8.5pt;
                margin-bottom: 16px;
            }
            .table-laporan th, .table-laporan td {
                padding: 4px 6px;
            }
            .col-num {
                width: 7%;
            }
            .col-label {
                width: 33%;
            }
            .col-content {
                width: 60%;
            }
            .signature-table {
                font-size: 8.5pt;
                margin-top: 10px;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff;
                padding: 0;
            }
            .page {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Navigasi & Cetak PDF -->
    <div class="no-print">
        <div>
            <strong style="color: #0f766e;"><i class="bi bi-file-earmark-pdf-fill me-1"></i> Laporan Konseling Individual</strong>
            <span class="text-muted small ms-1 d-none d-sm-inline">&bull; Dokumen Arsip Resmi BK</span>
        </div>
        <div class="controls-group" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <!-- Pilihan Ukuran Kertas A4 / F4 -->
            <div style="display: flex; align-items: center; gap: 4px;">
                <label for="paperSizeSelect" style="font-size: 12px; font-weight: 600; color: #475569;">Format:</label>
                <select id="paperSizeSelect" onchange="setPaperSize(this.value)" style="padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; background: #f8fafc; cursor: pointer;">
                    <option value="a4" selected>📄 A4 (210 x 297 mm)</option>
                    <option value="f4">📜 F4 / Folio (215 x 330 mm)</option>
                </select>
            </div>
            <a href="{{ route('panel.konseling.show', $konseling->id) }}" class="btn btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-print">
                <i class="bi bi-printer"></i> Cetak PDF
            </button>
        </div>
    </div>

    <!-- Halaman Dokumen A4 -->
    <div class="page">
        <!-- KOP SURAT CUKUP NAMA SEKOLAH DAN ALAMAT -->
        <table class="kop-table">
            <tr>
                @if(app_logo())
                <td style="width: 75px; text-align: left;">
                    <img src="{{ app_logo() }}" alt="Logo" class="kop-logo">
                </td>
                @endif
                <td class="kop-text">
                    <div class="kop-school">{{ app_setting('school_name', 'SMA NEGERI 2 PAMEKASAN') }}</div>
                    <div class="kop-address">
                        Alamat : {{ app_setting('school_address', 'Jl. Jokotole 234 Pamekasan') }}
                        @if(app_setting('school_phone'))
                            &nbsp;&bull;&nbsp; Telp. {{ app_setting('school_phone') }}
                        @endif
                    </div>
                </td>
                @if(app_logo())
                <td style="width: 75px;"></td>
                @endif
            </tr>
        </table>
        <div class="kop-divider"></div>

        <!-- JUDUL DOKUMEN -->
        <div class="report-title-box">
            <h1 class="report-title">LAPORAN PELAKSANAAN LAYANAN KONSELING INDIVIDUAL</h1>
            <h2 class="report-subtitle">
                SEMESTER {{ strtoupper(active_semester()?->name ?? 'GENAP') }} TAHUN PELAJARAN {{ active_academic_year()?->name ?? '2026/2027' }}
            </h2>
        </div>

        <!-- TABEL 9 BUTIR FORMAL LAPORAN KONSELING SESUAI TEMPLATE RESMI -->
        <table class="table-laporan">
            <tbody>
                <tr>
                    <td class="col-num">1.</td>
                    <td class="col-label">Nama Konseli</td>
                    <td class="col-content"><strong>{{ $student->name }}</strong></td>
                </tr>
                <tr>
                    <td class="col-num">2.</td>
                    <td class="col-label">Kelas</td>
                    <td class="col-content">{{ $student->classrooms->first()?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-num">3.</td>
                    <td class="col-label">No. Absen / NISN</td>
                    <td class="col-content">{{ $student->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-num">4.</td>
                    <td class="col-label">Hari/ Tanggal</td>
                    <td class="col-content">
                        @php
                            $execDate = $schedule?->scheduled_date ?? ($service?->execution_date ?? ($konseling->completed_at ?? now()));
                        @endphp
                        {{ \Carbon\Carbon::parse($execDate)->translatedFormat('l/ d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="col-num">5.</td>
                    <td class="col-label">Pertemuan ke-</td>
                    <td class="col-content">{{ $meetingNumber }}</td>
                </tr>
                <tr>
                    <td class="col-num">6.</td>
                    <td class="col-label">Waktu/lama pertemuan</td>
                    <td class="col-content">
                        {{ $service?->duration_minutes ? $service->duration_minutes . ' menit' : '30 menit' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-num">7.</td>
                    <td class="col-label">Tempat</td>
                    <td class="col-content">
                        @if($konseling->isOnlineMeet() || ($schedule && $schedule->counseling_method === 'online_meet'))
                            Google Meet (Daring)
                        @else
                            {{ $schedule?->location ?? ($service?->location ?? 'Ruang BK') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-num">8.</td>
                    <td class="col-label">Gejala yang nampak</td>
                    <td class="col-content">
                        <div style="margin-bottom: 4px;">- {{ $konseling->purpose }}</div>
                        @if($konseling->description)
                            <div style="font-size: 10pt; color: #222;">{{ $konseling->description }}</div>
                        @endif
                        @if($konseling->additional_notes)
                            <div style="font-size: 9.5pt; font-style: italic; color: #444; margin-top: 4px;">Catatan: {{ $konseling->additional_notes }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-num">9.</td>
                    <td class="col-label">Hasil dan Evaluasi Konseling</td>
                    <td class="col-content">
                        @php
                            $evalText = $service?->evaluation_result ?? ($konseling->resolution_notes ?? 'Konseli menyadari permasalahan yang dialami, menunjukkan keterbukaan dalam sesi konseling dan menyepakati komitmen langkah perbaikan diri bersama Guru Bimbingan Konseling.');
                        @endphp
                        <div style="line-height: 1.5; white-space: pre-line;">- {{ $evalText }}</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TANDA TANGAN (KIRI KEPALA SEKOLAH, KANAN GURU BK) -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 10.5pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    Mengetahui,<br>
                    Kepala {{ app_setting('school_name', 'Sekolah') }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    {{ app_setting('school_city', 'Sumpiuh') }}, {{ \Carbon\Carbon::parse($execDate)->translatedFormat('d F Y') }}<br>
                    Guru Bimbingan Konseling
                </td>
            </tr>
            <tr>
                <td style="height: 65px;"></td>
                <td style="height: 65px;"></td>
            </tr>
            <tr>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ app_setting('headmaster_name', "H. Ahmad Bachjatul 'Ulum, S.Ag., M.Pd.I.") }}</u></strong><br>
                    <span>NIP. {{ app_setting('headmaster_nip', '197508142005011004') }}</span>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ ($counselor ?? null)?->user?->name ?? (($counselor ?? null)?->name ?? (auth()->user()?->name ?? 'Guru Bimbingan Konseling')) }}</u></strong><br>
                    <span>NIP. {{ ($counselor ?? null)?->nip ?? (($counselor ?? null)?->nuptk ?? '-') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <script>
        function setPaperSize(size) {
            const styleTag = document.getElementById('pageOrientationStyle');
            const pageEl = document.querySelector('.page');
            if (size === 'f4') {
                styleTag.innerHTML = '@page { size: 215mm 330mm portrait; margin: 15mm 20mm 15mm 20mm; }';
                if (pageEl) pageEl.classList.add('f4-paper');
            } else {
                styleTag.innerHTML = '@page { size: A4 portrait; margin: 15mm 20mm 15mm 20mm; }';
                if (pageEl) pageEl.classList.remove('f4-paper');
            }
        }
    </script>
</body>
</html>
