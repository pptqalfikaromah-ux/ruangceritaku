@extends('layouts.panel')

@section('title', 'Profil Saya')

@section('content')
<x-page-header title="Profil Saya"
               subtitle="Kelola informasi akun pengguna dan data profil lengkap Anda.">
</x-page-header>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-app shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('panel.setting.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @php
                        $activeProfile = $counselor ?? $teacher;
                        $currentPhoto = $user->avatar_url;
                    @endphp

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 mb-4 pb-3 border-bottom">
                        <img src="{{ $currentPhoto }}" id="headerProfileAvatar" alt="{{ $user->name }}" class="rounded-circle shadow-sm border border-2 border-primary-subtle" width="76" height="76" style="object-fit: cover; background: #fff;">
                        <div>
                            <h5 class="fw-bold text-slate-800 mb-0.5">{{ $user->name }}</h5>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-teal-subtle text-primary-teal">{{ $user->role_label }}</span>
                                <span class="text-muted small">Username: <code>{{ $user->username }}</code></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <x-form-input name="name" label="Nama Lengkap" :value="$user->name" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="title" label="Gelar Akademik" :value="$counselor?->title ?? ''" placeholder="Contoh: S.Pd., Kons." />
                        </div>

                        <div class="col-md-6">
                            <x-form-input name="nip_nuptk" label="NIP / NUPTK" :value="$activeProfile?->nip_nuptk ?? ''" placeholder="Masukkan NIP atau NUPTK" />
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label fw-semibold small text-slate-700">Jenis Kelamin</label>
                            @php
                                $currentGender = old('gender', $activeProfile?->gender ?? 'L');
                            @endphp
                            <select name="gender" id="gender" class="form-select">
                                <option value="L" {{ $currentGender === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $currentGender === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <x-form-input name="email" label="Alamat Email" type="email" :value="$user->email" placeholder="email@sekolah.sch.id" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="phone" label="No. Handphone / WhatsApp" :value="$activeProfile?->phone ?? ''" placeholder="Contoh: 081234567890" />
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-slate-700">Foto Profil Akun</label>
                            <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                                <div class="position-relative flex-shrink-0">
                                    <img id="photoPreview" 
                                         src="{{ $currentPhoto }}" 
                                         alt="Pratinjau Foto" 
                                         class="rounded-circle shadow-sm border border-2 border-teal" 
                                         width="76" 
                                         height="76" 
                                         style="object-fit: cover; background: #fff;">
                                    <span id="previewBadge" class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark border shadow-xs d-none" style="font-size: 0.65rem;">
                                        <i class="bi bi-eye"></i> Baru
                                    </span>
                                </div>
                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-1">
                                        <span class="small fw-bold text-slate-800">Unggah Foto Baru</span>
                                        <span id="photoFileInfo" class="small text-muted fs-xs"></span>
                                    </div>
                                    <div class="input-group">
                                        <input type="file" name="photo" id="photoInput" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <button class="btn btn-outline-secondary d-none" type="button" id="btnCancelPhoto" title="Batalkan pilihan foto">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small text-muted mt-1 mb-0">Format didukung: JPG, PNG, atau WEBP. Ukuran maksimal 2 MB. Sebelum disimpan, foto langsung dapat dilihat pada pratinjau.</div>
                                    <div id="photoError" class="text-danger small mt-1 d-none fw-semibold"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.password.change') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
                            <i class="bi bi-key me-1"></i> Ubah Password
                        </a>
                        <button type="submit" class="btn btn-primary w-100 w-sm-auto fw-bold px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
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
    const headerProfileAvatar = document.getElementById('headerProfileAvatar');
    const previewBadge = document.getElementById('previewBadge');
    const photoFileInfo = document.getElementById('photoFileInfo');
    const btnCancelPhoto = document.getElementById('btnCancelPhoto');
    const photoError = document.getElementById('photoError');
    const initialPhotoUrl = photoPreview ? photoPreview.src : '';

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function (e) {
            const file = e.target.files && e.target.files[0];
            if (!file) {
                resetPreview();
                return;
            }

            if (!file.type.match('image.*')) {
                showError('File yang dipilih harus berupa gambar (JPG, PNG, atau WEBP).');
                photoInput.value = '';
                resetPreview();
                return;
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                showError('Ukuran file terlalu besar (' + (file.size / 1024 / 1024).toFixed(2) + ' MB). Maksimal 2 MB.');
                photoInput.value = '';
                resetPreview();
                return;
            }

            clearError();

            const reader = new FileReader();
            reader.onload = function (event) {
                photoPreview.src = event.target.result;
                if (headerProfileAvatar) {
                    headerProfileAvatar.src = event.target.result;
                }
                if (previewBadge) previewBadge.classList.remove('d-none');
                if (btnCancelPhoto) btnCancelPhoto.classList.remove('d-none');
                if (photoFileInfo) {
                    const sizeKb = (file.size / 1024).toFixed(0);
                    photoFileInfo.textContent = file.name + ' (' + sizeKb + ' KB)';
                }
            };
            reader.readAsDataURL(file);
        });

        if (btnCancelPhoto) {
            btnCancelPhoto.addEventListener('click', function () {
                photoInput.value = '';
                resetPreview();
                clearError();
            });
        }
    }

    function resetPreview() {
        if (photoPreview) photoPreview.src = initialPhotoUrl;
        if (headerProfileAvatar) headerProfileAvatar.src = initialPhotoUrl;
        if (previewBadge) previewBadge.classList.add('d-none');
        if (btnCancelPhoto) btnCancelPhoto.classList.add('d-none');
        if (photoFileInfo) photoFileInfo.textContent = '';
    }

    function showError(msg) {
        if (photoError) {
            photoError.textContent = msg;
            photoError.classList.remove('d-none');
        } else {
            alert(msg);
        }
    }

    function clearError() {
        if (photoError) {
            photoError.textContent = '';
            photoError.classList.add('d-none');
        }
    }
});
</script>
@endpush
