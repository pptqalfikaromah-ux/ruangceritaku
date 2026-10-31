@extends('layouts.student')

@section('title', 'Pamflet Kreativitas Saya')

@section('content')
<div class="row g-4">
    <!-- Header Banner -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white small fw-bold mb-2">
                            <i class="bi bi-palette-fill"></i> Ruang Karya Murid
                        </div>
                        <h4 class="fw-bold mb-1">Galeri Pamflet & Kreativitas Siswa</h4>
                        <p class="mb-0 text-white-50 small">
                            Bagikan poster, pamflet motivasi, tips belajar, atau karya seni kreatifmu. Karya yang disetujui Guru BK akan tayang di Dashboard seluruh sekolah!
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('murid.pamflet.create') }}" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-cloud-arrow-up-fill fs-5"></i> Unggah Karya Baru
                        </a>
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

    <!-- Daftar Pamflet Saya -->
    <div class="col-12">
        @if($pamphlets->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body py-5">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-teal-subtle text-teal" style="width: 80px; height: 80px;">
                            <i class="bi bi-images fs-1 text-teal" style="color: #0d9488;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark">Belum Ada Karya yang Diunggah</h5>
                    <p class="text-muted small mb-4 mx-auto" style="max-width: 460px;">
                        Ayo tunjukkan bakat dan kreativitasmu! Unggah gambar poster atau pamflet karyamu (format JPG/PNG maks 1 MB) untuk disetujui Guru BK.
                    </p>
                    <a href="{{ route('murid.pamflet.create') }}" class="btn btn-teal text-white fw-semibold px-4 py-2 rounded-pill" style="background-color: #0d9488;">
                        <i class="bi bi-plus-lg me-1"></i> Unggah Pamflet Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($pamphlets as $pamphlet)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column transition-hover">
                            <!-- Thumbnail Gambar -->
                            <div class="position-relative overflow-hidden" style="height: 220px; background-color: #f1f5f9;">
                                <img src="{{ $pamphlet->image_url }}" alt="{{ $pamphlet->title }}" class="w-100 h-100 object-fit-cover">
                                
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge {{ $pamphlet->status_badge_class }} rounded-pill px-3 py-2 shadow-sm small">
                                        @if($pamphlet->status === 'disetujui')
                                            <i class="bi bi-check-circle-fill me-1"></i> Tayang di Dashboard
                                        @elseif($pamphlet->status === 'ditolak')
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        @else
                                            <i class="bi bi-clock-history me-1"></i> Menunggu Moderasi
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Konten -->
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $pamphlet->title }}</h6>
                                <div class="text-muted small mb-3">
                                    <i class="bi bi-calendar3 me-1"></i> Diunggah {{ $pamphlet->created_at->translatedFormat('d M Y, H:i') }}
                                </div>

                                @if($pamphlet->description)
                                    <p class="text-muted small mb-3 flex-grow-1" style="font-size: .83rem;">
                                        {{ Str::limit($pamphlet->description, 100) }}
                                    </p>
                                @endif

                                @if($pamphlet->status === 'ditolak' && $pamphlet->rejection_reason)
                                    <div class="alert alert-danger p-2 rounded-3 small mb-3">
                                        <div class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Catatan Penolakan:</div>
                                        <div>{{ $pamphlet->rejection_reason }}</div>
                                    </div>
                                @endif

                                @if($pamphlet->status === 'disetujui')
                                    <div class="p-2 rounded-3 bg-success-subtle text-success small mb-3">
                                        <i class="bi bi-check-all me-1"></i> Telah disetujui dan aktif tampil di carousel Dashboard seluruh warga sekolah.
                                    </div>
                                @endif

                                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#previewModal{{ $pamphlet->id }}">
                                        <i class="bi bi-eye me-1"></i> Lihat Penuh
                                    </button>

                                    <form action="{{ route('murid.pamflet.destroy', $pamphlet->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karya pamflet ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Preview Gambar Penuh -->
                    <div class="modal fade" id="previewModal{{ $pamphlet->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                                <div class="modal-header border-0 bg-light">
                                    <div>
                                        <h6 class="modal-title fw-bold text-dark">{{ $pamphlet->title }}</h6>
                                        <span class="badge {{ $pamphlet->status_badge_class }} rounded-pill small">{{ $pamphlet->status_label }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 text-center bg-dark bg-opacity-10">
                                    <img src="{{ $pamphlet->image_url }}" alt="{{ $pamphlet->title }}" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                                </div>
                                @if($pamphlet->description)
                                    <div class="modal-footer border-0 bg-white text-start d-block">
                                        <div class="fw-bold small text-muted mb-1">Deskripsi / Pesan Karya:</div>
                                        <p class="small text-dark mb-0">{{ $pamphlet->description }}</p>
                                    </div>
                                @endif
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
