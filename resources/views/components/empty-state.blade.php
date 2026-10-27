@props(['icon' => 'bi-folder-x', 'title' => 'Belum Ada Data', 'description' => 'Data untuk kategori ini belum tersedia atau tidak ditemukan.'])

<div class="empty-state">
    <div class="empty-state-icon">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h5 class="fw-bold text-slate-700 mb-1">{{ $title }}</h5>
    <p class="text-muted small mb-3 mx-auto" style="max-width: 400px;">{{ $description }}</p>
    @if(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>
