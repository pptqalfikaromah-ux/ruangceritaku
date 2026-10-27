@extends('layouts.panel')

@section('title', 'Ubah Data Murid')

@section('content')
<x-page-header title="Ubah Data Murid"
               subtitle="Perbarui data murid {{ $murid->name }}.">
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
                <form action="{{ route('panel.murid.update', $murid->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3">Identitas Utama Murid</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="nisn" label="NISN" :value="$murid->nisn" required />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="nis" label="NIS" :value="$murid->nis" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <x-form-input name="name" label="Nama Lengkap Murid" :value="$murid->name" required />
                        </div>
                        <div class="col-md-4">
                            <x-form-select name="gender" label="Jenis Kelamin" required>
                                <option value="L" {{ old('gender', $murid->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $murid->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </x-form-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <x-form-input name="birth_place" label="Tempat Lahir" :value="$murid->birth_place" />
                        </div>
                        <div class="col-md-4">
                            <x-form-input name="birth_date" label="Tanggal Lahir" type="date" :value="$murid->birth_date?->format('Y-m-d')" />
                        </div>
                        <div class="col-md-4">
                            <x-form-select name="religion" label="Agama">
                                <option value="">-- Pilih Agama --</option>
                                @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                    <option value="{{ $agm }}" {{ old('religion', $murid->religion) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </x-form-select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-semibold small text-slate-700">Alamat Tempat Tinggal</label>
                        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $murid->address) }}</textarea>
                    </div>

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3 mt-4">Data Orang Tua / Wali</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input name="guardian_name" label="Nama Orang Tua / Wali" :value="$murid->guardian_name" />
                        </div>
                        <div class="col-md-6">
                            <x-form-input name="guardian_phone" label="No. Handphone / WhatsApp Wali" :value="$murid->guardian_phone" />
                        </div>
                    </div>

                    <h6 class="fw-bold text-slate-800 border-bottom pb-2 mb-3 mt-4">Penempatan & Status</h6>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <x-form-select name="classroom_id" label="Rombel Saat Ini (Tahun Ajaran Aktif)">
                                <option value="">-- Tidak Terdaftar di Rombel --</option>
                                @foreach($classrooms as $cr)
                                    <option value="{{ $cr->id }}" {{ old('classroom_id', $currentClassroom?->id) == $cr->id ? 'selected' : '' }}>
                                        {{ $cr->name }} (Kelas {{ $cr->level }}) - TA: {{ $cr->academicYear?->name }}
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>
                        <div class="col-md-3">
                            <x-form-select name="status" label="Status Murid" required>
                                <option value="aktif" {{ old('status', $murid->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status', $murid->status) === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="pindah" {{ old('status', $murid->status) === 'pindah' ? 'selected' : '' }}>Pindah</option>
                                <option value="nonaktif" {{ old('status', $murid->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </x-form-select>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="photo" class="form-label fw-semibold small text-slate-700">Ganti Foto</label>
                                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.murid.index') }}" class="btn btn-light border">Batal</a>
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
