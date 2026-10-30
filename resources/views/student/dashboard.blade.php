@extends('layouts.student')

@section('title', 'Beranda')

@section('breadcrumb')
    <span style="color:var(--text-dark);font-weight:700;">Beranda</span>
@endsection

@section('content')
{{-- Student Profile Banner (EMIS-style) --}}
<div style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);border-radius:10px;padding:20px 24px;margin-bottom:20px;color:#fff;position:relative;overflow:hidden;">
    <div style="position:absolute;width:200px;height:200px;background:rgba(255,255,255,.06);border-radius:50%;top:-60px;right:60px;"></div>
    <div style="position:absolute;width:120px;height:120px;background:rgba(245,158,11,.12);border-radius:50%;bottom:-30px;right:20px;"></div>
    <div class="row g-3 align-items-center position-relative">
        <div class="col-auto">
            <img src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=f0fdfa&color=0d9488&bold=true&size=80' }}"
                 alt="{{ $student->name }}"
                 style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.5);box-shadow:0 4px 12px rgba(0,0,0,.15);">
        </div>
        <div class="col">
            <div style="font-size:.72rem;font-weight:700;opacity:.75;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;">
                <i class="bi bi-mortarboard-fill me-1"></i>{{ app_setting('school_name', 'Madrasah / Sekolah') }} &bull; Siswa Aktif
            </div>
            <div style="font-size:1.3rem;font-weight:800;line-height:1.2;margin-bottom:4px;">{{ $student->name }}</div>
            <div style="font-size:.8rem;opacity:.85;">
                <i class="bi bi-building me-1"></i>Kelas {{ $student->classrooms->first()?->name ?? 'Siswa' }}
                &nbsp;&bull;&nbsp;
                <i class="bi bi-card-text me-1"></i>NISN: {{ $student->nisn }}
            </div>
        </div>
        <div class="col-md-auto text-md-end mt-2 mt-md-0">
            <a href="{{ route('murid.konseling.ajukan') }}"
               style="display:inline-flex;align-items:center;gap:6px;background:#f59e0b;color:#1e293b;border:none;border-radius:6px;padding:9px 18px;font-weight:800;font-size:.83rem;text-decoration:none;box-shadow:0 2px 8px rgba(245,158,11,.4);transition:background .2s;">
                <i class="bi bi-send-check"></i> Ajukan Konseling
            </a>
        </div>
    </div>
</div>

{{-- Etalase Pamflet Kreativitas Siswa & Pengumuman Madrasah --}}
<x-pamphlet-carousel />
<x-announcements-widget />

@php
    $imsEnabled = app_setting('ims_enabled', '1') === '1';
    $statColClass = $imsEnabled ? 'col-sm-6 col-lg-3' : 'col-sm-6 col-lg-4';
    $agendaColClass = $imsEnabled ? 'col-lg-6' : 'col-12';
