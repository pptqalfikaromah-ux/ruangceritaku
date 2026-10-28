@extends('layouts.panel')

@section('title', 'Tambah Murid Baru')

@section('content')
<x-page-header title="Tambah Data Murid"
               subtitle="Input data identitas lengkap murid baru ke sistem konseling.">
    <x-slot:actions>
        <a href="{{ route('panel.murid.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-10">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.murid.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3">Identitas Utama Murid</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="nisn" label="NISN (Nomor Induk Siswa Nasional)" placeholder="10 digit NISN" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="nis" label="NIS (Nomor Induk Sekolah)" placeholder="NIS sekolah jika ada" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <x-form-input name="name" label="Nama Lengkap Murid" placeholder="Nama lengkap sesuai akta / ijazah" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-select name="gender" label="Jenis Kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </x-form-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-form-input name="birth_place" label="Tempat Lahir" placeholder="Kota / Kabupaten" />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="birth_date" label="Tanggal Lahir" type="date" />
                        </div>
                        <div class="col-md-4">
                            <x-form-select name="religion" label="Agama">
                                <option value="">-- Pilih Agama --</option>
                                @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                    <option value="{{ $agm }}" {{ old('religion') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </x-form-select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold small text-slate-700">Alamat Tempat Tinggal</label>
                        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat domisili murid">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3 mt-4">Data Orang Tua / Wali & Kontak</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="guardian_name" label="Nama Orang Tua / Wali" placeholder="Nama orang tua atau wali murid" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="guardian_phone" label="No. Handphone / WhatsApp Wali" placeholder="Contoh: 081234567890" help="Akan digunakan untuk kontak konsultasi" />
                        </div>
                    </div>

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-key-fill me-1 text-primary"></i>Akun Login Murid
                    </h6>
                    <div class="alert alert-info small py-2 mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Username & password ini akan digunakan murid untuk login ke sistem. Akun langsung aktif setelah disimpan.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="username" label="Username" placeholder="Contoh: murid.anisa2025" required help="Harus unik, tidak boleh sama dengan user lain" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="password" label="Password" type="password" placeholder="Minimal 6 karakter" required />
                        </div>
                    </div>

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3 mt-4">Penempatan & Status</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-select name="classroom_id" label="Masukkan ke Rombel (Tahun Ajaran Ini)" help="Opsional, dapat dimasukkan nanti">
                                <option value="">-- Belum Masuk Rombel --</option>
                                @foreach($classrooms as $cr)
                                    <option value="{{ $cr->id }}" {{ old('classroom_id') == $cr->id ? 'selected' : '' }}>
                                        {{ $cr->name }} (Kelas {{ $cr->level }}) - TA: {{ $cr->academicYear?->name }}
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>
                        <div class="col-md-3">
                            <x-form-select name="status" label="Status Murid" required>
                                <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="pindah" {{ old('status') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </x-form-select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-slate-700">Foto Murid</label>
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-3">
                                <div class="position-relative flex-shrink-0">
                                    <img id="photoPreview" 
                                         src="https://ui-avatars.com/api/?name=Murid&background=0d9488&color=fff&size=100" 
                                         alt="Pratinjau Foto" 
                                         class="rounded-circle shadow-xs border" 
                                         width="54" 
                                         height="54" 
                                         style="object-fit: cover; background: #fff;">
                                    <span id="previewBadge" class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark border shadow-xs d-none" style="font-size: 0.6rem;">
                                        Baru
                                    </span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="photo" id="photoInput" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <button class="btn btn-outline-secondary d-none" type="button" id="btnCancelPhoto" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small text-muted mt-1 mb-0 fs-xxs">Maks 2MB (JPG, PNG, WEBP). Pratinjau langsung terlihat.</div>
                                    @error('photo')
                                        <div class="text-danger small mt-0.5 fs-xxs">{{ $message }}</div>
                                    @enderror
                                    <div id="photoError" class="text-danger small mt-0.5 d-none fw-semibold fs-xxs"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.murid.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Data Murid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewBadge = document.getElementById('previewBadge');
        const btnCancelPhoto = document.getElementById('btnCancelPhoto');
        const photoError = document.getElementById('photoError');
        const initialUrl = photoPreview ? photoPreview.src : '';

        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (!file) {
                    photoPreview.src = initialUrl;
                    if (previewBadge) previewBadge.classList.add('d-none');
                    if (btnCancelPhoto) btnCancelPhoto.classList.add('d-none');
                    return;
                }
                if (!file.type.match('image.*')) {
                    if (photoError) { photoError.textContent = 'Harus berupa file gambar.'; photoError.classList.remove('d-none'); }
                    photoInput.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    if (photoError) { photoError.textContent = 'Maksimal ukuran foto adalah 2 MB.'; photoError.classList.remove('d-none'); }
                    photoInput.value = '';
                    return;
                }
                if (photoError) photoError.classList.add('d-none');

                const reader = new FileReader();
                reader.onload = function (evt) {
                    photoPreview.src = evt.target.result;
                    if (previewBadge) previewBadge.classList.remove('d-none');
                    if (btnCancelPhoto) btnCancelPhoto.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            if (btnCancelPhoto) {
                btnCancelPhoto.addEventListener('click', function () {
                    photoInput.value = '';
                    photoPreview.src = initialUrl;
                    if (previewBadge) previewBadge.classList.add('d-none');
                    btnCancelPhoto.classList.add('d-none');
                    if (photoError) photoError.classList.add('d-none');
                });
            }
        }
    });
</script>
@endpush
