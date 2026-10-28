@extends('layouts.walikelas')

@section('title', 'Lapor Kasus Murid ke Guru BK')
@section('page_title', 'Lapor Kasus / Insiden Murid')
@section('page_subtitle', 'Sampaikan catatan insidental untuk penanganan bersama Guru BK')

@section('content')
<div class="mb-3">
    <a href="{{ route('walikelas.aduan.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Aduan
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-app">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square text-warning me-1.5"></i> Formulir Pelaporan Kasus Murid Kelas {{ $classroom?->name }}
                </h6>
                <div class="text-muted fs-xs">Laporan ini akan langsung masuk ke panel Guru BK & Admin Madrasah untuk ditinjau dan ditindaklanjuti.</div>
            </div>

            <div class="card-body p-3 p-md-4">
                <form action="{{ route('walikelas.aduan.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="student_id" class="form-label fw-bold small text-dark">Pilih Murid Terkait <span class="text-danger">*</span></label>
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
                            <label for="incident_date" class="form-label fw-bold small text-dark">Tanggal Kejadian / Temuan <span class="text-danger">*</span></label>
                            <input type="date" name="incident_date" id="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', date('Y-m-d')) }}" required>
                            @error('incident_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
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

                        <div class="col-md-7">
                            <label for="title" class="form-label fw-bold small text-dark">Pokok Masalah / Judul Kasus <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Sering terlambat dan tidak mengumpulkan tugas pekanan" value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold small text-dark">Deskripsi & Kronologi Kejadian <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan secara runtut fakta kejadian, waktu, tempat, dan siapa saja yang terlibat..." required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="initial_action" class="form-label fw-bold small text-dark">Tindakan Awal yang Telah Dilakukan Wali Kelas</label>
                        <textarea name="initial_action" id="initial_action" rows="2" class="form-control @error('initial_action') is-invalid @enderror" placeholder="Contoh: Sudah dinasihati secara personal saat jam istirahat, dihubungi orang tua melalui pesan singkat...">{{ old('initial_action') }}</textarea>
                        <div class="form-text fs-xs text-muted">Upaya mediasi atau pembinaan awal yang telah Anda lakukan di lingkup kelas.</div>
                        @error('initial_action') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background: #fffbeb; border: 1.5px solid #fef08a;">
                        <label for="teacher_notes" class="form-label fw-bold text-dark mb-1">
                            <i class="bi bi-chat-quote-fill text-warning me-1"></i> Catatan & Rekomendasi Wali Kelas untuk Admin / Guru BK <span class="text-danger">*</span>
                        </label>
                        <textarea name="teacher_notes" id="teacher_notes" rows="3" class="form-control @error('teacher_notes') is-invalid @enderror" placeholder="Berikan catatan khusus, harapan atau rekomendasi Anda agar Guru BK segera menindaklanjuti anak ini (misal: mohon panggilan sesi konseling individu, home visit, dll)..." required>{{ old('teacher_notes') }}</textarea>
                        <div class="form-text fs-xs text-muted">
                            Catatan ini akan menjadi prioritas rujukan bagi Guru BK dalam menentukan jadwal & langkah intervensi.
                        </div>
                        @error('teacher_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2">
                        <a href="{{ route('walikelas.aduan.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold text-center">Batal</a>
                        <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark shadow-sm">
                            <i class="bi bi-send-fill me-1.5"></i> Kirim Laporan ke Guru BK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
