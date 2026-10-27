@extends('layouts.panel')

@section('title', 'Ubah Password')

@section('content')
<x-page-header title="Ubah Password Akun"
               subtitle="Pastikan password baru Anda kuat, aman, dan mudah diingat.">
</x-page-header>

<div class="row">
    <div class="col-lg-7">
        @if($user->must_change_password)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="bi bi-shield-exclamation fs-3 me-3 text-warning"></i>
                <div>
                    <div class="fw-bold">Pemberitahuan Wajib Ganti Password</div>
                    <div class="small">Akun Anda masih menggunakan password awal dari sistem. Silakan tentukan password baru yang aman untuk dapat mengakses seluruh fitur panel Ruang Ceritaku.</div>
                </div>
            </div>
        @endif

        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(!$user->must_change_password)
                        <x-form-input name="current_password" label="Password Saat Ini" type="password" placeholder="Masukkan password yang saat ini digunakan" required />
                    @endif

                    <x-form-input name="password" label="Password Baru" type="password" placeholder="Minimal 6 karakter" required help="Gunakan kombinasi huruf, angka, atau simbol" />

                    <x-form-input name="password_confirmation" label="Konfirmasi Password Baru" type="password" placeholder="Ulangi password baru" required />

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        @if(!$user->must_change_password)
                            <a href="{{ route('panel.dashboard') }}" class="btn btn-light border">Batal</a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i> Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
