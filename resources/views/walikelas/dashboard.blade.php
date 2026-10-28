@extends('layouts.walikelas')

@section('title', 'Beranda Wali Kelas')
@section('page_title', 'Beranda Wali Kelas')
@section('page_subtitle', $classroom ? 'Rombel ' . $classroom->name : 'Menunggu Penugasan')

@section('content')
{{-- 1. WELCOME PROFILE BANNER --}}
<div class="p-3 p-md-4 mb-3 mb-md-4 position-relative overflow-hidden text-white" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); border-radius: 14px; box-shadow: 0 4px 14px rgba(217, 119, 6, 0.25);">
    <div style="position: absolute; width: 220px; height: 220px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; top: -60px; right: 60px;"></div>
    <div style="position: absolute; width: 140px; height: 140px; background: rgba(13, 148, 136, 0.25); border-radius: 50%; bottom: -40px; right: 20px;"></div>

    <div class="row align-items-center g-3 position-relative z-1">
        <div class="col-auto">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #ffffff; color: #d97706; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        </div>
        <div class="col">
            <div class="badge rounded-pill bg-white text-warning-emphasis fw-extrabold px-2.5 py-1 mb-1 shadow-xs fs-xxs">
                PORTAL WALI KELAS &bull; {{ $classroom ? 'KELAS ' . $classroom->name : 'BELUM ADA ROMBEL' }}
            </div>
            <h3 class="fw-extrabold mb-0.5 fs-5 fs-md-4">
                Selamat Datang, {{ $teacher?->name ?? $user->name }}!
            </h3>
            <div class="small opacity-90 fs-xs">
                <i class="bi bi-building me-1"></i>{{ app_setting('school_name', 'Madrasah / Sekolah') }}
                @if($classroom)
                    &bull; {{ $totalStudents }} Murid Binaan
                @endif
            </div>
        </div>
        <div class="col-12 col-md-auto text-md-end mt-2 mt-md-0">
            <a href="{{ route('walikelas.aduan.create') }}" class="btn btn-light rounded-pill px-3.5 py-2 fw-bold text-warning-emphasis shadow-sm w-100 w-md-auto d-inline-flex align-items-center justify-content-center gap-1.5">
                <i class="bi bi-plus-circle-fill text-warning"></i> Lapor Kasus Murid ke BK
            </a>
        </div>
    </div>
</div>

