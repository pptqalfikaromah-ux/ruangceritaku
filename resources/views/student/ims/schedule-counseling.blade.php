@extends('layouts.student')

@section('title', 'Ajukan Permohonan Konseling Hasil IMS')
@section('page_title', 'Konseling Tindak Lanjut IMS')

@section('breadcrumb')
    <a href="{{ route('murid.dashboard') }}">Murid</a>
    <span>/</span>
    <a href="{{ route('murid.ims.index') }}">IMS</a>
    <span>/</span>
    <span class="active">Ajukan Konseling</span>
@endsection

@section('content')
<div class="space-y-4 pb-5">
    <!-- Header Page (Modern & Mobile Friendly) -->
    <div class="card border-0 rounded-4 bg-white p-3.5 p-md-4 shadow-sm" style="border: 1px solid #E2ECE9 !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                    <span class="badge rounded-pill px-2.5 py-1 fs-xs fw-semibold" style="background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe;">
                        <i class="bi bi-clipboard2-check me-1"></i>Tindak Lanjut Instrumen Masalah (IMS)
                    </span>
                    <span class="badge rounded-pill px-2.5 py-1 fs-xs fw-bold" style="background: #E6FAF5; color: #008F72;">
                        Konseling Guru BK
                    </span>
                </div>
                <h1 class="h4 fw-extrabold text-dark mb-1 tracking-tight" style="font-size: clamp(1.2rem, 2vw, 1.55rem);">
                    Pengajuan Konseling Berdasarkan Hasil Temuan IMS
                </h1>
                <p class="text-muted fs-xs mb-0" style="line-height: 1.6;">
                    Diskusikan butir masalah yang kamu temukan di lembar IMS bersama Guru BK / Konselor madrasah. Waktu dan jadwal sesi akan ditentukan dan disetujui langsung oleh Guru BK.
                </p>
            </div>
            <div>
                <a href="{{ route('murid.ims.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3.5 py-2 fw-semibold text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu IMS
                </a>
            </div>
        </div>
    </div>

    @if($existingPending)
        <div class="alert border-0 shadow-sm rounded-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between p-3.5 gap-3" 
             style="background: #FFF8E6; border: 1.5px solid #FDE68A !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: #FFB800; color: #1e293b;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark fs-sm">Permohonan Konseling IMS Sedang Berjalan</div>
                    <div class="fs-xs text-muted">Kamu sudah memiliki permohonan konseling IMS dengan nomor tiket <strong>#{{ $existingPending->ticket_number }}</strong> (status: <em>{{ $existingPending->status_label }}</em>).</div>
                </div>
            </div>
            <a href="{{ route('murid.konseling.show', $existingPending->id) }}" class="btn btn-sm btn-gold rounded-pill px-3.5 py-2 fw-bold text-nowrap">
                Lihat Detail Tiket
            </a>
        </div>
    @endif

    <div class="row g-4">
        <!-- Kolom Kiri: Rangkuman Temuan Masalah IMS Siswa -->
        <div class="col-lg-5 space-y-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden" style="border: 1px solid #E2ECE9 !important;">
                <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark fs-sm">
                        <i class="bi bi-check2-square text-mint me-1"></i> Masalah yang Kamu Tandai di IMS
                    </span>
                    @php
                        $totalMarked = $selectedAnswers->count() + ($customStatements ? $customStatements->count() : 0);
                    @endphp
                    <span class="badge rounded-pill px-2.5 py-1 fs-xs fw-bold" style="background: #E6FAF5; color: #008F72;">
                        {{ $totalMarked }} Butir Masalah
                    </span>
                </div>
                <div class="card-body p-3.5">
                    @if($selectedAnswers->isEmpty() && (!$customStatements || $customStatements->isEmpty()))
                        <div class="p-4 text-center text-muted fs-xs bg-light rounded-4">
                            <i class="bi bi-info-circle text-secondary fs-3 d-block mb-2"></i>
                            Kamu belum menandai butir masalah tertentu di IMS. Tidak apa-apa, kamu tetap dapat mengajukan konsultasi umum tindak lanjut bersama Guru BK.
                        </div>
                    @else
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden mb-3" style="max-height: 420px; overflow-y: auto;">
                            <!-- 1. Butir Standar -->
                            @foreach($selectedAnswers as $ans)
                                @php
                                    $stmt = $ans->statement;
                                    $fieldName = strtoupper($stmt->guidanceField?->name ?? 'UMUM');
                                    $badgeStyle = match($fieldName) {
                                        'PRIBADI' => 'background: #eff6ff; color: #1d4ed8;',
                                        'SOSIAL' => 'background: #ecfeff; color: #0e7490;',
                                        'BELAJAR' => 'background: #fefce8; color: #a16207;',
                                        'KARIR', 'KARIER' => 'background: #faf5ff; color: #7e22ce;',
                                        default => 'background: #f1f5f9; color: #475569;',
                                    };
                                @endphp
                                <div class="list-group-item p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold fs-xs text-mint">#Butir {{ $stmt->statement_number }}</span>
                                        <span class="badge rounded-pill px-2 py-0.5 fs-xs fw-semibold" style="{{ $badgeStyle }}">
                                            Bidang {{ $stmt->guidanceField?->name ?? 'BK' }}
                                        </span>
                                    </div>
                                    <div class="fs-xs text-dark fw-semibold lh-base">{{ $stmt->statement_text }}</div>
                                    @if(!empty($ans->student_note))
                                        <div class="mt-2 p-2 rounded-2 small text-muted fst-italic" style="background: #F4FBF9; border-left: 3px solid #00B894; font-size: 0.78rem;">
                                            <i class="bi bi-chat-quote me-1 text-mint"></i> "{{ $ans->student_note }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <!-- 2. Butir Masalah Kustom -->
                            @if(isset($customStatements) && $customStatements->isNotEmpty())
                                @foreach($customStatements as $cStmt)
                                    <div class="list-group-item p-3 border-bottom" style="background: #fafafa;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge rounded-pill px-2 py-0.5 fs-xs fw-bold" style="background: #ede9fe; color: #6d28d9;">
                                                <i class="bi bi-stars me-1"></i>Butir Tambahan Saya
                                            </span>
                                            <span class="badge rounded-pill px-2 py-0.5 fs-xs fw-semibold" style="background: #E6FAF5; color: #008F72;">
                                                Bidang {{ $cStmt->guidanceField?->name ?? 'BK' }}
                                            </span>
                                        </div>
                                        <div class="fs-xs text-dark fw-semibold lh-base">{{ $cStmt->statement_text }}</div>
                                        @if(!empty($cStmt->student_note))
                                            <div class="mt-2 p-2 rounded-2 small text-muted fst-italic" style="background: #F4FBF9; border-left: 3px solid #7c3aed; font-size: 0.78rem;">
                                                <i class="bi bi-chat-quote me-1 text-purple"></i> "{{ $cStmt->student_note }}"
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="text-center pt-1">
                        <a href="{{ route('murid.ims.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3.5 fs-xs fw-semibold">
                            <i class="bi bi-pencil me-1"></i> Edit / Tambah Masalah di IMS
                        </a>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Privasi & Kerahasiaan -->
            <div class="p-3.5 rounded-4 bg-white shadow-xs" style="border: 1px solid #E2ECE9;">
                <div class="fw-bold text-dark mb-1.5 d-flex align-items-center gap-1.5 fs-xs">
                    <i class="bi bi-shield-check text-mint fs-5"></i> Kerahasiaan Terjamin Sepenuhnya
                </div>
                <p class="mb-0 text-muted fs-xs lh-base">
                    Sesi konseling tindak lanjut IMS dilaksanakan secara empat mata bersama Guru BK dengan memegang teguh asas kerahasiaan bimbingan konseling madrasah. Teman kelasmu tidak akan mengetahui isi masalah yang kamu tandai.
                </p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Pengajuan Konseling IMS Murid -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3.5 p-md-4" style="border: 1px solid #E2ECE9 !important;">
                <div class="border-bottom pb-3 mb-3">
                    <h5 class="fw-bold text-dark mb-1">Form Pengajuan Konseling IMS</h5>
                    <p class="text-muted fs-xs mb-0">Kirimkan permohonan konseling ini ke Guru BK. Jadwal pertemuan akan disesuaikan dan ditetapkan oleh Guru BK madrasah.</p>
                </div>

                <!-- Informasi Penentuan Jadwal oleh Guru BK / Admin -->
                <div class="p-3.5 rounded-4 mb-4 d-flex align-items-start gap-3" 
                     style="background: #F4FBF9; border: 1.5px solid #00B894;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                         style="width: 40px; height: 40px; background: #00B894; color: #ffffff;">
                        <i class="bi bi-calendar2-check-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-sm mb-1">Jadwal Ditentukan & Disetujui Guru BK / Admin</div>
                        <div class="text-muted fs-xs lh-base">
                            Kamu tidak perlu repot memilih jam dan tanggal. Guru BK / Konselor madrasah akan <strong>menentukan tanggal dan jam sesi yang paling kondusif</strong> (misal saat jam luang atau istirahat) agar belajarmu di kelas tetap lancar. Jadwal resmi akan muncul di tiketmu.
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('murid.ims.counseling.store') }}">
                    @csrf

                    <!-- 1. Pilihan Guru BK (Konselor) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark fs-xs">
                            Guru BK / Konselor yang Diharapkan
                        </label>
                        <select name="counselor_id" class="form-select rounded-3 py-2.5 fs-xs @error('counselor_id') is-invalid @enderror">
                            <option value="">-- Otomatis (Guru BK Pembina Kelasku) --</option>
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->id }}" {{ old('counselor_id', $assignedCounselor?->id) == $counselor->id ? 'selected' : '' }}>
                                    {{ $counselor->user->name ?? $counselor->name }} 
                                    @if($assignedCounselor && $assignedCounselor->id == $counselor->id)
                                        (Guru BK Kelasku)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text fs-xxs text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i>Jika dikosongkan, permohonan otomatis diarahkan ke Guru BK yang bertugas membina kelasmu.
                        </div>
                    </div>

                    <!-- 1b. Pilihan Metode Konseling (Tatap Muka vs Online Meet) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark fs-xs mb-2">
                            Pilih Metode Konseling yang Kamu Inginkan <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="card h-100 p-3.5 rounded-4 border method-card text-decoration-none" style="cursor: pointer; background: #fafafa;">
                                    <div class="d-flex align-items-center gap-2.5 mb-1.5">
                                        <input type="radio" name="counseling_method" value="tatap_muka" class="form-check-input mt-0" {{ old('counseling_method', 'tatap_muka') === 'tatap_muka' ? 'checked' : '' }} required style="width: 18px; height: 18px;">
                                        <span class="fw-bold text-dark fs-xs d-flex align-items-center gap-1.5">
                                            <i class="bi bi-door-open text-mint fs-5"></i> Tatap Muka di Sekolah
                                        </span>
                                    </div>
                                    <div class="text-muted fs-xxs lh-base ps-4">
                                        Sesi langsung di Ruang BK madrasah. Guru BK akan menentukan waktu dan ruangan.
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="card h-100 p-3.5 rounded-4 border method-card text-decoration-none" style="cursor: pointer; background: #fafafa;">
                                    <div class="d-flex align-items-center gap-2.5 mb-1.5">
                                        <input type="radio" name="counseling_method" value="online_meet" class="form-check-input mt-0" {{ old('counseling_method') === 'online_meet' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                        <span class="fw-bold text-dark fs-xs d-flex align-items-center gap-1.5">
                                            <i class="bi bi-camera-video text-primary fs-5"></i> Online (Google Meet)
                                        </span>
                                    </div>
                                    <div class="text-muted fs-xxs lh-base ps-4">
                                        Sesi daring dari perangkatmu. Guru BK akan mengisi Link Google Meet pada tiketmu.
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Catatan Tambahan / Harapan Murid -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark fs-xs">
                            Catatan Khusus / Harapan untuk Sesi Konseling <span class="text-muted fw-normal fs-xxs">(Opsional)</span>
                        </label>
                        <textarea name="student_expectation" 
                                  rows="4" 
                                  class="form-control rounded-3 p-3 fs-xs" 
                                  placeholder="Contoh: 'Mohon panggil saya saat jam istirahat saja ya bu/pak, agar tidak tertinggal materi pelajaran di kelas' atau 'Saya ingin curhat lebih mendalam tentang pertemanan di asrama'...">{{ old('student_expectation') }}</textarea>
                        <div class="form-text fs-xxs text-muted mt-1">
                            Kamu bisa menyampaikan preferensi waktu santai atau hal penting yang perlu diketahui Guru BK sebelum pertemuan.
                        </div>
                    </div>

                    <!-- Tombol Aksi (Mobile Friendly) -->
                    <div class="pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <a href="{{ route('murid.ims.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 py-2.5 fs-xs fw-semibold order-2 order-sm-1">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-gold btn-sm rounded-pill px-4 py-2.5 fw-bold shadow-sm order-1 order-sm-2 w-100-mobile" style="font-size: 0.95rem;">
                            <i class="bi bi-send-check-fill me-1.5"></i> Kirim Permohonan Konseling IMS
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 576px) {
        .w-100-mobile {
            width: 100% !important;
            padding: 12px !important;
            font-size: 0.95rem !important;
        }
    }
</style>
@endpush
