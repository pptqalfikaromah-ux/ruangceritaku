@extends('layouts.panel')

@section('title', 'Penugasan Wali Kelas')
@section('breadcrumb')
    <a href="{{ route('panel.wali-kelas.index') }}">Data Wali Kelas</a>
    <span class="divider">/</span>
    <span class="current">Penugasan Rombel</span>
@endsection

@section('content')
<div class="card card-app mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-diagram-3-fill text-warning me-1.5"></i> Penugasan Wali Kelas ke Rombel
            </h5>
            <div class="text-muted fs-xs">
                Tahun Ajaran: <strong>{{ ($activeYear ?? active_academic_year())?->name ?? '2025/2026' }}</strong> &bull; Semester: <strong>{{ ($activeSemester ?? $activeSem ?? active_semester())?->name ?? 'Ganjil' }}</strong>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('panel.wali-kelas.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-bold">
                <i class="bi bi-people me-1"></i> Data Wali Kelas
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Rombel / Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan / Peminatan</th>
                        <th>Wali Kelas yang Bertugas</th>
                        <th>Catatan Khusus</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classrooms as $index => $room)
                        @php
                            $assignment = $room->currentHomeroomAssignment;
                            $assignedTeacher = $assignment?->teacher;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
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
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2.5 py-1 fw-bold"
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

                        <!-- Modal Tugaskan Wali Kelas -->
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
                                                <select name="teacher_id" class="form-select" required>
                                                    <option value="">-- Pilih Wali Kelas --</option>
                                                    @foreach($teachers as $t)
                                                        <option value="{{ $t->id }}" {{ ($assignedTeacher?->id === $t->id) ? 'selected' : '' }}>
                                                            {{ $t->name }} (NIP: {{ $t->nip_nuptk ?? '-' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text fs-xs text-muted">Hanya wali kelas berstatus aktif yang ditampilkan.</div>
                                            </div>

                                            <div class="mb-0">
                                                <label class="form-label fw-bold small text-dark">Catatan Penugasan (Opsional)</label>
                                                <textarea name="notes" rows="2" class="form-control" placeholder="Catatan tambahan penugasan...">{{ $assignment?->notes }}</textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer border-top bg-light">
                                            <button type="button" class="btn btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark shadow-sm">
                                                Simpan Penugasan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Belum ada data rombel kelas. Tambahkan rombel terlebih dahulu di menu Data Rombel.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
