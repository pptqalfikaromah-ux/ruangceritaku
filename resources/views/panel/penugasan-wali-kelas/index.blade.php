@extends('layouts.panel')

@section('title', 'Penugasan Wali Kelas')
@section('breadcrumb')
    <a href="{{ route('panel.wali-kelas.index') }}">Data Wali Kelas</a>
    <span class="divider">/</span>
    <span class="current">Penugasan Rombel</span>
@endsection

@section('content')
<div class="card card-app mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h5 class="fw-bold mb-0 text-dark fs-6 fs-md-5">
                <i class="bi bi-diagram-3-fill text-warning me-1.5"></i> Penugasan Wali Kelas ke Rombel
            </h5>
            <div class="text-muted fs-xxs fs-md-xs mt-0.5">
                Tahun Ajaran: <strong>{{ ($activeYear ?? active_academic_year())?->name ?? '2025/2026' }}</strong> &bull; Semester: <strong>{{ ($activeSemester ?? $activeSem ?? active_semester())?->name ?? 'Ganjil' }}</strong>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('panel.wali-kelas.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 fw-bold fs-xs shadow-xs">
                <i class="bi bi-people me-1"></i> Data Wali Kelas
            </a>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
<!-- ========================================== -->
<div class="d-md-none mb-3">
    @forelse($classrooms as $index => $room)
        @php
            $assignment = $room->currentHomeroomAssignment;
            $assignedTeacher = $assignment?->teacher;
        @endphp
        <div class="mobile-card mb-3">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pb-3 border-bottom">
                <div>
                    <h3 class="card-name mb-1">Kelas {{ $room->name }}</h3>
                    <div class="card-sub">
                        Tingkat {{ $room->level }} &bull; {{ $room->major ?? 'Reguler' }}
                    </div>
                </div>
                @if($assignedTeacher)
                    <span class="badge bg-success-subtle text-success badge-pill-custom flex-shrink-0">
                        <i class="bi bi-check-circle me-1"></i>Ditugaskan
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger badge-pill-custom flex-shrink-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>Kosong
                    </span>
                @endif
            </div>

            <div class="card-info-box mb-3">
                @if($assignedTeacher)
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2.5 rounded-circle bg-warning-subtle text-warning-emphasis flex-shrink-0">
                            <i class="bi bi-mortarboard-fill fs-5"></i>
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="fw-bold text-dark fs-6 text-truncate mb-0.5">{{ $assignedTeacher->name }}</div>
                            <div class="text-muted small">NIP: {{ $assignedTeacher->nip_nuptk ?? '-' }} &bull; User: {{ $assignedTeacher->user?->username ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-muted small fst-italic py-1">
                        <i class="bi bi-info-circle me-1.5 text-danger"></i> Belum ada wali kelas yang ditugaskan ke rombel ini.
                    </div>
                @endif
                @if($assignment?->notes)
                    <div class="text-muted small border-top pt-2 mt-2">
                        <i class="bi bi-chat-left-text me-1.5 text-mint"></i>Catatan: {{ $assignment->notes }}
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" 
                        class="btn btn-outline-warning text-dark btn-card-action flex-grow-1"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalAssign{{ $room->id }}">
                    <i class="bi bi-pencil me-1"></i> {{ $assignedTeacher ? 'Ganti Wali Kelas' : 'Tugaskan Wali Kelas' }}
                </button>

                @if($assignment)
                    <form action="{{ route('panel.penugasan-wali-kelas.destroy', $assignment) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Lepas penugasan wali kelas untuk rombel {{ $room->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light border text-danger btn-card-icon" title="Lepas Penugasan">
                            <i class="bi bi-x-circle fs-5"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="mobile-card text-center py-5 px-3 mb-3">
            <p class="text-muted fs-xs mb-0">Tidak ada rombel belajar pada tahun ajaran ini.</p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 2. TAMPILAN DESKTOP: TABEL (>= 768px)      -->
<!-- ========================================== -->
<div class="d-none d-md-block">
    <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.04em;">
                    <tr>
                        <th style="width: 50px;" class="ps-3.5 text-center">No</th>
                        <th>Nama Rombel / Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan / Peminatan</th>
                        <th>Wali Kelas yang Bertugas</th>
                        <th>Catatan Khusus</th>
                        <th class="text-center pe-3.5" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse($classrooms as $index => $room)
                        @php
                            $assignment = $room->currentHomeroomAssignment;
                            $assignedTeacher = $assignment?->teacher;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted ps-3.5">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">Kelas {{ $room->name }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                    Tingkat {{ $room->level }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $room->major ?? 'Reguler' }}</td>
                            <td>
                                @if($assignedTeacher)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-1.5 rounded-circle bg-warning-subtle text-warning-emphasis">
                                            <i class="bi bi-mortarboard-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $assignedTeacher->name }}</div>
                                            <div class="text-muted fs-xxs">NIP: {{ $assignedTeacher->nip_nuptk ?? '-' }} &bull; User: {{ $assignedTeacher->user?->username }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Belum Ditugaskan
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $assignment?->notes ?? '-' }}</td>
                            <td class="text-center pe-3.5">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2.5 py-1 fw-bold fs-xs"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalAssign{{ $room->id }}">
                                    <i class="bi bi-pencil me-1"></i> {{ $assignedTeacher ? 'Ganti' : 'Tugaskan' }}
                                </button>

                                @if($assignment)
                                    <form action="{{ route('panel.penugasan-wali-kelas.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('Lepas penugasan wali kelas untuk rombel {{ $room->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger rounded-pill px-2 py-1" title="Lepas Penugasan">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Tidak ada data rombel belajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tugaskan Wali Kelas (Reusable for both views) -->
@foreach($classrooms as $room)
    @php
        $assignment = $room->currentHomeroomAssignment;
        $assignedTeacher = $assignment?->teacher;
    @endphp
    <div class="modal fade" id="modalAssign{{ $room->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('panel.penugasan-wali-kelas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="classroom_id" value="{{ $room->id }}">

                    <div class="modal-header border-bottom">
                        <h6 class="modal-title fw-bold text-dark">
                            <i class="bi bi-diagram-3-fill text-warning me-1.5"></i> Penugasan Wali Kelas &bull; Kelas {{ $room->name }}
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">Pilih Pendidik / Wali Kelas <span class="text-danger">*</span></label>
                            <select name="teacher_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ ($assignedTeacher?->id === $t->id) ? 'selected' : '' }}>
                                        {{ $t->name }} (NIP: {{ $t->nip_nuptk ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text fs-xxs text-muted">Hanya wali kelas berstatus aktif yang ditampilkan.</div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold small text-dark">Catatan Penugasan (Opsional)</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Catatan tambahan penugasan...">{{ $assignment?->notes }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-top bg-light">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-warning rounded-pill px-4 fw-bold text-dark shadow-sm">
                            Simpan Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
