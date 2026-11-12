<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Layanan Kelompok - {{ $group->ticket_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .header-kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }
        .header-kop h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-kop h1 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-kop p {
            margin: 0;
            font-size: 9pt;
            font-style: italic;
        }
        .doc-title {
            text-align: center;
            margin-bottom: 18px;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 12pt;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .doc-title span {
            font-size: 10pt;
            display: block;
            margin-top: 3px;
        }
        table.meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5pt;
        }
        table.meta-table td {
            padding: 3px 4px;
            vertical-align: top;
        }
        table.member-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 16px;
            font-size: 10pt;
        }
        table.member-table th, table.member-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }
        table.member-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .signatures {
            margin-top: 35px;
            width: 100%;
            page-break-inside: avoid;
        }
        .sig-col {
            width: 50%;
            float: left;
            text-align: center;
        }
        .clear {
            clear: both;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 15px; right: 20px; z-index: 999; background: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
        <button onclick="window.print()" style="background: #0d9488; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Kartu Sesi
        </button>
        <button onclick="window.close()" style="background: #e2e8f0; color: #334155; border: none; padding: 8px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-left: 6px;">
            Tutup
        </button>
    </div>

    <!-- KOP -->
    <div class="header-kop">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h1>{{ app_setting('school_name', 'MADRASAH TSANAWIYAH AL KAROMAH') }}</h1>
        <p>{{ app_setting('school_address', 'Jl. Pendidikan No. 01, Jawa Tengah') }} &bull; Layanan Bimbingan dan Konseling</p>
    </div>

    <!-- TITLE -->
    <div class="doc-title">
        <h3>KARTU BUKTI LAYANAN {{ strtoupper($group->service_type_label) }}</h3>
        <span>Nomor Tiket: <strong>{{ $group->ticket_number }}</strong></span>
    </div>

    <!-- META TABLE -->
    <table class="meta-table">
        <tr>
            <td style="width: 25%;">Jenis Layanan</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%;"><strong>{{ $group->service_type_label }}</strong></td>
        </tr>
        <tr>
            <td>Topik Bahasan</td>
            <td>:</td>
            <td><strong>{{ $group->topic }}</strong></td>
        </tr>
        <tr>
            <td>Hari / Tanggal</td>
            <td>:</td>
            <td><strong>{{ $group->scheduled_date ? $group->scheduled_date->translatedFormat('l, d F Y') : '-' }}</strong></td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>Pukul {{ $group->start_time ?? '-' }} - {{ $group->end_time ?? '-' }} WIB</td>
        </tr>
        <tr>
            <td>Tempat Pertemuan</td>
            <td>:</td>
            <td>{{ $group->location ?? 'Ruang BK' }}</td>
        </tr>
        @if($group->meeting_link)
            <tr>
                <td>Tautan Daring</td>
                <td>:</td>
                <td>{{ $group->meeting_link }}</td>
            </tr>
        @endif
        <tr>
            <td>Konselor Pembimbing</td>
            <td>:</td>
            <td>{{ $group->counselor?->name ?? 'Guru BK' }}</td>
        </tr>
    </table>

    <!-- DAFTAR ANGGOTA KELOMPOK -->
    <p style="font-weight: bold; margin-bottom: 4px;">Daftar Peserta Sesi Kelompok:</p>
    <table class="member-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 44%;">Nama Peserta</th>
                <th style="width: 20%;">NISN</th>
                <th style="width: 16%;">Kelas</th>
                <th style="width: 12%;">Peran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td><strong>{{ $group->leader?->name }}</strong></td>
                <td style="text-align: center;">{{ $group->leader?->nisn ?? '-' }}</td>
                <td style="text-align: center;">{{ $group->leader?->classrooms->first()?->name ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold;">Ketua</td>
            </tr>
            @foreach($group->members as $idx => $m)
                <tr>
                    <td style="text-align: center;">{{ $idx + 2 }}</td>
                    <td>{{ $m->name }}</td>
                    <td style="text-align: center;">{{ $m->nisn ?? '-' }}</td>
                    <td style="text-align: center;">{{ $m->classrooms->first()?->name ?? '-' }}</td>
                    <td style="text-align: center;">Anggota</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 9.5pt; font-style: italic; margin-top: 10px;">
        * Harap seluruh peserta kelompok hadir tepat waktu di lokasi pertemuan sesuai jadwal yang telah ditetapkan.
    </p>

    <!-- TANDA TANGAN -->
    <div class="signatures">
        <div class="sig-col">
            Ketua Kelompok Murid,<br><br><br><br>
            <strong>{{ $group->leader?->name }}</strong><br>
            NISN. {{ $group->leader?->nisn ?? '-' }}
        </div>
        <div class="sig-col">
            {{ app_setting('school_city', 'Semarang') }}, {{ date('d F Y') }}<br>
            Guru BK / Konselor Pembimbing<br><br><br><br>
            <strong>{{ $group->counselor?->name ?? 'Tim Guru BK' }}</strong><br>
            NIP. {{ $group->counselor?->nip ?? '-' }}
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>

