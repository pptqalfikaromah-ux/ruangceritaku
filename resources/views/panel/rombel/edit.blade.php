@extends('layouts.panel')

@section('title', 'Ubah Rombel')

@section('content')
<x-page-header title="Ubah Data Rombel"
               subtitle="Perbarui informasi rombongan belajar/kelas.">
    <x-slot:actions>
        <a href="{{ route('panel.rombel.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.rombel.update', $rombel->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <x-form-select name="academic_year_id" label="Tahun Ajaran" required>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $rombel->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="name" label="Nama Rombel / Kelas" :value="$rombel->name" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="level" label="Tingkat / Kelas" :value="$rombel->level" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="major" label="Jurusan / Peminatan" :value="$rombel->major" help="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="homeroom_teacher" label="Nama Wali Kelas" :value="$rombel->homeroom_teacher" help="Opsional" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.rombel.index') }}" class="btn btn-light border">Batal</a>
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
