@extends('layouts.panel')

@section('title', 'Manajemen Pengumuman')
@section('page_title', 'Pengumuman Sekolah')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('panel.dashboard') }}">Beranda</a></li>
    <li class="breadcrumb-item active">Pengumuman</li>
@endsection

@section('content')
<div class="space-y-4">
    <!-- Header Banner -->
    <div class="card card-app border-0 shadow-sm rounded-4 bg-white p-3 p-md-4 mb-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2 fs-5 fs-md-4">
                    <i class="bi bi-megaphone-fill text-mint"></i> Pengumuman Madrasah
                </h4>
                <p class="text-muted fs-xs mb-0">
                    Kelola pengumuman resmi yang akan ditayangkan langsung di dashboard seluruh akun pengguna (Admin, Guru BK, Wali Kelas, dan Murid) lengkap dengan dukungan poster/gambar.
                </p>
            </div>
            <div>
                <button type="button" class="btn btn-mint text-white btn-sm rounded-pill px-3.5 py-1.5 shadow-xs fw-bold fs-xs text-nowrap" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg me-1"></i> Buat Pengumuman Baru
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-2.5 px-3 fs-xs mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Info Counter -->
    <div class="d-flex justify-content-between align-items-center px-1 mb-2">
        <span class="fw-extrabold text-dark fs-sm">Daftar Pengumuman Terbit</span>
        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fs-xxs">{{ $announcements->total() }} Pengumuman</span>
    </div>

    <!-- ========================================== -->
    <!-- 1. TAMPILAN MOBILE: CARD LIST (< 768px)    -->
    <!-- ========================================== -->
    <div class="d-md-none mb-3">
        @forelse($announcements as $ann)
            <div class="mobile-card mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3 pb-3 border-bottom">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @if($ann->is_pinned)
                            <span class="badge bg-warning text-dark badge-pill-custom">
                                <i class="bi bi-pin-angle-fill me-1"></i>Pinned
                            </span>
                        @endif
                        <span class="badge {{ $ann->category_badge_class }} badge-pill-custom">
                            {{ $ann->category_label }}
                        </span>
                    </div>
                    @if($ann->is_active)
                        <span class="badge bg-success-subtle text-success badge-pill-custom flex-shrink-0">
                            <i class="bi bi-eye me-1"></i>Tayang
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary badge-pill-custom flex-shrink-0">
                            <i class="bi bi-eye-slash me-1"></i>Nonaktif
                        </span>
                    @endif
                </div>

                @if($ann->image_url)
                    <div class="mb-3 rounded-3 overflow-hidden border position-relative" style="max-height: 180px;">
                        <img src="{{ $ann->image_url }}" alt="{{ $ann->title }}" class="w-100" style="object-fit: cover; max-height: 180px;">
                        <a href="{{ $ann->image_url }}" target="_blank" class="btn btn-sm btn-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2 rounded-pill px-2.5 py-1 fs-xs text-white">
                            <i class="bi bi-zoom-in me-1"></i>Lihat Gambar
                        </a>
                    </div>
                @endif

                <h3 class="card-name mb-2">{{ $ann->title }}</h3>
                <div class="card-info-box mb-3">
                    <p class="text-dark mb-0 small" style="line-height: 1.6; white-space: pre-line;">{{ Str::limit($ann->content, 180) }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between text-muted small pt-2 border-top">
                    <div>
                        @php
                            $targetLabel = match($ann->target_role) {
                                'murid' => 'Khusus Murid',
                                'guru_bk' => 'Khusus Guru BK',
                                'wali_kelas' => 'Khusus Wali Kelas',
                                default => 'Semua Pengguna'
                            };
                        @endphp
                        <span class="badge bg-light text-dark border badge-pill-custom">
                            <i class="bi bi-person me-1"></i>{{ $targetLabel }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-primary btn-card-action" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal{{ $ann->id }}">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <form action="{{ route('panel.pengumuman.destroy', $ann) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light border text-danger btn-card-icon" title="Hapus">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="mobile-card text-center py-5 px-3 mb-3">
                <i class="bi bi-megaphone fs-1 d-block mb-2 text-secondary"></i>
                <p class="text-muted fs-xs mb-0">Belum ada pengumuman yang diterbitkan.</p>
            </div>
        @endforelse
    </div>

    <!-- ========================================== -->
    <!-- 2. TAMPILAN DESKTOP: TABEL (>= 768px)      -->
    <!-- ========================================== -->
    <div class="d-none d-md-block">
        <div class="card card-app border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-xs">
                    <thead class="table-light fs-xxs text-uppercase text-muted" style="letter-spacing: 0.04em;">
                        <tr>
                            <th class="ps-3.5" style="width: 50px;">No</th>
                            <th>Judul & Isi Pengumuman</th>
                            <th style="width: 90px;">Gambar</th>
                            <th>Kategori</th>
                            <th>Target Akun</th>
                            <th>Status</th>
                            <th>Pembuat & Tanggal</th>
                            <th class="text-end pe-3.5" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $ann)
                            <tr>
                                <td class="ps-3.5 text-muted">{{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($ann->is_pinned)
                                            <span class="badge bg-warning text-dark px-1.5 py-0.5 rounded-pill fs-xxs fw-bold" title="Disematkan di atas">
                                                <i class="bi bi-pin-angle-fill"></i> Pinned
                                            </span>
                                        @endif
                                        <div class="fw-bold text-dark fs-sm">{{ $ann->title }}</div>
                                    </div>
                                    <div class="text-muted fs-xxs mt-1" style="max-width: 440px; white-space: pre-line;">{{ Str::limit($ann->content, 120) }}</div>
                                </td>
                                <td>
                                    @if($ann->image_url)
                                        <a href="{{ $ann->image_url }}" target="_blank" class="d-inline-block position-relative rounded overflow-hidden shadow-xs border" style="width: 48px; height: 48px;" title="Lihat gambar asli">
                                            <img src="{{ $ann->image_url }}" alt="{{ $ann->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white p-0.5 rounded-top-start" style="font-size: 8px;">
                                                <i class="bi bi-zoom-in"></i>
                                            </span>
                                        </a>
                                    @else
                                        <span class="text-muted fs-xxs italic">- Tanpa Gambar -</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $ann->category_badge_class }} px-2 py-1 rounded-pill fw-semibold">
                                        {{ $ann->category_label }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $targetLabel = match($ann->target_role) {
                                            'murid' => 'Khusus Murid',
                                            'guru_bk' => 'Khusus Guru BK',
                                            'wali_kelas' => 'Khusus Wali Kelas',
                                            default => 'Semua Pengguna'
                                        };
                                        $targetBadge = match($ann->target_role) {
                                            'murid' => 'bg-emerald-subtle text-emerald',
                                            'guru_bk' => 'bg-info-subtle text-info',
                                            'wali_kelas' => 'bg-warning-subtle text-warning-emphasis',
                                            default => 'bg-secondary-subtle text-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $targetBadge }} px-2 py-1 rounded-pill fw-semibold">
                                        {{ $targetLabel }}
                                    </span>
                                </td>
                                <td>
                                    @if($ann->is_active)
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fw-semibold">
                                            <i class="bi bi-eye me-1"></i> Tayang
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill fw-semibold">
                                            <i class="bi bi-eye-slash me-1"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $ann->author?->name ?? 'Admin' }}</div>
                                    <div class="text-muted fs-xxs">{{ $ann->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </td>
                                <td class="text-end pe-3.5">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle p-1 me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $ann->id }}" 
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('panel.pengumuman.destroy', $ann) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-megaphone fs-2 d-block mb-2 text-secondary"></i>
                                    Belum ada pengumuman yang diterbitkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($announcements->hasPages())
        <div class="card card-app border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="card-body py-2.5 px-3.5 d-flex justify-content-center overflow-x-auto">
                {{ $announcements->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Edit Pengumuman (Reusable for both views) -->
@foreach($announcements as $ann)
    <div class="modal fade" id="editModal{{ $ann->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                <form method="POST" action="{{ route('panel.pengumuman.update', $ann) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fs-sm fw-bold">Edit Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body space-y-3 p-4">
                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-semibold">Judul Pengumuman</label>
                            <input type="text" name="title" value="{{ $ann->title }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Kategori / Urgensi</label>
                                <select name="category" class="form-select form-select-sm" required>
                                    <option value="info" {{ $ann->category === 'info' ? 'selected' : '' }}>Informasi Umum</option>
                                    <option value="penting" {{ $ann->category === 'penting' ? 'selected' : '' }}>Penting & Mendesak</option>
                                    <option value="agenda" {{ $ann->category === 'agenda' ? 'selected' : '' }}>Agenda & Jadwal</option>
                                    <option value="kegiatan" {{ $ann->category === 'kegiatan' ? 'selected' : '' }}>Kegiatan Sekolah</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fs-xs text-muted fw-semibold">Target Pembaca</label>
                                <select name="target_role" class="form-select form-select-sm" required>
                                    <option value="all" {{ $ann->target_role === 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                                    <option value="murid" {{ $ann->target_role === 'murid' ? 'selected' : '' }}>Khusus Murid</option>
                                    <option value="guru_bk" {{ $ann->target_role === 'guru_bk' ? 'selected' : '' }}>Khusus Guru BK</option>
                                    <option value="wali_kelas" {{ $ann->target_role === 'wali_kelas' ? 'selected' : '' }}>Khusus Wali Kelas</option>
                                </select>
                            </div>
                        </div>

                        <!-- Gambar / Poster Pengumuman -->
                        <div class="mb-3 p-3 bg-light rounded-3 border">
                            <label class="form-label fs-xs text-dark fw-bold d-flex align-items-center gap-1.5">
                                <i class="bi bi-image text-mint"></i> Gambar / Poster Pengumuman (Opsional)
                            </label>
                            @if($ann->image_url)
                                <div class="d-flex align-items-center gap-3 mb-2 p-2 bg-white rounded border">
                                    <img src="{{ $ann->image_url }}" alt="Preview" style="height: 64px; width: 64px; object-fit: cover; border-radius: 6px;">
                                    <div>
                                        <div class="fs-xs fw-semibold text-dark">Poster saat ini terpasang</div>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rmImg{{ $ann->id }}">
                                            <label class="form-check-label fs-xs text-danger fw-semibold" for="rmImg{{ $ann->id }}">
                                                Hapus poster ini
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control form-control-sm" accept="image/*" onchange="previewAnnouncementImage(this, 'previewEdit{{ $ann->id }}')">
                            <div class="form-text fs-xxs text-muted mt-1">Format: JPG, JPEG, PNG, WEBP. Maksimal 3MB. Kosongkan jika tidak ingin mengubah.</div>
                            <div id="previewEdit{{ $ann->id }}" class="mt-2 text-center d-none">
                                <img src="" alt="Pratinjau Gambar Baru" class="img-fluid rounded border shadow-xs" style="max-height: 180px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fs-xs text-muted fw-semibold">Isi Pengumuman</label>
                            <textarea name="content" rows="4" class="form-control form-control-sm" required>{{ $ann->content }}</textarea>
                        </div>
                        <div class="d-flex gap-4 pt-2 border-top">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="pinEdit{{ $ann->id }}" {{ $ann->is_pinned ? 'checked' : '' }}>
                                <label class="form-check-label fs-xs text-dark" for="pinEdit{{ $ann->id }}">
                                    Sematkan di Atas (Pin)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="actEdit{{ $ann->id }}" {{ $ann->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fs-xs text-dark" for="actEdit{{ $ann->id }}">
                                    Status Tayang (Aktif)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-mint text-white rounded-pill px-4 fw-bold shadow-xs">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Tambah Pengumuman Baru -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <form method="POST" action="{{ route('panel.pengumuman.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-sm fw-bold">Buat Pengumuman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body space-y-3 p-4">
                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Judul Pengumuman</label>
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Contoh: Jadwal Layanan Bimbingan Kelompok Semester Ganjil" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Kategori / Urgensi</label>
                            <select name="category" class="form-select form-select-sm" required>
                                <option value="info" selected>Informasi Umum</option>
                                <option value="penting">Penting & Mendesak</option>
                                <option value="agenda">Agenda & Jadwal</option>
                                <option value="kegiatan">Kegiatan Sekolah</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-xs text-muted fw-semibold">Target Pembaca</label>
                            <select name="target_role" class="form-select form-select-sm" required>
                                <option value="all" selected>Semua Pengguna</option>
                                <option value="murid">Khusus Murid</option>
                                <option value="guru_bk">Khusus Guru BK</option>
                                <option value="wali_kelas">Khusus Wali Kelas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gambar / Poster Pengumuman -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label fs-xs text-dark fw-bold d-flex align-items-center gap-1.5">
                            <i class="bi bi-image text-mint"></i> Upload Poster / Gambar Pengumuman (Opsional)
                        </label>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*" onchange="previewAnnouncementImage(this, 'previewCreate')">
                        <div class="form-text fs-xxs text-muted mt-1">Format gambar: JPG, JPEG, PNG, WEBP. Maksimal ukuran: 3MB.</div>
                        <div id="previewCreate" class="mt-2 text-center d-none">
                            <img src="" alt="Pratinjau Gambar" class="img-fluid rounded border shadow-xs" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-xs text-muted fw-semibold">Isi Pengumuman</label>
                        <textarea name="content" rows="4" class="form-control form-control-sm" placeholder="Tuliskan isi pengumuman secara jelas dan informatif..." required></textarea>
                    </div>
                    <div class="d-flex gap-4 pt-2 border-top">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="pinCreate">
                            <label class="form-check-label fs-xs text-dark" for="pinCreate">
                                Sematkan di Atas (Pin)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="actCreate" checked>
                            <label class="form-check-label fs-xs text-dark" for="actCreate">
                                Langsung Terbitkan (Aktif)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-mint text-white rounded-pill px-4 fw-bold shadow-xs">Terbitkan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewAnnouncementImage(input, previewContainerId) {
        const container = document.getElementById(previewContainerId);
        if (!container) return;
        const img = container.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (img) {
                    img.src = e.target.result;
                    container.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            if (img) img.src = '';
            container.classList.add('d-none');
        }
    }
</script>
@endpush
@endsection