@endphp

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    @if($imsEnabled)
        {{-- IMS Status --}}
        <div class="{{ $statColClass }}">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                <div style="width:44px;height:44px;border-radius:10px;background:#f0fdfa;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-clipboard2-check" style="font-size:1.4rem;color:#0d9488;"></i>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Status IMS</div>
                    @if($currentAssessment && $currentAssessment->isSubmitted())
                        <div style="font-size:1rem;font-weight:800;color:#0d9488;">Terkirim</div>
                        <div style="font-size:.7rem;color:#94a3b8;">{{ $currentAssessment->submitted_at?->format('d M Y') }}</div>
                    @elseif($currentAssessment && $currentAssessment->isDraft())
                        <div style="font-size:1rem;font-weight:800;color:#f59e0b;">Draf</div>
                        <div style="font-size:.7rem;color:#94a3b8;">Belum dikirim</div>
                    @else
                        <div style="font-size:1rem;font-weight:800;color:#94a3b8;">Belum Isi</div>
                        <div style="font-size:.7rem;color:#94a3b8;">Silakan isi IMS</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- Konseling --}}
    <div class="{{ $statColClass }}">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#fff1f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-chat-heart" style="font-size:1.4rem;color:#e11d48;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Konseling</div>
                @if($activeCounseling)
                    <div style="font-size:1rem;font-weight:800;color:#e11d48;">{{ $activeCounseling->status_label }}</div>
                    <div style="font-size:.7rem;color:#94a3b8;">{{ Str::limit($activeCounseling->purpose, 20) }}</div>
                @else
                    <div style="font-size:1rem;font-weight:800;color:#94a3b8;">Tidak Ada</div>
                    <div style="font-size:.7rem;color:#94a3b8;">Belum ada tiket</div>
                @endif
            </div>
        </div>
    </div>
    {{-- Riwayat Konseling --}}
    <div class="{{ $statColClass }}">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-calendar-check" style="font-size:1.4rem;color:#d97706;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Total Sesi</div>
                <div style="font-size:1.4rem;font-weight:800;color:#d97706;line-height:1;">{{ $recentCounselings->count() }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">Pengajuan tercatat</div>
            </div>
        </div>
    </div>
    {{-- Pengaduan --}}
    <div class="{{ $statColClass }}">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="width:44px;height:44px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-shield-check" style="font-size:1.4rem;color:#ef4444;"></i>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;">Pengaduan</div>
                <div style="font-size:1.4rem;font-weight:800;color:#ef4444;line-height:1;">{{ $recentComplaints->count() }}</div>
                <div style="font-size:.7rem;color:#94a3b8;">Laporan tercatat</div>
            </div>
        </div>
    </div>
</div>

{{-- IMS + Agenda Konseling Row --}}
<div class="row g-4 mb-4">
    @if($imsEnabled)
        {{-- IMS Status Card --}}
        <div class="col-lg-6">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;height:100%;">
                <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;font-size:.88rem;color:#1e293b;"><i class="bi bi-clipboard2-check text-success me-2"></i>Instrumen Masalah Siswa (IMS)</span>
                </div>
                <div style="padding:16px;">
                    @if($currentAssessment && $currentAssessment->isSubmitted())
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <span style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">
                                <i class="bi bi-check-circle-fill me-1"></i>Terkirim ke Guru BK
                            </span>
                        </div>
                        <div style="font-weight:700;margin-bottom:4px;">IMS Telah Dikirimkan</div>
                        <div style="font-size:.8rem;color:#64748b;margin-bottom:14px;">
                            Terkirim {{ $currentAssessment->submitted_at?->translatedFormat('d F Y, H:i') }}. Guru BK sudah menerima lembar masalahmu.
                        </div>
                        <a href="{{ route('murid.ims.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#0d9488;color:#fff;border-radius:6px;padding:7px 16px;font-weight:700;font-size:.8rem;text-decoration:none;">
                            <i class="bi bi-eye"></i> Lihat Lembar IMS
                        </a>
                    @elseif($currentAssessment && $currentAssessment->isDraft())
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <span style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">
                                <i class="bi bi-hourglass-split me-1"></i>Tersimpan Sebagai Draf
                            </span>
                        </div>
                        <div style="font-weight:700;margin-bottom:4px;">Pengisian Belum Selesai</div>
                        <div style="font-size:.8rem;color:#64748b;margin-bottom:14px;">Kamu sudah menyimpan sebagian jawaban. Lanjutkan dan kirim untuk diproses Guru BK.</div>
                        <a href="{{ route('murid.ims.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#f59e0b;color:#1e293b;border-radius:6px;padding:7px 16px;font-weight:700;font-size:.8rem;text-decoration:none;">
                            <i class="bi bi-pencil-square"></i> Lanjutkan Pengisian
                        </a>
                    @else
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <span style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">
                                <i class="bi bi-dash-circle me-1"></i>Belum Mengisi
                            </span>
                        </div>
                        <div style="font-weight:700;margin-bottom:4px;">Daftar Masalah Siswa</div>
                        <div style="font-size:.8rem;color:#64748b;margin-bottom:14px;">Pilih layanan dan bidang masalah yang sedang kamu alami. Jawaban bersifat rahasia.</div>
                        <a href="{{ route('murid.ims.index') }}" style="display:inline-flex;align-items:center;gap:6px;background:#0d9488;color:#fff;border-radius:6px;padding:7px 16px;font-weight:700;font-size:.8rem;text-decoration:none;">
                            <i class="bi bi-clipboard2-check"></i> Mulai Isi IMS
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Agenda Konseling --}}
    <div class="{{ $agendaColClass }}">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;height:100%;">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:700;font-size:.88rem;color:#1e293b;"><i class="bi bi-calendar-heart me-2" style="color:#e11d48;"></i>Agenda Konseling Aktif</span>
            </div>
            <div style="padding:16px;">
                @if($activeCounseling)
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                        <span class="badge {{ $activeCounseling->status_badge_class }}" style="border-radius:20px;padding:3px 10px;font-size:.72rem;">
                            {{ $activeCounseling->status_label }}
                        </span>
                    </div>
                    <div style="font-weight:700;margin-bottom:4px;">{{ Str::limit($activeCounseling->purpose, 55) }}</div>
                    @if($activeCounseling->schedule)
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:10px 12px;margin:10px 0;font-size:.8rem;">
                            <div><i class="bi bi-calendar-event me-1" style="color:#0d9488;"></i> {{ $activeCounseling->schedule->scheduled_date->translatedFormat('l, d F Y') }}</div>
                            <div class="mt-1"><i class="bi bi-clock me-1" style="color:#0d9488;"></i> {{ $activeCounseling->schedule->start_time }} WIB &bull; {{ $activeCounseling->schedule->location }}</div>
                        </div>
                    @else
                        <div style="font-size:.8rem;color:#64748b;margin:8px 0 14px;">Permohonanmu sedang ditinjau oleh Guru BK. Jadwal akan segera ditentukan.</div>
                    @endif
                    <a href="{{ route('murid.konseling.show', $activeCounseling->id) }}" style="display:inline-flex;align-items:center;gap:6px;background:#0d9488;color:#fff;border-radius:6px;padding:7px 16px;font-weight:700;font-size:.8rem;text-decoration:none;">
                        <i class="bi bi-eye"></i> Lihat Detail & Konfirmasi
                    </a>
                @else
                    <div style="text-align:center;padding:24px 0;color:#94a3b8;">
                        <i class="bi bi-calendar-plus" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
                        <div style="font-size:.82rem;">Belum ada agenda konseling aktif.</div>
                        <div style="font-size:.75rem;margin-top:4px;">Isi IMS terlebih dahulu untuk mengajukan konseling.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent History --}}
