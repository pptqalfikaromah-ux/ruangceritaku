@extends('layouts.panel')

@section('title', 'Detail Layanan Kelompok: ' . $group->ticket_number)
@section('breadcrumb')
    <a href="{{ route('panel.layanan-kelompok.index') }}">Layanan Kelompok</a>
    <i class="bi bi-chevron-right fs-xxs text-muted"></i>
    <span class="current">{{ $group->ticket_number }}</span>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="badge {{ $group->service_type_badge_class }} rounded-pill px-3 py-1 fs-xxs fw-bold">
                            {{ $group->service_type_label }}
                        </span>
                        <span class="badge {{ $group->status_badge_class }} rounded-pill px-3 py-1 fs-xxs fw-bold">
                            {{ $group->status_label }}
                        </span>
                    </div>
                    <h1 class="h4 fw-extrabold text-dark mb-0.5">Tiket #{{ $group->ticket_number }}</h1>
                    <p class="text-muted fs-xs mb-0">Diajukan pada {{ $group->created_at->translatedFormat('l, d F Y - H:i') }} WIB</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('panel.layanan-kelompok.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fs-xs fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    @if($group->status === 'selesai')
                        <a href="{{ route('panel.layanan-kelompok.printReport', $group) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 fs-xs fw-bold">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Berita Acara PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Kolom Kiri: Rincian Kelompok & Anggota -->
        <div class="col-12 col-lg-7 space-y-3">
            <!-- Informasi Pokok Bahasan -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                    <i class="bi bi-card-text me-1 text-mint"></i> Rincian Pengajuan Kelompok
                </h6>

                <div class="mb-3">
                    <label class="text-muted fs-xxs text-uppercase fw-bold d-block mb-1">Topik Bahasan:</label>
                    <div class="fw-bold text-dark fs-5">{{ $group->topic }}</div>
                </div>

                <div class="mb-3 p-3 rounded-3 bg-light border">
                    <label class="text-muted fs-xxs text-uppercase fw-bold d-block mb-1">Latar Belakang / Masalah:</label>
                    <p class="text-dark fs-xs mb-0" style="line-height: 1.65;">{{ $group->description }}</p>
                </div>

                @if($group->group_goal)
                    <div class="mb-3 p-3 rounded-3 bg-teal-subtle border-teal">
                        <label class="text-mint fs-xxs text-uppercase fw-bold d-block mb-1">Harapan / Tujuan Kelompok:</label>
                        <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->group_goal }}</p>
                    </div>
                @endif

                <div class="row g-2 pt-2 border-top">
                    <div class="col-6">
                        <span class="text-muted fs-xxs d-block">Usulan Hari / Tanggal:</span>
                        <strong class="text-dark fs-xs">
                            {{ $group->preferred_date ? $group->preferred_date->translatedFormat('d M Y') : 'Kapan saja yang memungkinkan' }}
                        </strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted fs-xxs d-block">Usulan Sesi Waktu:</span>
                        <strong class="text-dark fs-xs">{{ $group->preferred_session ?? 'Fleksibel' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Susunan Anggota Kelompok -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <h6 class="fw-extrabold text-dark mb-0 fs-sm">
                        <i class="bi bi-people-fill me-1 text-mint"></i> Susunan Anggota Kelompok
                    </h6>
                    <span class="badge bg-teal-subtle text-mint px-2.5 py-1 rounded-pill fs-xxs fw-bold">
                        Total {{ $group->members->count() + 1 }} Peserta
                    </span>
                </div>

                <!-- Ketua Kelompok -->
                <div class="p-3 rounded-3 bg-light border mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-circle bg-mint text-white d-flex align-items-center justify-content-center fw-bold shadow-xs" style="width: 38px; height: 38px;">
                            <i class="bi bi-star-fill fs-6 text-warning"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill fs-xxs fw-bold mb-0.5">Ketua / Pemohon</span>
                            <div class="fw-bold text-dark fs-sm">{{ $group->leader?->name }}</div>
                            <div class="text-muted fs-xxs">
                                NISN: <code class="text-mint">{{ $group->leader?->nisn ?? '-' }}</code> &bull; Kelas: {{ $group->leader?->classrooms->first()?->name ?? 'Siswa' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anggota Lainnya -->
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 fs-xs">
                        <thead class="table-light text-muted fs-xxs text-uppercase">
                            <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th>Nama Anggota Teman</th>
                                <th>Kelas</th>
                                <th>NISN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($group->members as $idx => $member)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $member->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5 fs-xxs">
                                            {{ $member->classrooms->first()?->name ?? 'Siswa' }}
                                        </span>
                                    </td>
                                    <td>
                                        <code class="text-mint">{{ $member->nisn ?? '-' }}</code>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted fs-xs">
                                        Tidak ada anggota tambahan terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Penjadwalan & Laporan Konselor -->
        <div class="col-12 col-lg-5 space-y-3">
            <!-- Card 1: Form / Info Penjadwalan Sesi -->
            <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                    <i class="bi bi-calendar-check me-1 text-mint"></i> Jadwal & Konselor Pembimbing
                </h6>

                @if($group->status === 'menunggu_persetujuan' || request('edit_schedule'))
                    <!-- Form Penjadwalan -->
                    <form action="{{ route('panel.layanan-kelompok.schedule', $group) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fs-xs fw-bold">Konselor / Guru BK Pembimbing <span class="text-danger">*</span></label>
                            <select name="counselor_id" class="form-select form-select-sm fs-xs" required>
                                <option value="">-- Pilih Guru BK --</option>
                                @foreach($counselors as $c)
                                    <option value="{{ $c->id }}" {{ (old('counselor_id', $group->counselor_id) == $c->id) ? 'selected' : '' }}>
                                        {{ $c->name }} ({{ $c->user?->name ?? 'Konselor' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs fw-bold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="scheduled_date" class="form-control form-control-sm fs-xs" 
                                   value="{{ old('scheduled_date', $group->scheduled_date?->format('Y-m-d') ?? ($group->preferred_date?->format('Y-m-d') ?? date('Y-m-d'))) }}" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fs-xs fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control form-control-sm fs-xs" 
                                       value="{{ old('start_time', $group->start_time ?? '09:30') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fs-xs fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control form-control-sm fs-xs" 
                                       value="{{ old('end_time', $group->end_time ?? '10:15') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs fw-bold">Tempat / Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm fs-xs" 
                                   value="{{ old('location', $group->location ?? 'Ruang BK Lantai 1') }}" placeholder="Contoh: Ruang BK / Gazebo Sekolah" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs fw-bold">Tautan Google Meet (Opsional jika daring)</label>
                            <input type="url" name="meeting_link" class="form-control form-control-sm fs-xs" 
                                   value="{{ old('meeting_link', $group->meeting_link) }}" placeholder="https://meet.google.com/xxx-xxxx-xxx">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs fw-bold">Catatan Arahan untuk Siswa (Opsional)</label>
                            <textarea name="counselor_notes" rows="2" class="form-control form-control-sm fs-xs" 
                                      placeholder="Contoh: Seluruh anggota kelompok dimohon berkumpul tepat waktu.">{{ old('counselor_notes', $group->counselor_notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-mint text-white btn-sm w-100 rounded-pill fw-bold py-2 shadow-xs fs-xs">
                            <i class="bi bi-check-circle-fill me-1"></i> Konfirmasi & Tetapkan Jadwal
                        </button>
                    </form>
                @else
                    <!-- Tampilan Rincian Jadwal yang Sudah Ditetapkan -->
                    <div class="p-3 rounded-3 bg-light border space-y-2 mb-3">
                        <div>
                            <span class="text-muted fs-xxs d-block">Guru BK Pembimbing:</span>
                            <strong class="text-dark fs-xs">{{ $group->counselor?->name ?? 'Belum Ditentukan' }}</strong>
                        </div>
                        <div>
                            <span class="text-muted fs-xxs d-block">Waktu Pelaksanaan:</span>
                            <strong class="text-dark fs-xs">
                                <i class="bi bi-calendar-event me-1 text-mint"></i>{{ $group->scheduled_date?->translatedFormat('l, d F Y') }}
                            </strong>
                            <div class="text-muted fs-xs mt-0.5">
                                <i class="bi bi-clock me-1"></i>Pukul {{ $group->start_time }} - {{ $group->end_time }} WIB
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-xxs d-block">Lokasi:</span>
                            <strong class="text-dark fs-xs"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $group->location }}</strong>
                        </div>
                        @if($group->meeting_link)
                            <div>
                                <span class="text-muted fs-xxs d-block">Tautan Pertemuan Daring:</span>
                                <a href="{{ $group->meeting_link }}" target="_blank" class="text-mint fs-xs fw-bold text-decoration-none text-truncate d-block">
                                    <i class="bi bi-camera-video me-1"></i>{{ $group->meeting_link }}
                                </a>
                            </div>
                        @endif
                        @if($group->counselor_notes && $group->status === 'dijadwalkan')
                            <div class="pt-2 border-top">
                                <span class="text-muted fs-xxs d-block">Pesan Guru BK:</span>
                                <div class="text-dark fs-xs fst-italic">{{ $group->counselor_notes }}</div>
                            </div>
                        @endif
                    </div>

                    @if($group->status === 'dijadwalkan')
                        <div class="d-flex justify-content-end">
                            <a href="{{ request()->fullUrlWithQuery(['edit_schedule' => 1]) }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fs-xxs text-dark">
                                <i class="bi bi-pencil me-1"></i> Ubah Jadwal
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Card 2: Form Penyelesaian Sesi / Laporan Evaluasi Kelompok -->
            @if($group->status === 'dijadwalkan' || $group->status === 'selesai')
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <i class="bi bi-journal-check me-1 text-success"></i> Laporan Dinamika & Hasil Kelompok
                    </h6>

                    @if($group->status === 'selesai' && !request('edit_eval'))
                        <div class="space-y-2.5">
                            <div>
                                <span class="text-muted fs-xxs d-block">Durasi Pelaksanaan:</span>
                                <strong class="text-dark fs-xs">{{ $group->duration_minutes }} Menit</strong>
                            </div>
                            <div class="p-2.5 rounded-3 bg-light border">
                                <span class="text-muted fs-xxs d-block fw-bold mb-1">Dinamika Kelompok & Partisipasi:</span>
                                <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->counselor_notes }}</p>
                            </div>
                            <div class="p-2.5 rounded-3 bg-light border">
                                <span class="text-muted fs-xxs d-block fw-bold mb-1">Hasil Evaluasi & Kesepakatan:</span>
                                <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->evaluation_result }}</p>
                            </div>
                            @if($group->follow_up_plan)
                                <div class="p-2.5 rounded-3 bg-light border">
                                    <span class="text-muted fs-xxs d-block fw-bold mb-1">Rencana Tindak Lanjut:</span>
                                    <p class="text-dark fs-xs mb-0" style="line-height: 1.6;">{{ $group->follow_up_plan }}</p>
                                </div>
                            @endif
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-xxs">Selesai pada {{ $group->completed_at?->translatedFormat('d M Y, H:i') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['edit_eval' => 1]) }}" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 fs-xxs">
                                    <i class="bi bi-pencil me-1"></i> Edit Laporan
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Form Selesaikan Sesi -->
                        <form action="{{ route('panel.layanan-kelompok.complete', $group) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fs-xs fw-bold">Durasi Sesi (Menit) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_minutes" class="form-control form-control-sm fs-xs" 
                                       value="{{ old('duration_minutes', $group->duration_minutes ?? 45) }}" min="10" max="300" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-xs fw-bold">Dinamika Kelompok & Keterlibatan Anggota <span class="text-danger">*</span></label>
                                <textarea name="counselor_notes" rows="3" class="form-control form-control-sm fs-xs" 
                                          placeholder="Uraikan keaktifan anggota, keterbukaan, suasana interaksi antar murid..." required>{{ old('counselor_notes', $group->counselor_notes) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-xs fw-bold">Hasil Evaluasi & Kesepakatan Bersama <span class="text-danger">*</span></label>
                                <textarea name="evaluation_result" rows="3" class="form-control form-control-sm fs-xs" 
                                          placeholder="Uraikan pemahaman baru, komitmen kelompok, solusi yang dicapai..." required>{{ old('evaluation_result', $group->evaluation_result) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-xs fw-bold">Rencana Tindak Lanjut (Opsional)</label>
                                <textarea name="follow_up_plan" rows="2" class="form-control form-control-sm fs-xs" 
                                          placeholder="Contoh: Pemantauan interaksi kelas 2 minggu ke depan...">{{ old('follow_up_plan', $group->follow_up_plan) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success text-white btn-sm w-100 rounded-pill fw-bold py-2 shadow-xs fs-xs">
                                <i class="bi bi-check2-all me-1"></i> Selesaikan Sesi & Simpan Laporan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

