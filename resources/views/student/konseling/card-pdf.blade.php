<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Tiket Konseling - {{ $konseling->ticket_number }} - {{ $student->name }}</title>
    <link rel="icon" href="{{ app_logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            line-height: 1.4;
            padding: 25px 15px;
        }

        /* ACTIONS BAR (NO PRINT) */
        .actions-bar {
            max-width: 460px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #0d9488;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(13,148,136,.25);
            text-decoration: none;
        }
        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 7px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        /* KARTU TIKET RINGKAS (SMALL TICKET CARD) */
        .ticket-card {
            max-width: 460px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1.5px solid #0d9488;
            overflow: hidden;
            position: relative;
        }

        /* HEADER KARTU */
        .ticket-header {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: #ffffff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .school-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 8px;
            padding: 3px;
            flex-shrink: 0;
        }
        .school-info {
            flex-grow: 1;
            min-width: 0;
        }
        .school-name {
            font-size: 0.88rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .school-addr {
            font-size: 0.7rem;
            opacity: 0.9;
            margin-top: 2px;
            line-height: 1.2;
        }

        /* BADGE TIKET STRIP */
        .ticket-strip {
            background: #f0fdfa;
            border-bottom: 1px solid #ccfbf1;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .ticket-num {
            font-family: 'Space Mono', monospace;
            background: #0d9488;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }

        /* BODY TIKET */
        .ticket-body {
            padding: 16px 20px;
        }

        /* TABEL DATA RINGKAS */
        .ticket-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        .ticket-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .ticket-table .lbl {
            width: 32%;
            color: #64748b;
            font-weight: 500;
        }
        .ticket-table .sep {
            width: 4%;
            color: #94a3b8;
            text-align: center;
        }
        .ticket-table .val {
            width: 64%;
            font-weight: 700;
            color: #0f172a;
        }

        /* KOTAK JADWAL KHUSUS */
        .schedule-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            margin: 12px 0;
        }
        .schedule-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .schedule-item:last-child {
            margin-bottom: 0;
        }
        .schedule-icon {
            color: #0d9488;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        .schedule-text {
            font-size: 0.82rem;
            color: #0f172a;
            font-weight: 700;
        }
        .schedule-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
        }

        /* PERFORASI / LUBANG TIKET */
        .ticket-divider {
            position: relative;
            height: 1px;
            border-top: 1.5px dashed #cbd5e1;
            margin: 14px -20px;
        }
        .ticket-divider::before, .ticket-divider::after {
            content: '';
            position: absolute;
            top: -9px;
            width: 16px;
            height: 16px;
            background: #f1f5f9;
            border-radius: 50%;
            border: 1.5px solid #0d9488;
        }
        .ticket-divider::before {
            left: -8px;
            clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);
        }
        .ticket-divider::after {
            right: -8px;
            clip-path: polygon(0 0, 50% 0, 50% 100%, 0 100%);
        }

        /* FOOTER TIKET */
        .ticket-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 4px;
        }
        .qr-placeholder {
            text-align: left;
        }
        .qr-code {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            color: #64748b;
            letter-spacing: 0.1em;
            line-height: 1.3;
        }
        .sign-area {
            text-align: center;
            font-size: 0.75rem;
        }
        .sign-area .counselor-name {
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #334155;
            padding-bottom: 2px;
            margin-top: 30px;
        }
        .sign-area .counselor-role {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 2px;
        }

        .ticket-notice {
            background: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.7rem;
            color: #854d0e;
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            body { background: #ffffff; padding: 10px; }
            .actions-bar { display: none !important; }
            .ticket-card {
                box-shadow: none !important;
                border: 1.5px solid #000 !important;
                max-width: 420px !important;
                margin: 0 auto !important;
            }
            .ticket-header {
                background: #0d9488 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .ticket-strip {
                background: #f0fdfa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .ticket-divider::before, .ticket-divider::after {
                background: #ffffff !important;
                border-color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi Layar -->
    <div class="actions-bar">
        <a href="javascript:history.back()" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-print" type="button">
            <i class="bi bi-printer-fill"></i> Cetak Kartu
        </button>
    </div>

    <!-- Kartu Tiket Kecil -->
    <div class="ticket-card">
        <!-- Header Sekolah -->
        <div class="ticket-header">
            @if(app_logo())
                <img src="{{ app_logo() }}" alt="Logo" class="school-logo">
            @endif
            <div class="school-info">
                <div class="school-name">{{ app_setting('school_name', 'Bimbingan & Konseling Sekolah') }}</div>
                <div class="school-addr">{{ app_setting('school_address', 'Layanan Bimbingan Konseling Siswa') }}</div>
            </div>
        </div>

        <!-- Strip Nomor Tiket -->
        <div class="ticket-strip">
            <span class="ticket-title"><i class="bi bi-ticket-perforated me-1"></i> Kartu Tiket Konseling</span>
            <span class="ticket-num">{{ $konseling->ticket_number }}</span>
        </div>

        <!-- Body Tiket -->
        <div class="ticket-body">
            <table class="ticket-table">
                <tr>
                    <td class="lbl">Nama Siswa</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $student->name }}</td>
                </tr>
                <tr>
                    <td class="lbl">Kelas / NISN</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $student->classrooms->first()?->name ?? '-' }} ({{ $student->nisn ?? '-' }})</td>
                </tr>
                <tr>
                    <td class="lbl">Topik Layanan</td>
                    <td class="sep">:</td>
                    <td class="val">{{ \Illuminate\Support\Str::limit($konseling->purpose, 45) }}</td>
                </tr>
            </table>

            <!-- Kotak Jadwal & Lokasi -->
            @php
                $sched = $konseling->schedule;
            @endphp
            <div class="schedule-box">
                <div class="schedule-item">
                    <i class="bi bi-calendar-event schedule-icon"></i>
                    <div>
                        <div class="schedule-text">
                            {{ $sched ? $sched->scheduled_date->translatedFormat('l, d F Y') : 'Belum Ditentukan' }}
                        </div>
                        <div class="schedule-sub">Pukul {{ $sched ? $sched->start_time . ' - ' . $sched->end_time . ' WIB' : '-' }}</div>
                    </div>
                </div>

                <div class="schedule-item" style="margin-top: 6px;">
                    <i class="bi {{ $konseling->isOnlineMeet() ? 'bi-camera-video' : 'bi-geo-alt' }} schedule-icon"></i>
                    <div>
                        <div class="schedule-text">
                            @if($konseling->isOnlineMeet())
                                Google Meet (Online)
                            @else
                                {{ $sched?->location ?? 'Ruang Bimbingan Konseling' }}
                            @endif
                        </div>
                        <div class="schedule-sub">Guru BK: {{ $sched?->counselor?->user?->name ?? $sched?->counselor?->name ?? 'Konselor BK' }}</div>
                    </div>
                </div>
            </div>

            <!-- Garis Perforasi Tiket -->
            <div class="ticket-divider"></div>

            <!-- Footer Tiket: Status & Tanda Tangan -->
            <div class="ticket-footer">
                <div class="qr-placeholder">
                    <div class="qr-code">
                        [ TIKET RESMI ]<br>
                        STATUS: {{ strtoupper($konseling->student_status_label) }}<br>
                        DICETAK: {{ now()->translatedFormat('d/m/Y') }}
                    </div>
                </div>
                <div class="sign-area">
                    <div style="font-size: 0.7rem; color: #64748b;">Guru Pembimbing BK,</div>
                    <div class="counselor-name">{{ $sched?->counselor?->user?->name ?? $sched?->counselor?->name ?? 'Guru BK' }}</div>
                    <div class="counselor-role">Unit Bimbingan Konseling</div>
                </div>
            </div>

            <div class="ticket-notice">
                <i class="bi bi-info-circle me-1"></i> Mohon hadir tepat waktu sesuai jadwal yang telah disepakati.
            </div>
        </div>
    </div>

</body>
</html>
