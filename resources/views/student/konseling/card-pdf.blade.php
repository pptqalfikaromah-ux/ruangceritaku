<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Konseling - {{ $konseling->ticket_number }} - {{ $student->name }}</title>
    <link rel="icon" href="{{ app_logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.5;
            padding: 30px 15px;
        }
        .page-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.07);
            padding: 35px 45px;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* ACTIONS BAR (NO PRINT) */
        .actions-bar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #0d9488;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13,148,136,.25);
            text-decoration: none;
            transition: all .2s ease;
        }
        .btn-print:hover { background: #0f766e; }
        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 9px 20px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-back:hover { background: #f1f5f9; color: #0f172a; }

        /* KOP SURAT */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #0f172a;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .kop-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-text {
            text-align: center;
            flex-grow: 1;
            padding: 0 15px;
        }
        .kop-kemenag {
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #047857;
        }
        .kop-school {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .kop-address {
            font-size: 0.76rem;
            color: #475569;
            line-height: 1.35;
        }

        /* CARD HEADER */
        .card-title-box {
            text-align: center;
            margin-bottom: 22px;
        }
        .card-main-title {
            font-size: 1.15rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #0f766e;
            text-decoration: underline;
            text-underline-offset: 4px;
        }
        .card-ticket {
            display: inline-block;
            background: #f0fdfa;
            border: 1px dashed #0d9488;
            color: #0f766e;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 0.82rem;
            letter-spacing: 0.05em;
            margin-top: 6px;
        }

        /* SECTION TABLES */
        .section-label {
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f766e;
            background: #f0fdfa;
            padding: 6px 12px;
            border-left: 4px solid #0d9488;
            margin-bottom: 10px;
            margin-top: 18px;
            border-radius: 0 6px 6px 0;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            margin-bottom: 10px;
        }
        .table-data td {
            padding: 6px 8px;
            vertical-align: top;
        }
        .table-data .lbl {
            width: 28%;
            font-weight: 600;
            color: #475569;
        }
        .table-data .sep {
            width: 3%;
            color: #94a3b8;
            text-align: center;
        }
        .table-data .val {
            width: 69%;
            font-weight: 700;
            color: #0f172a;
        }

        /* HIGHLIGHT BOX JADWAL */
        .schedule-highlight {
            background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);
            border: 2px solid #0d9488;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 14px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        .schedule-details {
            flex-grow: 1;
        }
        .schedule-time {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f766e;
        }
        .schedule-venue {
            font-size: 0.85rem;
            color: #1e293b;
            margin-top: 2px;
        }
        .badge-method {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .badge-meet { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-offline { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

        .meet-link-box {
            background: #eff6ff;
            border: 1px dashed #3b82f6;
            border-radius: 8px;
            padding: 10px 14px;
            margin: 10px 0;
            font-size: 0.82rem;
            color: #1e40af;
            word-break: break-all;
        }

        /* CATATAN / PETUNJUK */
        .notes-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.78rem;
            color: #92400e;
            margin-top: 14px;
            line-height: 1.45;
        }

        /* SIGNATURES */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
            page-break-inside: avoid;
        }
        .sign-box {
            width: 42%;
            text-align: center;
            font-size: 0.84rem;
        }
        .sign-title { font-weight: 600; color: #475569; margin-bottom: 60px; }
        .sign-name { font-weight: 800; color: #0f172a; text-decoration: underline; }
        .sign-nip { font-size: 0.78rem; color: #64748b; margin-top: 2px; }

        @media print {
            body { background: #ffffff; padding: 0; }
            .actions-bar { display: none !important; }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi di Layar -->
    <div class="actions-bar">
        <a href="javascript:history.back()" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="btn-print" type="button">
            <i class="bi bi-printer-fill"></i> Cetak / Unduh PDF
        </button>
    </div>

    <!-- Halaman Kartu Konseling -->
    <div class="page-container">
        <!-- KOP SURAT RESMI SEKOLAH / MADRASAH -->
        <div class="kop-wrapper">
            <img src="{{ app_logo() }}" alt="Logo Madrasah" class="kop-logo">
            <div class="kop-text">
                <div class="kop-school">{{ app_setting('school_name', 'MTs Ma\'arif NU 1 Sumpiuh') }}</div>
                <div class="kop-address">
                    {{ app_setting('school_address', 'Jl. Raya Sumpiuh, Banyumas, Jawa Tengah') }}
                </div>
            </div>
            <img src="{{ app_logo() }}" alt="Logo Madrasah" class="kop-logo" style="opacity: 0;">
        </div>

        <!-- JUDUL & NOMOR REGISTRASI -->
        <div class="card-title-box">
            <h1 class="card-main-title">KARTU BUKTI LAYANAN KONSELING SISWA</h1>
            <div class="card-ticket">NOMOR TIKET: {{ $konseling->ticket_number }}</div>
        </div>

        <!-- 1. IDENTITAS PESERTA DIDIK -->
        <div class="section-label">I. Identitas Peserta Didik</div>
        <table class="table-data">
            <tr>
                <td class="lbl">Nama Lengkap Murid</td>
                <td class="sep">:</td>
                <td class="val">{{ $student->name }}</td>
            </tr>
            <tr>
                <td class="lbl">NISN / NIS</td>
                <td class="sep">:</td>
                <td class="val">{{ $student->nisn }} / {{ $student->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Kelas / Rombongan Belajar</td>
                <td class="sep">:</td>
                <td class="val">{{ $student->classrooms->first()?->name ?? 'Kelas Aktif' }}</td>
            </tr>
            <tr>
                <td class="lbl">Jenis Kelamin</td>
                <td class="sep">:</td>
                <td class="val">{{ $student->gender === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
            </tr>
        </table>

        <!-- 2. DETAIL PERMOHONAN KONSELING -->
        <div class="section-label">II. Data Permohonan Konseling</div>
        <table class="table-data">
            <tr>
                <td class="lbl">Tujuan / Masalah Konseling</td>
                <td class="sep">:</td>
                <td class="val">{{ $konseling->purpose }}</td>
            </tr>
            <tr>
                <td class="lbl">Sumber Pengajuan</td>
                <td class="sep">:</td>
                <td class="val">
                    {{ $konseling->isFromIms() ? 'Tindak Lanjut Instrumen Masalah Siswa (IMS)' : 'Konseling Mandiri / Pribadi' }}
                </td>
            </tr>
            <tr>
                <td class="lbl">Bidang & Jenis Layanan</td>
                <td class="sep">:</td>
                <td class="val">
                    Bidang {{ $konseling->guidanceField?->name ?? 'Umum' }} 
                    • Layanan {{ $konseling->serviceType?->name ?? 'Konseling Perorangan' }}
                </td>
            </tr>
            <tr>
                <td class="lbl">Tanggal Diajukan</td>
                <td class="sep">:</td>
                <td class="val">{{ $konseling->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
        </table>

        <!-- 3. RENCANA JADWAL & TEMPAT / LINK GOOGLE MEET -->
        <div class="section-label">III. Penetapan Jadwal & Lokasi Sesi</div>
        @php
            $schedule = $konseling->schedule;
            $isOnline = $konseling->isOnlineMeet() || ($schedule && $schedule->counseling_method === 'online_meet');
            $counselorName = $konseling->assignedCounselor?->user?->name ?? $schedule?->counselor?->user?->name ?? 'Guru BK / Konselor Sekolah';
            $counselorNip = $konseling->assignedCounselor?->nip_nuptk ?? $schedule?->counselor?->nip_nuptk ?? '-';
        @endphp

        <div class="schedule-highlight">
            <div class="schedule-details">
                <div class="schedule-time">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $schedule?->scheduled_date ? $schedule->scheduled_date->translatedFormat('l, d F Y') : 'Jadwal Ditentukan Guru BK' }}
                    @if($schedule?->start_time)
                        • Pukul {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                    @endif
                </div>
                <div class="schedule-venue">
                    <strong>Lokasi / Media:</strong> 
                    @if($isOnline)
                        Daring melalui Google Meet
                    @else
                        {{ $schedule?->location ?? 'Ruang Bimbingan & Konseling (BK)' }}
                    @endif
                </div>
            </div>
            <div>
                @if($isOnline)
                    <span class="badge-method badge-meet"><i class="bi bi-camera-video me-1"></i>Online Meet</span>
                @else
                    <span class="badge-method badge-offline"><i class="bi bi-door-open me-1"></i>Tatap Muka</span>
                @endif
            </div>
        </div>

        @if($isOnline && $schedule?->meeting_link)
            <div class="meet-link-box">
                <strong><i class="bi bi-link-45deg me-1"></i>Tautan Google Meet:</strong><br>
                <a href="{{ $schedule->meeting_link }}" target="_blank" style="color:#1d4ed8; text-decoration:underline;">
                    {{ $schedule->meeting_link }}
                </a>
                <div style="font-size:0.75rem; color:#64748b; margin-top:4px;">
                    *Pastikan siswa sudah masuk ke ruang Google Meet 5 menit sebelum sesi dimulai menggunakan akun belajar / email aktif.
                </div>
            </div>
        @endif

        <table class="table-data">
            <tr>
                <td class="lbl">Guru BK / Konselor Pembimbing</td>
                <td class="sep">:</td>
                <td class="val">{{ $counselorName }} (NIP/NUPTK: {{ $counselorNip }})</td>
            </tr>
            <tr>
                <td class="lbl">Status Persetujuan</td>
                <td class="sep">:</td>
                <td class="val" style="color: #0d9488;">
                    <strong>Disetujui Rencana Pelaksanaan</strong>
                </td>
            </tr>
        </table>

        <!-- PETUNJUK PELAKSANAAN -->
        <div class="notes-box">
            <strong><i class="bi bi-info-circle me-1"></i>Petunjuk Peserta Didik:</strong><br>
            1. Harap menunjukkan kartu bukti ini kepada Bapak/Ibu guru pengajar di kelas saat meminta izin menghadiri sesi konseling tatap muka.<br>
            2. Seluruh proses konseling dijamin asas kerahasiaannya sesuai kode etik Bimbingan dan Konseling.<br>
            3. Jika terdapat kendala mendadak, silakan ajukan jadwal ulang melalui portal murid Ruang Ceritaku.
        </div>

        <!-- TANDA TANGAN -->
        <div class="signatures">
            <div class="sign-box">
                <div class="sign-title">Peserta Didik,</div>
                <div class="sign-name">{{ $student->name }}</div>
                <div class="sign-nip">NISN: {{ $student->nisn }}</div>
            </div>
            <div class="sign-box">
                <div class="sign-title">
                    {{ app_setting('school_city', 'Sumpiuh') }}, {{ now()->translatedFormat('d F Y') }}<br>
                    Guru Bimbingan Konseling,
                </div>
                <div class="sign-name">{{ $counselorName }}</div>
                <div class="sign-nip">NIP/NUPTK: {{ $counselorNip }}</div>
            </div>
        </div>
    </div>

</body>
</html>
