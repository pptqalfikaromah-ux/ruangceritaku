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
                        $currentPhoto = $counselor?->photo ? asset('storage/' . $counselor->photo) : null;
                    @endphp

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 mb-4 pb-3 border-bottom">
                        @if($currentPhoto)
                            <img src="{{ $currentPhoto }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm" width="70" height="70" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-teal-subtle text-primary-teal d-flex align-items-center justify-content-center fw-bold fs-3 shadow-xs" style="width: 70px; height: 70px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
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
                            <label for="photo" class="form-label fw-semibold small text-slate-700">Ganti Foto Profil</label>
                            <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text small text-muted">Format foto: JPG atau PNG, ukuran maksimal 2MB.</div>
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
