@extends('layouts.panel')

@section('title', 'Tambah Rombel Baru')

@section('content')
<x-page-header title="Tambah Rombel Baru"
               subtitle="Tambahkan rombongan belajar/kelas pada tahun ajaran tertentu.">
    <x-slot:actions>
        <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.rombel.store') }}" method="POST">
                    @csrf

                    <x-form-select name="academic_year_id" label="Tahun Ajaran" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', active_academic_year()?->id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="name" label="Nama Rombel / Kelas" placeholder="Contoh: VII-A atau X-IPA-1" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="level" label="Tingkat / Kelas" placeholder="Contoh: 7, 8, 9 atau X, XI, XII" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="major" label="Jurusan / Peminatan" placeholder="Contoh: IPA, IPS, RPL, atau umum" help="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="homeroom_teacher" label="Nama Wali Kelas" placeholder="Nama lengkap wali kelas beserta gelar" help="Opsional" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.rombel.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Rombel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
