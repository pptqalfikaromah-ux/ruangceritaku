@extends('layouts.student')

@section('title', 'Kirim Pelayanan Darurat')
@section('page_title', 'Pelayanan Darurat')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}" class="text-decoration-none" style="color: var(--emis-teal);">Murid</a></li>
    <li class="breadcrumb-item"><a href="{{ route('murid.pengaduan.index') }}" class="text-decoration-none" style="color: var(--emis-teal);">Pelayanan Darurat</a></li>
    <li class="breadcrumb-item active">Kirim Pelayanan</li>
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
                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fs-xs fw-bold rounded-pill">
                        <i class="bi bi-shield-fill-exclamation me-1"></i>Pelayanan Darurat
                    </span>
                    <span class="badge bg-light text-dark border px-2 py-1 fs-xs fw-medium rounded-pill">Prioritas Penanganan Cepat</span>
                </div>
                <h1 class="h4 fw-bold text-dark mb-0">Formulir Pelayanan Darurat & Perlindungan Murid</h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm">Sampaikan Masalah / Kendala Darurat yang Kamu Hadapi</span>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('murid.pengaduan.store') }}" class="space-y-4">
                        @csrf

                        <div class="row g-3.5">
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold mb-1.5">Kategori Permasalahan <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-xs text-muted fw-semibold mb-1.5">Tanggal / Waktu Laporan (Otomatis)</label>
                                <input type="date" name="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" class="form-control bg-light" readonly title="Waktu kejadian terisi otomatis saat Anda mengirimkan pelayanan ini">
                            </div>
                        </div>

                        <div class="row g-3.5 mt-1">
                            <div class="col-12">
                                <label class="form-label fs-xs text-muted fw-semibold mb-1.5">Tempat / Lokasi Terjadinya (Opsional)</label>
                                <input type="text" name="incident_location" value="{{ old('incident_location') }}" class="form-control" placeholder="Contoh: Di lorong kelas, toilet, media sosial, atau perjalanan...">
                            </div>
                        </div>

                        <div class="mb-3.5 mt-2">
                            <label class="form-label fs-xs text-muted fw-semibold mb-1.5">Ceritakan Peristiwa yang Terjadi Secara Jelas <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Jelaskan apa yang terjadi, siapa saja pihak yang terlibat (jika kamu tahu), dan bantuan mendesak apa yang kamu perlukan..." required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Identitas Siswa Pelapor Resmi -->
                        <div class="p-3.5 bg-light rounded-4 border">
                            <div class="d-flex align-items-center gap-2 mb-2.5">
                                <i class="bi bi-person-badge-fill text-primary-teal fs-5"></i>
                                <span class="fw-bold text-dark fs-xs">Identitas Murid Pelapor (Resmi & Terverifikasi)</span>
                            </div>
                            <div class="row g-2.5 small text-muted">
                                <div class="col-sm-6">
                                    <span class="d-block text-slate-500 fs-xxs text-uppercase fw-bold mb-1">Nama Murid:</span>
                                    <div class="fw-bold text-slate-800 p-2 bg-white rounded-3 border">{{ auth()->user()->student?->name ?? auth()->user()->name }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <span class="d-block text-slate-500 fs-xxs text-uppercase fw-bold mb-1">NISN / Akun:</span>
                                    <div class="p-2 bg-white rounded-3 border">
                                        <code class="text-primary-teal fw-bold font-monospace">{{ auth()->user()->student?->nisn ?? auth()->user()->username }}</code>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-xxs d-block mt-2.5 pt-2 border-top" style="line-height: 1.5;">
                                <i class="bi bi-info-circle me-1"></i> Pengaduan dikirimkan menggunakan identitas resmi Anda agar Guru BK dapat segera memberikan perlindungan dan pendampingan yang tepat.
                            </span>
                        </div>

                        <div class="d-flex justify-content-end gap-2.5 pt-3.5 border-top">
                            <a href="{{ route('murid.pengaduan.index') }}" class="btn btn-light rounded-pill px-4 py-2 border fw-semibold text-muted">Batal</a>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                                <i class="bi bi-shield-fill-check me-1.5"></i> Kirimkan Laporan
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
