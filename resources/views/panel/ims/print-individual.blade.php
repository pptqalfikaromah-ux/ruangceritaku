<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pengolahan AUM/IMS - {{ $assessment->student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 18mm 15mm 18mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
            line-height: 1.35;
        }
        .page {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        .page-break {
            page-break-before: always;
            margin-top: 30px;
        }
        .badge-rahasia {
            display: inline-block;
            border: 1.5px solid #d32f2f;
            color: #d32f2f;
            font-weight: bold;
            font-size: 9.5pt;
            letter-spacing: 1.5px;
            padding: 3px 12px;
            text-transform: uppercase;
        }
        .header-title {
            text-align: center;
            margin-top: -24px;
            margin-bottom: 20px;
        }
        .header-title h1 {
            font-size: 12.5pt;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title h2 {
            font-size: 10.5pt;
            font-weight: normal;
            font-style: italic;
            margin: 0 0 2px 0;
        }
        .header-title h3 {
            font-size: 10.5pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .identity-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .identity-table td {
            padding: 2px 4px;
            vertical-align: top;
        }
        .table-aum {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 15px;
        }
        .table-aum th, .table-aum td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .table-aum th {
            text-align: center;
            font-weight: bold;
            background: #fdfdfd;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .fst-italic {
            font-style: italic;
        }
        .consult-note {
            font-style: italic;
            font-size: 10.5pt;
            margin-top: 10px;
            margin-bottom: 25px;
        }
        .signature-box {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        .signature-box td {
            vertical-align: top;
            width: 50%;
        }
        .page2-header-box {
            border: 1.5px solid #000;
            padding: 8px 12px;
            width: 58%;
            margin-bottom: 18px;
            font-size: 10pt;
        }
        .page2-header-box td {
            padding: 1.5px 3px;
            vertical-align: top;
        }
        .page2-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .field-group {
            margin-bottom: 14px;
        }
        .field-title {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 3px;
        }
        .statement-item {
            font-size: 10pt;
            margin-left: 14px;
            margin-bottom: 2px;
            text-align: justify;
        }
        .heavy-section {
            margin-top: 22px;
            padding-top: 10px;
            border-top: 1px dashed #555;
        }
        .heavy-title {
            font-weight: bold;
            font-size: 10.5pt;
            text-decoration: underline;
            margin-bottom: 6px;
        }
        .no-print {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
        }
        .btn {
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
        }
        .btn-print {
            background: #0d9488;
            color: #fff;
        }
        .btn-print:hover {
            background: #0f766e;
        }
        .btn-close {
            background: #e2e8f0;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .page {
                max-width: 100%;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi Khusus Pratinjau Layar -->
    <div class="no-print">
        <div>
            <strong>Pratinjau Format Cetak Dokumen AUM / IMS</strong> &bull; Format 3 : Individual Siswa
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg style="width: 14px; height: 14px; vertical-align: -2px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Dokumen (Print / PDF)
            </button>
            <button onclick="window.close()" class="btn btn-close">Tutup</button>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- HALAMAN 1: TABEL REKAPITULASI HASIL PENGOLAHAN -->
    <!-- ============================================== -->
    <div class="page">
        <!-- Cap Rahasia -->
        <div>
            <span class="badge-rahasia">RAHASIA</span>
        </div>

        <!-- Judul Kop -->
        <div class="header-title">
            <h1>HASIL PENGOLAHAN AUM / IMS</h1>
            <h2>Seri Umum - Format 3 : Siswa SLTP/MTs</h2>
            <h3>(INDIVIDUAL)</h3>
        </div>

        <!-- Identitas Murid -->
        <table class="identity-table">
            <tr>
                <td style="width: 18%;">Nama Siswa</td>
                <td style="width: 32%;">: <strong>{{ $assessment->student->name }}</strong></td>
                <td style="width: 18%;">Sekolah</td>
                <td style="width: 32%;">: {{ app_setting('school_name', "MTs Ma'arif NU 1 Sumpiuh") }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>: {{ $assessment->student->gender === 'L' ? 'Laki-laki' : ($assessment->student->gender === 'P' ? 'Perempuan' : ($assessment->student->gender ?? '-')) }}</td>
                <td>Kelas</td>
                <td>: {{ $assessment->student->classrooms->first()?->name ?? 'Semua Rombel' }}</td>
            </tr>
            <tr>
                <td>Usia</td>
                <td>: {{ $assessment->student->birth_date ? \Carbon\Carbon::parse($assessment->student->birth_date)->age . ' tahun' : '-' }}</td>
                <td>Tanggal pengisian</td>
                <td>: {{ $assessment->submitted_at ? $assessment->submitted_at->translatedFormat('d F Y') : ($assessment->created_at ? $assessment->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y')) }}</td>
            </tr>
        </table>

        <!-- Tabel Rekapitulasi Format Resmi AUM -->
        <table class="table-aum">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 28%;">LAYANAN BK</th>
                    <th colspan="3" style="width: 52%;">JENIS MASALAH</th>
                    <th rowspan="2" style="width: 20%;">N.M.Y.B<br><span style="font-weight: normal; font-size: 8.5pt;">(Nomor Masalah Yang Berat)</span></th>
                </tr>
                <tr>
                    <th style="width: 36%;">NOMOR MASALAH</th>
                    <th style="width: 8%;">JML</th>
                    <th style="width: 8%;">%</th>
                </tr>
                <tr style="background: #fafafa; font-size: 8.5pt;">
                    <th class="text-center">1</th>
                    <th class="text-center">2</th>
                    <th class="text-center">3</th>
                    <th class="text-center">4</th>
                    <th class="text-center">5</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fieldsData as $idx => $row)
                    <tr>
                        <td class="fw-bold">
                            {{ $idx + 1 }}. {{ $row['service']->name }} ({{ $row['total_statements'] }})
                        </td>
                        <td style="word-break: break-word; font-size: 9.5pt;">
                            @if(count($row['selected_numbers']) > 0)
                                {{ implode(', ', $row['selected_numbers']) }}
                            @else
                                <span class="text-center d-block">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $row['count'] > 0 ? $row['count'] : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $row['count'] > 0 ? number_format($row['percentage'], 2, ',', '.') . '%' : '-' }}
                        </td>
                        <td class="text-center" style="word-break: break-word; font-size: 9.5pt;">
                            @if(count($row['heavy_numbers']) > 0)
                                <strong>{{ implode(', ', $row['heavy_numbers']) }}</strong>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                <!-- Baris Rekapitulasi Keseluruhan -->
                <tr style="font-weight: bold; background: #fafafa;">
                    <td>
                        KESELURUHAN ({{ $totalStatementsAll }})
                    </td>
                    <td class="fst-italic text-center">
                        Sama dengan Diatas
                    </td>
                    <td class="text-center">
                        {{ $totalSelectedAll }}
                    </td>
                    <td class="text-center">
                        {{ number_format($overallPercentage, 2, ',', '.') }}%
                    </td>
                    <td class="fst-italic text-center">
                        Sama Dengan Diatas
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Keterangan Konsultasi Masalah -->
        <div class="consult-note">
            Ingin mengkonsultasikan masalah kepada : 
            <strong><u>{{ $assessment->consult_to ?? 'Guru BK / Konselor' }}</u></strong>
        </div>

        <!-- Kolom Tanda Tangan -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 25px; font-size: 10pt;">
            <tr>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    Mengetahui,<br>
                    Kepala {{ app_setting('school_name', "Madrasah / Sekolah") }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: center;">
                    {{ app_setting('school_city', 'Sumpiuh') }}, {{ $assessment->submitted_at ? $assessment->submitted_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}<br>
                    Guru Bimbingan dan Konseling / Konselor
                </td>
            </tr>
            <tr>
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
            </tr>
            <tr>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ app_setting('headmaster_name', "H. Ahmad Bachjatul 'Ulum, S.Ag., M.Pd.I.") }}</u></strong><br>
                    <span>NIP. {{ app_setting('headmaster_nip', '197508142005011004') }}</span>
                </td>
                <td style="vertical-align: top; text-align: center;">
                    <strong><u>{{ $counselor->name ?? auth()->user()->name }}</u></strong><br>
                    <span>NIP. {{ $counselor->nip ?? ($counselor->nuptk ?? '-') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- ============================================== -->
    <!-- HALAMAN 2: RINCIAN BUTIR PERMASALAHAN SISWA   -->
    <!-- ============================================== -->
    <div class="page page-break">
        <!-- Box Identitas Pojok Kiri Atas Sesuai Dokumen AUM Halaman 2 -->
        <div class="page2-header-box">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 38%;">Nama Siswa</td>
                    <td>: <strong>{{ $assessment->student->name }}</strong></td>
                </tr>
                <tr>
                    <td>NIS / NISN</td>
                    <td>: {{ $assessment->student->nis ?? ($assessment->student->nisn ?? '-') }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>: {{ $assessment->student->gender === 'L' ? 'Laki-laki' : ($assessment->student->gender === 'P' ? 'Perempuan' : ($assessment->student->gender ?? '-')) }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $assessment->student->classrooms->first()?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Mengisi</td>
                    <td>: {{ $assessment->submitted_at ? $assessment->submitted_at->translatedFormat('d-m-Y') : now()->translatedFormat('d-m-Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Judul Halaman 2 -->
        <div class="page2-title">
            KETERANGAN HASIL PENGOLAHAN AUM / IMS PER LAYANAN BK
        </div>

        <!-- Daftar Masalah Terpilih Per Layanan BK -->
        @foreach($fieldsData as $idx => $row)
            <div class="field-group">
                <div class="field-title">
                    {{ $idx + 1 }}. {{ $row['service']->name }}
                </div>
                @if(count($row['selected_answers']) > 0)
                    @foreach($row['selected_answers'] as $ans)
                        <div class="statement-item">
                            - {{ sprintf('%03d', $ans->statement?->statement_number) }} - {{ $ans->statement?->statement_text }}
                            <span style="font-size: 8.5pt; color: #555; background: #eee; padding: 1px 4px; border-radius: 3px;">
                                [{{ $ans->statement?->guidanceField?->name ?? 'Bidang' }}]
                            </span>
                            @if($ans->is_heavy)
                                <strong style="color: #b45309; font-size: 8.5pt;">(⭐ Masalah Paling Mengganggu)</strong>
                            @endif
                            @if($ans->student_note)
                                <em style="color: #444; font-size: 9.5pt;">(Catatan Siswa: "{{ $ans->student_note }}")</em>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="statement-item fst-italic" style="color: #666;">
                        - Tidak ada butir masalah yang dialami pada layanan ini.
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Bagian Masalah Yang Dirasakan Paling Berat (N.M.Y.B) -->
        <div class="heavy-section">
            <div class="heavy-title">
                N.M.Y.B (Nomor Masalah Yang Berat / Paling Mengganggu)
            </div>
            @if(count($heavyStatementsAll) > 0)
                @foreach($heavyStatementsAll as $ha)
                    <div class="statement-item" style="font-weight: 500;">
                        - <strong>{{ sprintf('%03d', $ha->statement?->statement_number) }}</strong> - {{ $ha->statement?->statement_text }}
                        @if($ha->student_note)
                            <em style="color: #444; font-size: 9.5pt;">(Catatan Siswa: "{{ $ha->student_note }}")</em>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="statement-item fst-italic" style="color: #666;">
                    - Tidak ada butir masalah yang ditandai sangat berat oleh siswa.
                </div>
            @endif
        </div>

        <!-- Catatan Penutup Tambahan dari Murid jika ada -->
        @if($assessment->final_notes)
            <div style="margin-top: 18px; padding: 10px; border: 1px solid #999; background: #fafafa; font-size: 9.5pt;">
                <strong>Narasi / Catatan Curhat Tambahan dari Murid:</strong><br>
                <span class="fst-italic">"{{ $assessment->final_notes }}"</span>
            </div>
        @endif
    </div>

</body>
</html>
