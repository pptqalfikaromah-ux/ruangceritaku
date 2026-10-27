@extends('layouts.panel')

@section('title', 'Tugaskan Guru BK ke Rombel')

@section('content')
<x-page-header title="Tugaskan Guru BK ke Rombel"
               subtitle="Pilih Guru BK dan rombel yang akan dibina untuk semester tertentu.">
    <x-slot:actions>
        <a href="{{ route('panel.penugasan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penugasan
        </a>
    </x-slot:actions>
</x-page-header>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-app">
            <div class="card-body p-4">
                <form action="{{ route('panel.penugasan.store') }}" method="POST">
                    @csrf

                    <x-form-select name="semester_id" label="Semester Penugasan" required help="Pilih semester di mana penugasan ini berlaku">
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ old('semester_id', $activeSemester?->id) == $sem->id ? 'selected' : '' }}>
                                {{ $sem->full_name }} {{ $sem->is_active ? '(Semester Aktif)' : '' }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <x-form-select name="counselor_id" label="Pilih Guru BK" required>
                        <option value="">-- Pilih Guru BK --</option>
                        @foreach($counselors as $counselor)
                            <option value="{{ $counselor->id }}" {{ old('counselor_id') == $counselor->id ? 'selected' : '' }}>
                                {{ $counselor->full_name_with_title }} (NIP: {{ $counselor->nip_nuptk ?? '-' }})
                            </option>
                        @endforeach
                    </x-form-select>

                    <x-form-select name="classroom_id" label="Pilih Rombel / Kelas Binaan" required help="Satu rombel hanya dapat memiliki satu Guru BK utama per semester.">
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($classrooms as $cr)
                            <option value="{{ $cr->id }}" {{ old('classroom_id') == $cr->id ? 'selected' : '' }}>
                                {{ $cr->name }} (Kelas {{ $cr->level }}) - TA: {{ $cr->academicYear?->name }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-semibold small text-slate-700">Catatan Penugasan</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Contoh: Pembina utama konseling kelas 7">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('panel.penugasan.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
