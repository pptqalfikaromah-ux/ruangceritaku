<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan Darurat Siswa - {{ $student?->name ?? ($pengaduan->reporter_name ?? 'Murid') }} - {{ $pengaduan->ticket_number }}</title>
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
        * { box-sizing: border-box; }
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
        .page.f4-paper { max-width: 820px; }

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
        .btn-print { background: #0d9488; color: #fff; }
        .btn-print:hover { background: #0f766e; }
        .btn-back { background: #e2e8f0; color: #1e293b; }

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
        .kop-text { text-align: center; }
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
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
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
            width: 32px;
            text-align: center;
            font-weight: bold;
        }
        .col-label {
            width: 220px;
            font-weight: bold;
        }
        .col-content {
            text-align: justify;
        }

        /* TANDA TANGAN */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none;
            padding: 4px;
            text-align: center;
            vertical-align: top;
            font-size: 10.5pt;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print { display: none !important; }
            .page {
                box-shadow: none;
                padding: 0;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- ACTIONS BAR (TIDAK IKUT TERCETAK) -->
    <div class="no-print">
        <a href="{{ url()->previous() ?: route('panel.pengaduan.index') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div style="display: flex; gap: 8px; align-items: center;">
            <span style="font-size: 12px; color: #64748b; margin-right: 4px;">Kertas:</span>
            <button type="button" class="btn" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 5px 12px;" onclick="setPaperSize('a4')">A4</button>
            <button type="button" class="btn" style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 5px 12px;" onclick="setPaperSize('f4')">F4</button>
            <button type="button" class="btn btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak Laporan PDF
            </button>
        </div>
    </div>

    <!-- HALAMAN CETAK -->
    <div class="page">
        <!-- KOP SURAT -->
        <table class="kop-table">
            <tr>
                <td style="width: 85px;">
                    <img src="{{ app_logo() }}" alt="Logo Madrasah" class="kop-logo">
                </td>
                <td class="kop-text">
                    <div class="kop-school">{{ app_setting('school_name', 'MADRASAH TSANAWIYAH AL-FALAH') }}</div>
                    <div class="kop-address">
                        {{ app_setting('school_address', 'Jl. Raya Pendidikan No. 45, Kecamatan Sumpiuh, Kabupaten Banyumas') }}<br>
                        Telp/HP: {{ app_setting('school_phone', '(0281) 123456') }} &bull; Email: {{ app_setting('school_email', 'admin@ruangceritaku.sch.id') }}
                    </div>
                </td>
                <td style="width: 85px;"></td>
            </tr>
        </table>
        <div class="kop-divider"></div>

        <!-- JUDUL -->
        <div class="report-title-box">
            <h1 class="report-title">ARSIP BERITA ACARA LAYANAN DARURAT & PENGADUAN SISWA</h1>
            <div class="report-subtitle">TIM PENCEGAHAN DAN PENANGANAN KEKERASAN / BIMBINGAN KONSELING</div>
            <div style="font-size: 9.5pt; font-family: monospace; color: #444; margin-top: 4px;">No. Registrasi Pengaduan: {{ $pengaduan->ticket_number }}</div>
        </div>

        <!-- TABEL RINCIAN LAPORAN -->
        <table class="table-laporan">
            <tbody>
                <tr>
                    <td class="col-num">1.</td>
                    <td class="col-label">Nomor Tiket Pengaduan</td>
                    <td class="col-content">
                        <strong>{{ $pengaduan->ticket_number }}</strong>
                        @if($pengaduan->safety_risk_flag)
                            &nbsp;<span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 1px 6px; font-size: 9pt; border-radius: 4px; font-weight: bold;">(Risiko Keselamatan / Darurat)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="col-num">2.</td>
                    <td class="col-label">Waktu Masuk Laporan</td>
                    <td class="col-content">
                        {{ $pengaduan->created_at ? $pengaduan->created_at->translatedFormat('l, d F Y - H:i') . ' WIB' : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-num">3.</td>
                    <td class="col-label">Identitas Pelapor / Korban</td>
                    <td class="col-content">
                        <strong>{{ $student?->name ?? ($pengaduan->reporter_name ?? 'Murid') }}</strong><br>
                        NISN: {{ $student?->nisn ?? '-' }} &bull; Rombel: {{ $student?->classrooms->first()?->name ?? 'Siswa' }} &bull; JK: {{ $student?->gender_label ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-num">4.</td>
                    <td class="col-label">Kategori Masalah / Aduan</td>
                    <td class="col-content">
                        <strong>{{ $pengaduan->category }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="col-num">5.</td>
                    <td class="col-label">Waktu & Tempat Kejadian</td>
                    <td class="col-content">
                        Waktu: {{ $pengaduan->incident_date ? $pengaduan->incident_date->translatedFormat('d F Y') : '-' }}<br>
                        Lokasi: {{ $pengaduan->incident_location ?? 'Lingkungan Madrasah' }}
                    </td>
                </tr>
                <tr>
                    <td class="col-num">6.</td>
                    <td class="col-label">Uraian Kronologi Kejadian</td>
                    <td class="col-content">
                        <div style="white-space: pre-line; line-height: 1.55;">{{ $pengaduan->description }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="col-num">7.</td>
                    <td class="col-label">Tindakan Penanganan & Perlindungan</td>
                    <td class="col-content">
                        <div style="white-space: pre-line; line-height: 1.55;">
                            {{ $pengaduan->handling_notes ?? 'Telah dilakukan verifikasi faktual, mediasi/pendampingan psikologis, pengamanan lingkungan belajar, dan koordinasi dengan pihak terkait hingga tuntas.' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-num">8.</td>
                    <td class="col-label">Status Akhir & Tanggal Selesai</td>
                    <td class="col-content">
                        <strong>SELESAI DITANGANI / RESOLVED</strong>
                        <div style="font-size: 10pt; color: #333; margin-top: 2px;">
                            Ditangani oleh: <strong>{{ $counselor->name ?? 'Tim Bimbingan Konseling' }}</strong>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TANDA TANGAN (KIRI KEPALA SEKOLAH, KANAN GURU BK PENANGGUNG JAWAB) -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 10.5pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    Mengetahui,<br>
                    Kepala {{ app_setting('school_name', 'Madrasah / Sekolah') }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    {{ app_setting('school_city', 'Sumpiuh') }}, {{ now()->translatedFormat('d F Y') }}<br>
                    Konselor / Penanggung Jawab Layanan
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
                    <strong><u>{{ $counselor->name ?? (auth()->user()->name ?? 'Guru Bimbingan Konseling') }}</u></strong><br>
                    <span>NIP. {{ $counselor->nip_nuptk ?? '-' }}</span>
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
