@extends('layouts.panel')

@section('title', 'Ubah Guru BK')

@section('content')
<x-page-header title="Ubah Data Guru BK"
               subtitle="Perbarui informasi profil dan status Guru BK {{ $guru_bk->name }}.">
    <x-slot:actions>
        <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-10">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.guru-bk.update', $guru_bk->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3">Biodata Guru BK</h6>
                    <div class="row">
                        <div class="col-md-8">
                            <x-form-input name="name" label="Nama Lengkap (Tanpa Gelar)" :value="$guru_bk->name" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="title" label="Gelar Akademik" :value="$guru_bk->title" help="Contoh: S.Pd., Kons." />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="nip_nuptk" label="NIP / NUPTK" :value="$guru_bk->nip_nuptk" />
                        </div>
                        <div class="col-md-6">
                            <x-form-select name="gender" label="Jenis Kelamin" required>
                                <option value="L" {{ old('gender', $guru_bk->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $guru_bk->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </x-form-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="phone" label="No. Handphone / WhatsApp" :value="$guru_bk->phone" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="email" label="Alamat Email" type="email" :value="$guru_bk->email" />
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small text-slate-700">Foto Profil Guru BK</label>
                            <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-3">
                                <div class="position-relative flex-shrink-0">
                                    <img id="photoPreview" 
                                         src="{{ $guru_bk->photo_url }}" 
                                         alt="Pratinjau Foto" 
                                         class="rounded-circle shadow-xs border" 
                                         width="60" 
                                         height="60" 
                                         style="object-fit: cover; background: #fff;">
                                    <span id="previewBadge" class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark border shadow-xs d-none" style="font-size: 0.6rem;">
                                        Baru
                                    </span>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="photo" id="photoInput" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <button class="btn btn-outline-secondary d-none" type="button" id="btnCancelPhoto" title="Batal">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small text-muted mt-1 mb-0 fs-xxs">Maks 2MB (JPG, PNG, WEBP). Pratinjau langsung terlihat.</div>
                                    <div id="photoError" class="text-danger small mt-0.5 d-none fw-semibold fs-xxs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <x-form-select name="status" label="Status Guru BK" required>
                                <option value="aktif" {{ old('status', $guru_bk->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $guru_bk->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </x-form-select>
                        </div>
                    </div>

                    @if($guru_bk->user)
                        <div class="p-3 rounded-3 bg-light border mt-3 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <div>
                                    <div class="fw-bold small text-slate-800">Akun Login Terhubung</div>
                                    <div class="text-muted smaller">Username: <strong>{{ $guru_bk->user->username }}</strong> (Status Akun: {{ $guru_bk->user->is_active ? 'Aktif' : 'Nonaktif' }})</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.guru-bk.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
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