<div class="row g-4">
    {{-- Riwayat Konseling --}}
    <div class="col-lg-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:700;font-size:.88rem;color:#1e293b;"><i class="bi bi-chat-dots me-2" style="color:#0d9488;"></i>Riwayat Konseling</span>
                <a href="{{ route('murid.konseling.index') }}" style="font-size:.75rem;color:#0d9488;font-weight:700;text-decoration:none;">Lihat Semua &rarr;</a>
            </div>
            <div>
                @forelse($recentCounselings as $c)
                    <a href="{{ route('murid.konseling.show', $c->id) }}"
                       style="display:block;padding:12px 16px;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background .15s;"
                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">
                            <span style="font-family:monospace;font-size:.72rem;font-weight:700;color:#0d9488;">{{ $c->ticket_number }}</span>
                            <span class="badge {{ $c->status_badge_class }}" style="font-size:.65rem;border-radius:20px;">{{ $c->status_label }}</span>
                        </div>
                        <div style="font-weight:700;font-size:.83rem;color:#1e293b;">{{ Str::limit($c->purpose, 50) }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">{{ $c->created_at->translatedFormat('d M Y') }}</div>
                    </a>
                @empty
                    <div style="text-align:center;padding:24px;color:#94a3b8;font-size:.8rem;">Belum ada riwayat pengajuan konseling.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Riwayat Pengaduan --}}
    <div class="col-lg-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
            <div style="padding:12px 16px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-weight:700;font-size:.88rem;color:#1e293b;"><i class="bi bi-shield-exclamation me-2" style="color:#ef4444;"></i>Riwayat Pengaduan</span>
                <a href="{{ route('murid.pengaduan.index') }}" style="font-size:.75rem;color:#0d9488;font-weight:700;text-decoration:none;">Lihat Semua &rarr;</a>
            </div>
            <div>
                @forelse($recentComplaints as $p)
                    <a href="{{ route('murid.pengaduan.show', $p->id) }}"
                       style="display:block;padding:12px 16px;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:background .15s;"
                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">
                            <span style="font-family:monospace;font-size:.72rem;font-weight:700;color:#ef4444;">{{ $p->ticket_number }}</span>
                            <span class="badge {{ $p->status_badge_class }}" style="font-size:.65rem;border-radius:20px;">{{ $p->status_label }}</span>
                        </div>
                        <div style="font-weight:700;font-size:.83rem;color:#1e293b;">{{ $p->category }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">{{ Str::limit($p->description, 55) }}</div>
                    </a>
                @empty
                    <div style="text-align:center;padding:24px;color:#94a3b8;font-size:.8rem;">Belum ada riwayat laporan pengaduan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