{{-- 2. STATS CARDS (Kompak & Seimbang di Mobile 2x2, 4 Kolom di Desktop) --}}
<div class="row g-2 g-md-3 mb-3 mb-md-4">
    {{-- TOTAL MURID --}}
    <div class="col-6 col-lg-3">
        <div class="card-app p-2.5 p-sm-3 d-flex align-items-center gap-2.5 h-100">
            <div class="rounded-3 bg-teal-subtle text-mint d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted fs-xxs fw-bold text-uppercase text-truncate" style="letter-spacing: .03em;">Total Murid</div>
                <div class="fs-4 fw-black text-dark lh-1 my-0.5">{{ $totalStudents }}</div>
                <div class="text-muted fs-xxs d-none d-sm-block">Peserta Didik</div>
            </div>
        </div>
    </div>

    {{-- LAKI-LAKI --}}
    <div class="col-6 col-lg-3">
        <div class="card-app p-2.5 p-sm-3 d-flex align-items-center gap-2.5 h-100">
            <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                <i class="bi bi-gender-male"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted fs-xxs fw-bold text-uppercase text-truncate" style="letter-spacing: .03em;">Laki-Laki</div>
                <div class="fs-4 fw-black text-primary lh-1 my-0.5">{{ $maleStudentsCount }}</div>
                <div class="text-muted fs-xxs d-none d-sm-block">Siswa (L)</div>
            </div>
        </div>
    </div>

    {{-- PEREMPUAN --}}
    <div class="col-6 col-lg-3">
        <div class="card-app p-2.5 p-sm-3 d-flex align-items-center gap-2.5 h-100">
            <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                <i class="bi bi-gender-female"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted fs-xxs fw-bold text-uppercase text-truncate" style="letter-spacing: .03em;">Perempuan</div>
                <div class="fs-4 fw-black text-danger lh-1 my-0.5">{{ $femaleStudentsCount }}</div>
                <div class="text-muted fs-xxs d-none d-sm-block">Siswi (P)</div>
            </div>
        </div>
    </div>

    {{-- ADUAN KASUS --}}
    <div class="col-6 col-lg-3">
        <div class="card-app p-2.5 p-sm-3 d-flex align-items-center gap-2.5 h-100">
            <div class="rounded-3 bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div class="min-w-0">
                <div class="text-muted fs-xxs fw-bold text-uppercase text-truncate" style="letter-spacing: .03em;">Aduan Kasus</div>
                <div class="fs-4 fw-black text-warning-emphasis lh-1 my-0.5">{{ $totalComplaints }}</div>
                <div class="text-muted fs-xxs d-none d-sm-block">Ke Guru BK</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 g-md-4 mb-4">
    {{-- 3. DAFTAR MURID ROMBEL BINAAN --}}
    <div class="col-lg-7">
        <div class="card card-app h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-warning"></i> Daftar Murid {{ $classroom?->name ?? '' }}
                </h6>
                <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1">{{ $totalStudents }} Siswa</span>
            </div>
            <div class="card-body p-0">
                {{-- DESKTOP TABLE VIEW --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-xs text-muted">
                            <tr>
                                <th style="width: 45px;" class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th class="d-none d-sm-table-cell">NISN</th>
                                <th class="text-center" style="width: 60px;">L/P</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $stu)
                                <tr>
                                    <td class="text-center fw-bold text-muted fs-xs">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark fs-sm">{{ $stu->name }}</div>
                                        <div class="d-sm-none text-muted font-monospace fs-xxs">NISN: {{ $stu->nisn }}</div>
                                    </td>
                                    <td class="font-monospace small text-muted d-none d-sm-table-cell">{{ $stu->nisn }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $stu->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill fs-xxs px-2">
                                            {{ $stu->gender }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('walikelas.aduan.create', ['student_id' => $stu->id]) }}" class="btn btn-outline-warning btn-sm rounded-pill px-2.5 py-1 fw-bold fs-xs text-warning-emphasis" title="Lapor Kasus Anak Ini">
                                            <i class="bi bi-flag-fill text-warning me-0.5"></i> Lapor
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada data murid di rombel binaan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARD LIST VIEW --}}
                <div class="d-md-none">
                    @forelse($students as $stu)
                        <div class="p-3 d-flex align-items-center justify-content-between gap-2.5 border-bottom">
                            <div class="d-flex align-items-center gap-2.5 min-w-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 {{ $stu->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }}" style="width: 42px; height: 42px; font-size: 1rem;">
                                    {{ strtoupper(substr($stu->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark fs-sm text-truncate">{{ $stu->name }}</div>
                                    <div class="d-flex align-items-center gap-1.5 mt-0.5">
                                        <span class="badge {{ $stu->gender === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger' }} rounded-pill fs-xxs px-2">
                                            {{ $stu->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                        <span class="text-muted fs-xxs font-monospace">NISN: {{ $stu->nisn ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('walikelas.aduan.create', ['student_id' => $stu->id]) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1 fw-bold fs-xs text-warning-emphasis d-inline-flex align-items-center gap-1 shadow-xs">
                                    <i class="bi bi-flag-fill text-warning"></i> Lapor
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-people fs-2 d-block mb-1 text-muted opacity-50"></i>
                            <span class="fs-xs">Belum ada data murid di rombel ini.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 4. ADUAN KASUS TERAKHIR KE GURU BK --}}
    <div class="col-lg-5">
        <div class="card card-app h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-flag-fill text-danger"></i> Aduan Kasus ke Guru BK
                </h6>
                <a href="{{ route('walikelas.aduan.index') }}" class="small text-warning-emphasis fw-bold text-decoration-none">
                    Semua Aduan &rarr;
                </a>
            </div>
            <div class="card-body p-3">
                @forelse($recentComplaints as $comp)
                    <div class="p-2.5 rounded-3 border mb-2.5 bg-white shadow-xs">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                            <span class="fw-bold text-dark small">{{ $comp->student?->name }}</span>
                            <span class="badge {{ $comp->status_badge_class }} fs-xxs rounded-pill px-2 py-0.5">
                                {{ $comp->status_label }}
                            </span>
                        </div>
                        <div class="small fw-semibold text-dark mb-1">{{ $comp->title }}</div>
                        <div class="text-muted fs-xxs mb-1.5">
                            <i class="bi bi-calendar-event me-1"></i>{{ $comp->incident_date->format('d M Y') }} &bull; {{ $comp->category_label }}
                        </div>
                        @if($comp->counselor_response)
                            <div class="p-1.5 rounded-2 bg-teal-subtle text-mint fs-xxs mb-1.5">
                                <i class="bi bi-check2-circle me-1"></i><strong>Respon BK:</strong> {{ Str::limit($comp->counselor_response, 45) }}
                            </div>
                        @endif
                        <div class="text-end">
                            <a href="{{ route('walikelas.aduan.show', $comp) }}" class="btn btn-light btn-sm rounded-pill px-2.5 py-1 border fw-bold fs-xxs text-muted">
                                Detail Kasus &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success mb-2" style="width: 52px; height: 52px; font-size: 1.5rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="fw-bold fs-sm text-dark">Kondisi Kelas Terpantau Aman</div>
                        <div class="fs-xs text-muted mt-0.5">Belum ada aduan kasus siswa yang diajukan ke Guru BK.</div>
                        <a href="{{ route('walikelas.aduan.create') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1.5 fw-bold mt-2.5 text-warning-emphasis">
                            <i class="bi bi-plus-circle me-1"></i> Lapor Kasus Murid
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- 5. PAMFLET KREATIVITAS & PENGUMUMAN MADRASAH (Di Tempatkan di Bawah Agar Fokus Utama Tugas Wali Kelas Muncul Lebih Dulu) --}}
<div class="mb-4">
    <x-pamphlet-carousel />
    <x-announcements-widget />
</div>
@endsection
