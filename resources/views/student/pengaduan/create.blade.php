@extends('layouts.student')

@section('title', 'Buat Laporan Pengaduan Aman')
@section('page_title', 'Buat Pengaduan Aman')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item"><a href="{{ route('murid.pengaduan.index') }}" class="text-decoration-none" style="color: var(--emis-teal);">Pengaduan</a></li>
    <li class="breadcrumb-item active">Buat Pengaduan</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('murid.pengaduan.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-danger-subtle text-danger px-2 py-1 fs-xs fw-semibold rounded-pill">Pengaduan Aman</span>
                    <span class="badge bg-light text-dark border px-2 py-1 fs-xs fw-medium rounded-pill">Kerahasiaan Terjamin</span>
                </div>
                <h1 class="h4 fw-bold text-dark mb-0">Formulir Pengaduan & Perlindungan Diri</h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm">Sampaikan Masalah / Kejadian yang Kamu Alami</span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('murid.pengaduan.store') }}" class="space-y-4">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Kategori Masalah <span class="text-danger">*</span></label>
                                <select name="category" class="form-select form-select-sm" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Tingkat Urgensi <span class="text-danger">*</span></label>
                                <select name="urgency_level" class="form-select form-select-sm" required>
                                    <option value="sedang" {{ old('urgency_level') == 'sedang' ? 'selected' : '' }}>Sedang (Butuh penanganan biasa)</option>
                                    <option value="tinggi" {{ old('urgency_level') == 'tinggi' ? 'selected' : '' }}>Tinggi (Mendesak & mengganggu ketenangan)</option>
                                    <option value="darurat" {{ old('urgency_level') == 'darurat' ? 'selected' : '' }}>Darurat (Bahaya keselamatan fisik/mental!)</option>
                                    <option value="rendah" {{ old('urgency_level') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Perkiraan Waktu Kejadian</label>
                                <input type="date" name="incident_date" value="{{ old('incident_date') }}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Tempat / Lokasi Terjadinya</label>
                                <input type="text" name="incident_location" value="{{ old('incident_location') }}" class="form-control form-control-sm" placeholder="Contoh: Di lorong kelas, toilet, media sosial, atau perjalanan...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-semibold">Ceritakan Peristiwa yang Terjadi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Jelaskan apa yang terjadi, siapa saja pihak yang terlibat (jika kamu tahu), dan bantuan apa yang kamu perlukan..." required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Identitas Siswa Pelapor Resmi -->
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-badge-fill text-primary-teal fs-5"></i>
                                <span class="fw-bold text-dark fs-xs">Identitas Murid Pelapor (Resmi & Terverifikasi)</span>
                            </div>
                            <div class="row g-2 small text-muted">
                                <div class="col-sm-6">
                                    <span class="d-block text-slate-500 fs-xxs text-uppercase fw-bold">Nama Murid:</span>
                                    <span class="fw-bold text-slate-800">{{ auth()->user()->student?->name ?? auth()->user()->name }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="d-block text-slate-500 fs-xxs text-uppercase fw-bold">NISN / Akun:</span>
                                    <code class="text-primary-teal fw-bold">{{ auth()->user()->student?->nisn ?? auth()->user()->username }}</code>
                                </div>
                            </div>
                            <span class="text-muted fs-xxs d-block mt-2 pt-2 border-top">
                                <i class="bi bi-info-circle me-1"></i> Pengaduan dikirimkan menggunakan identitas resmi Anda agar Guru BK dapat segera memberikan perlindungan dan pendampingan yang tepat.
                            </span>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('murid.pengaduan.index') }}" class="btn btn-light btn-sm border">Batal</a>
                            <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">
                                <i class="bi bi-shield-fill-check me-1"></i> Kirimkan Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 space-y-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-heart-pulse-fill text-danger fs-4"></i>
                    <h5 class="fw-bold text-dark mb-0 fs-sm">Kamu Tidak Sendirian</h5>
                </div>
                <p class="fs-xs text-muted mb-3">
                    Menghadapi perundungan atau ancaman keselamatan bukan hal yang mudah. Kami di sini untuk mendengarmu tanpa menghakimi dan melindungimu dari perlakuan yang tidak adil.
                </p>
                <div class="p-3 bg-danger-subtle text-danger-emphasis rounded-3 fs-xs">
                    <strong>Penting:</strong> Jika kamu atau temanmu sedang dalam ancaman bahaya fisik mendesak saat ini juga, segera temui bapak/ibu guru terdekat atau petugas madrasah secara langsung.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
