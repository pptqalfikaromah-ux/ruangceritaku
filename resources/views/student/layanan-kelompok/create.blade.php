@extends('layouts.student')

@section('title', 'Formulir Pengajuan Layanan Kelompok (BKP & KKP)')
@section('breadcrumb')
    <a href="{{ route('murid.layanan-kelompok.index') }}">Layanan Kelompok</a>
    <i class="bi bi-chevron-right fs-xxs text-muted"></i>
    <span class="current">Ajukan Kelompok Baru</span>
@endsection

@push('styles')
<style>
    .service-card-select {
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .service-card-select:hover {
        border-color: #0d9488;
        transform: translateY(-2px);
    }
    .service-radio:checked + .service-card-select {
        border-color: #0d9488;
        background-color: #f0fdfa;
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.12);
    }
    .member-select-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        transition: background-color 0.15s ease;
        cursor: pointer;
    }
    .member-select-item:hover {
        background-color: #f8fafc;
    }
    .member-checkbox:checked ~ .member-content {
        color: #0d9488;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <!-- Header Card -->
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-teal-subtle text-mint d-inline-flex flex-shrink-0">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
                <div>
                    <h1 class="h4 fw-extrabold text-dark mb-1">Formulir Layanan Bimbingan & Konseling Kelompok</h1>
                    <p class="text-muted fs-xs mb-0">Isi formulir ini untuk mengajukan sesi bimbingan atau konseling bersama teman-temanmu ke Guru BK.</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('murid.layanan-kelompok.store') }}" method="POST" id="formGroupCounseling">
        @csrf

        <div class="row g-3">
            <!-- Kolom Kiri: Pilihan Layanan & Topik -->
            <div class="col-12 col-lg-7 space-y-3">
                <!-- 1. Pilih Jenis Layanan -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">1</span>
                        Pilih Jenis Layanan Kelompok
                    </h6>

                    <div class="row g-2.5">
                        <div class="col-12 col-sm-6">
                            <input type="radio" name="service_type" value="bimbingan_kelompok" id="typeBkp" class="d-none service-radio" checked>
                            <label for="typeBkp" class="service-card-select d-block h-100">
                                <div class="d-flex align-items-center justify-content-between mb-1.5">
                                    <span class="badge bg-mint text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">BKP</span>
                                    <i class="bi bi-check-circle-fill text-mint fs-5"></i>
                                </div>
                                <h6 class="fw-bold text-dark fs-xs mb-1">Bimbingan Kelompok</h6>
                                <p class="text-muted fs-xxs mb-0" style="line-height: 1.5;">
                                    Membahas topik umum bersama: kiat belajar, pertemanan, percaya diri, atau persiapan ujian.
                                </p>
                            </label>
                        </div>

                        <div class="col-12 col-sm-6">
                            <input type="radio" name="service_type" value="konseling_kelompok" id="typeKkp" class="d-none service-radio">
                            <label for="typeKkp" class="service-card-select d-block h-100">
                                <div class="d-flex align-items-center justify-content-between mb-1.5">
                                    <span class="badge bg-purple text-white px-2 py-0.5 rounded-pill fs-xxs fw-bold">KKP</span>
                                    <i class="bi bi-check-circle-fill text-purple fs-5"></i>
                                </div>
                                <h6 class="fw-bold text-dark fs-xs mb-1">Konseling Kelompok</h6>
                                <p class="text-muted fs-xxs mb-0" style="line-height: 1.5;">
                                    Menyelesaikan masalah pribadi yang serupa bersama teman & Guru BK dengan asas kerahasiaan kelompok.
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Rincian Topik & Alasan -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">2</span>
                        Topik & Latar Belakang Bahasan
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fs-xs fw-bold text-dark">
                            Topik / Pokok Bahasan yang Ingin Dibahas <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="topic" class="form-control fs-xs" 
                               placeholder="Contoh: Mengatasi kejenuhan belajar bersama atau Memperbaiki komunikasi antar teman" 
                               value="{{ old('topic') }}" required>
                        <span class="text-muted fs-xxs">Tuliskan tema utama yang disepakati oleh kelompokmu.</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-xs fw-bold text-dark">
                            Cerita / Alasan Mengapa Kelompok Butuh Sesi Ini <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" rows="3" class="form-control fs-xs" 
                                  placeholder="Ceritakan gambaran situasi yang kalian rasakan saat ini..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fs-xs fw-bold text-dark">
                            Harapan / Tujuan Bersama (Opsional)
                        </label>
                        <textarea name="group_goal" rows="2" class="form-control fs-xs" 
                                  placeholder="Contoh: Kami ingin lebih kompak dan memiliki jadwal belajar bersama yang teratur.">{{ old('group_goal') }}</textarea>
                    </div>
                </div>

                <!-- 4. Usulan Jadwal -->
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4">
                    <h6 class="fw-extrabold text-dark mb-3 pb-2 border-bottom fs-sm">
                        <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">4</span>
                        Usulan Waktu Pelaksanaan
                    </h6>

                    <div class="row g-2 mb-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fs-xs fw-bold text-dark">Usulan Tanggal</label>
                            <input type="date" name="preferred_date" class="form-control form-control-sm fs-xs" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('preferred_date') }}">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fs-xs fw-bold text-dark">Pilihan Sesi Waktu</label>
                            <select name="preferred_session" class="form-select form-select-sm fs-xs">
                                <option value="Istirahat Pertama (09:30 - 10:00)">Istirahat Pertama (09:30 - 10:00)</option>
                                <option value="Istirahat Kedua / Sholat Dhuhur">Istirahat Kedua / Sholat Dhuhur</option>
                                <option value="Setelah Jam Pelajaran Selesai">Setelah Jam Pelajaran Selesai</option>
                                <option value="Jam Kosong / Fleksibel">Jam Kosong / Fleksibel sesuai arahan BK</option>
                            </select>
                        </div>
                    </div>
                    <span class="text-muted fs-xxs">Jadwal resmi akan dikonfirmasi oleh Guru BK sesuai ketersediaan ruangan & waktu.</span>
                </div>
            </div>

            <!-- Kolom Kanan: Pilih Anggota Kelompok -->
            <div class="col-12 col-lg-5">
                <div class="card card-app border-0 shadow-sm rounded-4 p-3 p-md-4 h-100 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <h6 class="fw-extrabold text-dark mb-0 fs-sm">
                                <span class="badge bg-mint text-white rounded-circle me-1" style="width: 22px; height: 22px; line-height: 18px; text-align: center; display: inline-block;">3</span>
                                Pilih Anggota Teman
                            </h6>
                            <span class="text-muted fs-xxs">Minimal 1 orang, maksimal 12 orang teman.</span>
                        </div>
                        <span class="badge bg-teal-subtle text-mint px-2.5 py-1 rounded-pill fs-xxs fw-bold" id="selectedCountBadge">
                            0 Teman Terpilih
                        </span>
                    </div>

                    <!-- Ketua Kelompok Box -->
                    <div class="p-2.5 rounded-3 bg-light border mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark fs-xs text-truncate">{{ $student->name }} (Kamu)</div>
                            <div class="text-muted fs-xxs">Ketua Pemohon &bull; {{ $student->classrooms->first()?->name ?? 'Siswa' }}</div>
                        </div>
                    </div>

                    <!-- Kotak Pencarian Teman -->
                    <div class="mb-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchMemberInput" class="form-control border-start-0 fs-xs" placeholder="Ketik nama atau kelas teman...">
                        </div>
                    </div>

                    <!-- List Pilihan Teman -->
                    <div class="space-y-1.5 flex-grow-1 overflow-y-auto pe-1" style="max-height: 380px;" id="memberListContainer">
                        @foreach($classmates as $mate)
                            <label class="member-select-item d-flex align-items-center justify-content-between w-100 mb-1 member-item-row" data-name="{{ strtolower($mate->name) }} {{ strtolower($mate->classrooms->first()?->name ?? '') }}">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <input type="checkbox" name="member_ids[]" value="{{ $mate->id }}" class="form-check-input member-checkbox flex-shrink-0 mt-0" 
                                           {{ in_array($mate->id, old('member_ids', [])) ? 'checked' : '' }}>
                                    <div class="overflow-hidden">
                                        <div class="text-dark fs-xs text-truncate member-content">{{ $mate->name }}</div>
                                        <div class="text-muted fs-xxs">
                                            Kelas: {{ $mate->classrooms->first()?->name ?? '-' }} &bull; NISN: {{ $mate->nisn ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-3 border-top mt-auto">
                        <button type="submit" class="btn btn-mint text-white btn-sm w-100 rounded-pill py-2.5 fw-extrabold shadow-sm fs-xs">
                            <i class="bi bi-send-check-fill me-1"></i> Kirim Pengajuan Kelompok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchMemberInput');
    const memberRows = document.querySelectorAll('.member-item-row');
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const badge = document.getElementById('selectedCountBadge');

    function updateBadge() {
        const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
        badge.textContent = checkedCount + ' Teman Terpilih';
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBadge);
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        memberRows.forEach(row => {
            const data = row.getAttribute('data-name');
            if (data.includes(query)) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    });

    updateBadge();
});
</script>
@endpush

