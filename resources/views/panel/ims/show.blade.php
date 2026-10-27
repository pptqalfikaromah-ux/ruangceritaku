@extends('layouts.panel')

@section('title', 'Detail Jawaban Asesmen IMS - ' . $assessment->student->name)

@section('content')
<div class="space-y-6">
    <!-- Header Back & Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white p-4 rounded-3 border">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('panel.ims.monitoring') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-emerald-subtle text-emerald px-2 py-1 fs-xs fw-semibold rounded-pill">Hasil Asesmen IMS</span>
                    @if($assessment->isSubmitted())
                        <span class="badge bg-success-subtle text-success px-2 py-1 fs-xs fw-medium rounded-pill">Terkirim</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning px-2 py-1 fs-xs fw-medium rounded-pill">Draft</span>
                    @endif
                </div>
                <h1 class="h5 fw-bold text-dark mb-0">Lembar Hasil Pengisian Instrumen Masalah Siswa</h1>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panel.konseling.index', ['student_id' => $assessment->student_id]) }}" class="btn btn-primary btn-sm bg-emerald border-0 text-white">
                <i class="bi bi-calendar-plus me-1"></i> Jadwalkan Konseling
            </a>
        </div>
    </div>

    <!-- Student Info Card with Confidentiality Alert -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-emerald text-white p-4 text-center">
                    <img src="{{ $assessment->student->photo_url }}" alt="{{ $assessment->student->name }}" class="rounded-circle border border-3 border-white shadow-sm mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <h5 class="fw-bold mb-1">{{ $assessment->student->name }}</h5>
                    <div class="fs-xs opacity-75">NISN: {{ $assessment->student->nisn }} &bull; {{ $assessment->student->gender_label }}</div>
                </div>
                <div class="card-body p-3 fs-sm space-y-2">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Kelas / Rombel</span>
                        <span class="fw-semibold text-dark">
                            {{ $assessment->student->classrooms->first()?->name ?? '-' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Tahun Ajaran</span>
                        <span class="fw-semibold text-dark">{{ $assessment->academicYear?->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Semester</span>
                        <span class="fw-semibold text-dark">Semester {{ $assessment->semester?->semester ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Waktu Kirim</span>
                        <span class="fw-semibold text-dark">
                            {{ $assessment->submitted_at ? $assessment->submitted_at->translatedFormat('d F Y, H:i') : 'Draft' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Masalah Dipilih</span>
                        <span class="badge bg-danger-subtle text-danger fs-xs fw-bold px-2 py-1 rounded-pill">
                            {{ $totalSelected }} dari 180 Butir
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kerahasiaan Dokumen Notice -->
            <div class="card border-0 shadow-sm rounded-3 bg-warning-subtle text-warning-emphasis p-3 mt-3">
                <div class="d-flex gap-2">
                    <i class="bi bi-shield-lock-fill fs-5 mt-1"></i>
                    <div>
                        <div class="fw-bold fs-sm">Asas Kerahasiaan BK</div>
                        <p class="fs-xs mb-0 mt-1">
                            Seluruh data lembar jawaban dan catatan murid di bawah ini dilindungi kode etik bimbingan konseling dan tidak diperkenankan disebarluaskan di luar penanganan konselor.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Catatan Akhir Murid -->
            <div class="card border-0 shadow-sm rounded-3 mt-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <span class="fw-semibold text-dark fs-sm"><i class="bi bi-chat-quote me-1 text-emerald"></i> Catatan Penutup Murid</span>
                </div>
                <div class="card-body p-3 fs-sm">
                    @if($assessment->final_notes)
                        <div class="p-3 bg-light rounded-3 text-dark italic border">
                            "{{ $assessment->final_notes }}"
                        </div>
                    @else
                        <div class="text-muted fst-italic">Murid tidak menyertakan catatan penutup tambahan.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Answers Grouped by Bidang -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-semibold text-dark">Rincian Jawaban Per Bidang Bimbingan</span>
                    <span class="badge bg-light text-muted border">45 Butir / Bidang</span>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-fill bg-light px-3 pt-2 border-bottom" id="imsTabs" role="tablist">
                        @foreach($answersByField as $fieldName => $answers)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active fw-bold text-emerald' : 'text-muted' }} fs-xs text-uppercase" 
                                        id="tab-{{ Str::slug($fieldName) }}" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#content-{{ Str::slug($fieldName) }}" 
                                        type="button" role="tab">
                                    {{ $fieldName }}
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">
                                        {{ $answers->where('is_selected', true)->count() }} Masalah
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content p-3" id="imsTabContents">
                        @foreach($answersByField as $fieldName => $answers)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                 id="content-{{ Str::slug($fieldName) }}" 
                                 role="tabpanel">
                                
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light fs-xs text-muted">
                                            <tr>
                                                <th style="width: 50px;" class="text-center">No</th>
                                                <th>Pernyataan Masalah</th>
                                                <th style="width: 140px;">Layanan BK</th>
                                                <th style="width: 110px;" class="text-center">Jawaban</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-xs">
                                            @forelse($answers as $ans)
                                                <tr class="{{ $ans->is_selected ? 'table-warning-subtle' : '' }}">
                                                    <td class="text-center fw-bold text-muted">
                                                        #{{ $ans->statement?->statement_number }}
                                                    </td>
                                                    <td>
                                                        <div class="{{ $ans->is_selected ? 'fw-bold text-dark' : 'text-secondary' }}">
                                                            {{ $ans->statement?->statement_text }}
                                                        </div>
                                                        @if($ans->student_note)
                                                            <div class="mt-1 p-2 bg-white rounded border border-warning text-dark fs-xs">
                                                                <span class="text-muted fw-bold"><i class="bi bi-pencil-fill text-warning me-1"></i> Catatan Siswa:</span>
                                                                {{ $ans->student_note }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-2">
                                                            {{ $ans->statement?->serviceType?->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($ans->is_selected)
                                                            <span class="badge bg-danger text-white px-2 py-1 rounded-pill fw-bold">
                                                                <i class="bi bi-check-lg"></i> Ya (Dialami)
                                                            </span>
                                                        @else
                                                            <span class="text-muted fs-xs">Tidak</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">
                                                        Belum ada jawaban tersimpan untuk bidang ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
