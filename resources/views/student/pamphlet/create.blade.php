@extends('layouts.student')

@section('title', 'Unggah Pamflet Kreativitas')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="mb-3">
            <a href="{{ route('murid.pamflet.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Galeri Pamflet
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Header Card -->
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 48px; height: 48px; background: linear-gradient(135deg, #0d9488, #14b8a6);">
                        <i class="bi bi-cloud-arrow-up-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Formulir Unggah Pamflet & Karya Siswa</h5>
                        <p class="text-muted small mb-0">Tampilkan karya terbaikmu untuk menginspirasi teman-teman satu madrasah</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4 p-3">
                        <div class="fw-bold small mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Terjadi Kesalahan Input:</div>
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('murid.pamflet.store') }}" method="POST" enctype="multipart/form-data" id="pamphletForm">
                    @csrf

                    <!-- Identitas Siswa Pengunggah -->
                    <div class="p-3 rounded-3 bg-light mb-4 border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4 text-teal" style="color: #0d9488;"></i>
                            <div>
                                <div class="fw-bold text-dark small">{{ $student->name }}</div>
                                <div class="text-muted" style="font-size: .75rem;">NISN: {{ $student->nisn }} &bull; Rombel: <span class="badge bg-secondary">{{ $student->current_classroom?->name ?? 'Belum ditentukan' }}</span></div>
                            </div>
                        </div>
                        <span class="badge bg-teal-subtle text-teal rounded-pill px-3 py-2 small" style="background-color: #ccfbf1; color: #0f766e;">
                            Pengunggah Karya
                        </span>
                    </div>

                    <!-- Judul Karya -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold text-dark small">
                            Judul Karya / Pamflet <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="Contoh: Poster Anti-Bullying / Tips Semangat Belajar Semester Genap" 
                               required 
                               maxlength="150">
                        <div class="form-text small text-muted">Maksimal 150 karakter. Buat judul yang menarik dan mencerminkan tema karyamu.</div>
                    </div>

                    <!-- Berkas Gambar Karya (Maks 1 MB) -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold text-dark small">
                            Berkas Gambar Pamflet / Poster <span class="text-danger">*</span>
                        </label>
                        
                        <!-- Upload Drop Area -->
                        <div class="border border-2 border-dashed rounded-4 p-4 text-center cursor-pointer position-relative" id="dropArea" style="background-color: #f8fafc; border-color: #cbd5e1 !important; transition: all .2s ease;">
                            <input type="file" 
                                   name="image" 
                                   id="imageInput" 
                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" 
                                   accept="image/png, image/jpeg, image/jpg, image/webp" 
                                   required>
                            
                            <div id="dropPlaceholder">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-white shadow-sm mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-image fs-2 text-teal" style="color: #0d9488;"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Klik di sini atau Seret File Gambar ke Area Ini</h6>
                                <p class="text-muted small mb-2">Format: JPG, JPEG, PNG, WEBP &bull; <strong>Ukuran Maksimal: 1 MB (1024 KB)</strong></p>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 small">Pilih Berkas</span>
                            </div>

                            <!-- Preview Kontainer -->
                            <div id="imagePreviewContainer" class="d-none">
                                <img id="imagePreview" src="" alt="Pratinjau Karya" class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 280px; object-fit: contain;">
                                <div class="small fw-semibold text-dark" id="fileName">Nama File</div>
                                <div class="small text-muted" id="fileSize">Ukuran File</div>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 mt-2" id="btnRemoveImage">
                                    <i class="bi bi-x-circle me-1"></i> Ganti Gambar
                                </button>
                            </div>
                        </div>

                        <div id="sizeError" class="alert alert-danger mt-2 py-2 small d-none">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Ukuran file terlalu besar!</strong> Maksimal ukuran berkas yang diperbolehkan adalah 1 MB (1024 KB).
                        </div>
                    </div>

                    <!-- Deskripsi Karya -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold text-dark small">
                            Deskripsi / Makna Karya (Opsional)
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                  placeholder="Ceritakan pesan moral, ide, inspirasi, atau penjelasan singkat di balik karya pamflet yang kamu buat...">{{ old('description') }}</textarea>
                        <div class="form-text small text-muted">Deskripsi ini akan tampil ketika teman atau guru mengklik detail karyamu.</div>
                    </div>

                    <!-- Ketentuan Pengunggahan -->
                    <div class="card border-0 rounded-3 mb-4" style="background-color: #f0fdf4; border-left: 4px solid #16a34a !important;">
                        <div class="card-body p-3">
                            <div class="d-flex gap-2">
                                <i class="bi bi-shield-check fs-5 text-success"></i>
                                <div class="small text-dark">
                                    <div class="fw-bold text-success mb-1">Ketentuan Publikasi Pamflet:</div>
                                    <ul class="mb-0 ps-3 text-muted">
                                        <li>Karya orisinil buatan sendiri atau kelompok belajar siswa.</li>
                                        <li>Bertema positif, edukatif, motivasi, kesehatan mental, atau kreativitas madrasah.</li>
                                        <li>Tidak mengandung unsur SARA, ujaran kebencian, atau konten yang tidak pantas.</li>
                                        <li>Admin / Guru BK akan memoderasi karya sebelum resmi ditayangkan di Dashboard.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                        <a href="{{ route('murid.pamflet.index') }}" class="btn btn-light rounded-pill px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn text-white fw-bold px-4 rounded-pill shadow-sm" id="btnSubmit" style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah & Ajukan Moderasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('imageInput');
    const dropArea = document.getElementById('dropArea');
    const placeholder = document.getElementById('dropPlaceholder');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImg = document.getElementById('imagePreview');
    const fileNameEl = document.getElementById('fileName');
    const fileSizeEl = document.getElementById('fileSize');
    const sizeError = document.getElementById('sizeError');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnRemove = document.getElementById('btnRemoveImage');

    const MAX_SIZE = 1024 * 1024; // 1 MB = 1048576 bytes

    input.addEventListener('change', function (e) {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (!files || files.length === 0) return;
        const file = files[0];

        // Cek ukuran berkas
        if (file.size > MAX_SIZE) {
            sizeError.classList.remove('d-none');
            btnSubmit.disabled = true;
            input.value = '';
            placeholder.classList.remove('d-none');
            previewContainer.classList.add('d-none');
            return;
        }

        sizeError.classList.add('d-none');
        btnSubmit.disabled = false;

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB (Memenuhi batas < 1 MB)';
            placeholder.classList.add('d-none');
            previewContainer.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    btnRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        input.value = '';
        previewImg.src = '';
        placeholder.classList.remove('d-none');
        previewContainer.classList.add('d-none');
        sizeError.classList.add('d-none');
        btnSubmit.disabled = false;
    });
});
</script>
@endpush
@endsection
