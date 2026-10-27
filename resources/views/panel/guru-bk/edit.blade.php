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

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photo" class="form-label fw-semibold small text-slate-700">Ganti Foto Profil</label>
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
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
