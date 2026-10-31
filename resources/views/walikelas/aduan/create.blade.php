@extends('layouts.walikelas')

@section('title', 'Lapor Kasus Murid ke Guru BK')
@section('page_title', 'Lapor Kasus / Insiden Murid')
@section('page_subtitle', 'Sampaikan laporan masalah murid untuk penanganan bersama Guru BK')

@section('content')
<div class="mb-3">
    <a href="{{ route('walikelas.aduan.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Aduan
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-app shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square text-warning me-1.5"></i> Formulir Laporan Kasus Murid Kelas {{ $classroom?->name }}
                </h6>
                <div class="text-muted fs-xs mt-0.5">Laporan ini akan langsung diteruskan ke Guru BK & Admin Sekolah.</div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('walikelas.aduan.store') }}" method="POST">
                    @csrf

                    <!-- 1. Nama Murid & Tanggal -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="student_id" class="form-label fw-bold small text-dark">Nama Murid Terkait <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Murid di Kelas {{ $classroom?->name }} --</option>
                                @foreach($students as $stu)
                                    <option value="{{ $stu->id }}" {{ (old('student_id', request('student_id')) == $stu->id) ? 'selected' : '' }}>
                                        {{ $stu->name }} (NISN: {{ $stu->nisn }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-5">
                            <label for="incident_date" class="form-label fw-bold small text-dark">Tanggal Kejadian / Laporan <span class="text-danger">*</span></label>
                            <input type="date" name="incident_date" id="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', date('Y-m-d')) }}" required>
                            @error('incident_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- 2. Kategori Kasus -->
                    <div class="mb-3">
                        <label for="category" class="form-label fw-bold small text-dark">Kategori Kasus / Permasalahan <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="kedisiplinan" {{ old('category') === 'kedisiplinan' ? 'selected' : '' }}>Kedisiplinan & Kerapian</option>
                            <option value="pelanggaran_aturan" {{ old('category') === 'pelanggaran_aturan' ? 'selected' : '' }}>Pelanggaran Aturan / Tata Tertib</option>
                            <option value="akademik" {{ old('category') === 'akademik' ? 'selected' : '' }}>Akademik & Ketertinggalan Belajar</option>
                            <option value="sosial_pertemanan" {{ old('category') === 'sosial_pertemanan' ? 'selected' : '' }}>Sosial & Relasi Pertemanan</option>
                            <option value="perilaku_sikap" {{ old('category') === 'perilaku_sikap' ? 'selected' : '' }}>Perilaku & Sikap Emosional</option>
                            <option value="bullying_konflik" {{ old('category') === 'bullying_konflik' ? 'selected' : '' }}>Perundungan / Bullying / Konflik</option>
                            <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Insidental Lainnya</option>
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- 3. Deskripsi Masalah / Kejadian -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold small text-dark">Deskripsi Permasalahan / Kejadian <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan secara ringkas permasalahan atau kejadian yang dialami/dilakukan oleh murid..." required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 pt-2 border-top">
                        <a href="{{ route('walikelas.aduan.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold text-center">Batal</a>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark shadow-sm">
                            <i class="bi bi-send-fill me-1.5"></i> Kirim Laporan ke Guru BK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
