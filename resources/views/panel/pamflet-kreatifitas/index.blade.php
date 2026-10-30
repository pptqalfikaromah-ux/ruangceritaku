@extends('layouts.panel')

@section('title', 'Moderasi Pamflet Kreativitas Siswa')

@section('content')
<div class="row g-4">
    <!-- Header Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white small fw-bold mb-2">
                            <i class="bi bi-images"></i> Portofolio & Kreativitas Murid
                        </div>
                        <h4 class="fw-bold mb-1">Moderasi Pamflet Kreativitas Siswa</h4>
                        <p class="mb-0 text-white-50 small">
                            Tinjau dan setujui karya pamflet, poster motivasi, dan karya seni yang diunggah siswa untuk ditampilkan pada Carousel Dashboard seluruh akun.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-clock-history me-1"></i> {{ $counts['pending'] }} Perlu Moderasi
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 py-3">
                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                <div class="small fw-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="col-12">
            <div class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 py-3">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                <div class="small fw-semibold">{{ session('warning') }}</div>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 py-3">
                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                <div class="small fw-semibold">{{ session('info') }}</div>
            </div>
        </div>
    @endif

    <!-- Tab Filter Status (Scrollable on Mobile) -->
    <div class="col-12">
        <div class="d-flex gap-2 overflow-x-auto pb-1 no-scrollbar">
            <a class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold text-nowrap shadow-xs {{ $status === 'all' ? 'btn-mint text-white' : 'btn-light border text-dark' }}" 
               href="{{ route('panel.pamflet-kreatifitas.index', ['status' => 'all']) }}">
                Semua Karya ({{ $counts['all'] }})
            </a>
            <a class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold text-nowrap shadow-xs {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }}" 
               href="{{ route('panel.pamflet-kreatifitas.index', ['status' => 'pending']) }}">
                <i class="bi bi-clock-history me-1"></i> Menunggu Moderasi ({{ $counts['pending'] }})
            </a>
            <a class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold text-nowrap shadow-xs {{ $status === 'disetujui' ? 'btn-success text-white' : 'btn-light border text-dark' }}" 
               href="{{ route('panel.pamflet-kreatifitas.index', ['status' => 'disetujui']) }}">
                <i class="bi bi-check-circle-fill me-1"></i> Disetujui / Tayang ({{ $counts['disetujui'] }})
            </a>
            <a class="btn btn-sm rounded-pill px-3.5 py-2 fw-semibold text-nowrap shadow-xs {{ $status === 'ditolak' ? 'btn-danger text-white' : 'btn-light border text-dark' }}" 
               href="{{ route('panel.pamflet-kreatifitas.index', ['status' => 'ditolak']) }}">
                <i class="bi bi-x-circle-fill me-1"></i> Ditolak ({{ $counts['ditolak'] }})
            </a>
        </div>
    </div>

    <!-- Grid Kartu Pamflet -->
    <div class="col-12">
        @if($pamphlets->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body py-5">
                    <i class="bi bi-images fs-1 text-muted"></i>
                    <h5 class="fw-bold text-dark mt-3">Tidak Ada Karya Ditemukan</h5>
                    <p class="text-muted small mb-0">Belum ada karya pamflet pada kategori status ini.</p>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($pamphlets as $pamphlet)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column">
                            <!-- Image Header -->
                            <div class="position-relative bg-light" style="height: 240px; overflow: hidden;">
                                <img src="{{ $pamphlet->image_url }}" alt="{{ $pamphlet->title }}" class="w-100 h-100 object-fit-cover">
                                
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge {{ $pamphlet->status_badge_class }} rounded-pill px-3 py-2 shadow-sm small">
                                        @if($pamphlet->status === 'disetujui')
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                            @if(!$pamphlet->is_active)
                                                (Dinonaktifkan)
                                            @endif
                                        @elseif($pamphlet->status === 'ditolak')
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        @else
                                            <i class="bi bi-clock-history me-1"></i> Menunggu Moderasi
                                        @endif
                                    </span>
                                </div>

                                <button type="button" class="btn btn-sm btn-dark bg-opacity-75 position-absolute top-0 end-0 m-3 rounded-circle" style="width: 36px; height: 36px;" data-bs-toggle="modal" data-bs-target="#zoomModal{{ $pamphlet->id }}" title="Perbesar Gambar">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>
                            </div>

                            <!-- Body Card -->
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $pamphlet->title }}</h6>
                                
                                <!-- Siswa & Rombel -->
                                <div class="d-flex align-items-center gap-2 mb-3 mt-2">
                                    <div class="rounded-circle bg-teal-subtle text-teal d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold; font-size: .8rem; background-color: #ccfbf1; color: #0f766e;">
                                        {{ substr($pamphlet->student?->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $pamphlet->student?->name ?? 'Siswa' }}</div>
                                        <div class="text-muted" style="font-size: .75rem;">
                                            Kelas: <span class="badge bg-secondary-subtle text-secondary">{{ $pamphlet->classroom?->name ?? $pamphlet->student?->current_classroom?->name ?? '-' }}</span> &bull; NISN: {{ $pamphlet->student?->nisn ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                @if($pamphlet->description)
                                    <div class="p-2 rounded-3 bg-light text-muted small mb-3 flex-grow-1" style="font-size: .8rem;">
                                        {{ Str::limit($pamphlet->description, 120) }}
                                    </div>
                                @endif

                                @if($pamphlet->status === 'ditolak' && $pamphlet->rejection_reason)
                                    <div class="alert alert-danger p-2 rounded-3 small mb-3" style="font-size: .8rem;">
                                        <strong>Catatan Penolakan:</strong> {{ $pamphlet->rejection_reason }}
                                    </div>
                                @endif

                                <div class="text-muted mb-3" style="font-size: .72rem;">
                                    <i class="bi bi-clock me-1"></i> Diunggah {{ $pamphlet->created_at->translatedFormat('d F Y, H:i') }}
                                    @if($pamphlet->approved_at)
                                        <br><i class="bi bi-check2-circle text-success me-1"></i> Disetujui oleh {{ $pamphlet->approver?->name ?? 'Admin' }} ({{ $pamphlet->approved_at->translatedFormat('d M Y') }})
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 border-top d-flex flex-wrap gap-2 justify-content-between align-items-center mt-auto">
                                    @if($pamphlet->status === 'pending')
                                        <form action="{{ route('panel.pamflet-kreatifitas.approve', $pamphlet->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                                <i class="bi bi-check-lg me-1"></i> Setujui & Terbitkan
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pamphlet->id }}">
                                            <i class="bi bi-x-lg me-1"></i> Tolak
                                        </button>
                                    @else
                                        @if($pamphlet->status === 'disetujui')
                                            <form action="{{ route('panel.pamflet-kreatifitas.toggle', $pamphlet->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $pamphlet->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill px-3">
                                                    <i class="bi {{ $pamphlet->is_active ? 'bi-eye-slash' : 'bi-eye' }} me-1"></i>
                                                    {{ $pamphlet->is_active ? 'Sembunyikan' : 'Tayangkan' }}
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('panel.pamflet-kreatifitas.approve', $pamphlet->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                    <i class="bi bi-check-lg me-1"></i> Setujui Ulang
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('panel.pamflet-kreatifitas.destroy', $pamphlet->id) }}" method="POST" onsubmit="return confirm('Hapus permanen karya pamflet ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Zoom Gambar -->
                    <div class="modal fade" id="zoomModal{{ $pamphlet->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                                <div class="modal-header border-0 bg-light">
                                    <div>
                                        <h6 class="modal-title fw-bold text-dark">{{ $pamphlet->title }}</h6>
                                        <span class="small text-muted">Karya oleh {{ $pamphlet->student?->name }} ({{ $pamphlet->classroom?->name ?? 'Siswa' }})</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 text-center bg-dark">
                                    <img src="{{ $pamphlet->image_url }}" alt="{{ $pamphlet->title }}" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                                </div>
                                @if($pamphlet->description)
                                    <div class="modal-footer border-0 bg-white text-start d-block">
                                        <div class="fw-bold small text-muted mb-1">Deskripsi Karya:</div>
                                        <p class="small text-dark mb-0">{{ $pamphlet->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Modal Tolak Karya -->
                    <div class="modal fade" id="rejectModal{{ $pamphlet->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 rounded-4 shadow">
                                <form action="{{ route('panel.pamflet-kreatifitas.reject', $pamphlet->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header border-bottom p-4">
                                        <h6 class="modal-title fw-bold text-danger">
                                            <i class="bi bi-x-circle me-1"></i> Tolak Publikasi Pamflet
                                        </h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="small text-muted mb-3">
                                            Berikan catatan alasan mengapa karya <strong>"{{ $pamphlet->title }}"</strong> milik <strong>{{ $pamphlet->student?->name }}</strong> belum dapat disetujui agar siswa dapat memperbaiki karyanya.
                                        </p>
                                        <div class="mb-3">
                                            <label for="rejection_reason_{{ $pamphlet->id }}" class="form-label fw-bold text-dark small">
                                                Alasan Penolakan <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="rejection_reason" 
                                                      id="rejection_reason_{{ $pamphlet->id }}" 
                                                      rows="3" 
                                                      class="form-control rounded-3" 
                                                      placeholder="Contoh: Resolusi gambar terpotong, atau mohon gunakan bahasa yang lebih santun." 
                                                      required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top p-3">
                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                                            Tolak Karya
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $pamphlets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
