@php
    $currentRole = auth()->user()?->role ?? 'murid';
    $announcements = \App\Models\Announcement::active()
        ->forRole($currentRole)
        ->orderByDesc('is_pinned')
        ->latest()
        ->limit(5)
        ->get();
@endphp

@if($announcements->isNotEmpty())
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: linear-gradient(135deg, #0d9488, #14b8a6);">
                    <i class="bi bi-broadcast fs-6"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0">Pengumuman & Informasi Madrasah</h6>
            </div>
            <span class="badge bg-teal-subtle text-teal rounded-pill px-3 py-1 small" style="background-color: #ccfbf1; color: #0f766e;">
                {{ $announcements->count() }} Pengumuman
            </span>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($announcements as $announcement)
                    <div class="list-group-item p-3 p-md-4 {{ $announcement->is_pinned ? 'bg-light bg-opacity-50' : '' }} border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-2 mb-2">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @if($announcement->is_pinned)
                                    <span class="badge bg-danger rounded-pill px-2 py-1 small">
                                        <i class="bi bi-pin-angle-fill me-1"></i> Disematkan
                                    </span>
                                @endif
                                <span class="badge {{ $announcement->category_badge_class }} rounded-pill px-2 py-1 small text-uppercase fw-bold" style="font-size: .7rem;">
                                    {{ $announcement->category }}
                                </span>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: .95rem;">
                                    {{ $announcement->title }}
                                </h6>
                            </div>
                            <div class="text-muted small" style="font-size: .75rem;">
                                <i class="bi bi-calendar3 me-1"></i> {{ $announcement->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>

                        {{-- Tampilan Poster/Gambar Pengumuman jika ada --}}
                        @if($announcement->image_url)
                            <div class="my-3 text-center">
                                <a href="{{ $announcement->image_url }}" target="_blank" class="d-inline-block text-decoration-none">
                                    <img src="{{ $announcement->image_url }}" 
                                         alt="{{ $announcement->title }}" 
                                         class="img-fluid rounded-3 border shadow-xs" 
                                         style="max-height: 280px; width: auto; object-fit: cover; transition: transform 0.2s;"
                                         onmouseover="this.style.transform='scale(1.01)'"
                                         onmouseout="this.style.transform='scale(1)'">
                                    <div class="text-muted fs-xxs mt-1"><i class="bi bi-zoom-in me-1"></i>Klik untuk melihat gambar penuh</div>
                                </a>
                            </div>
                        @endif

                        <div class="text-dark small lh-base" style="font-size: .85rem;">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>

                        <div class="mt-2 text-muted small d-flex align-items-center gap-2" style="font-size: .75rem;">
                            <span><i class="bi bi-person-fill me-1"></i> Diterbitkan oleh: <strong>{{ $announcement->author?->name ?? 'Admin Madrasah' }}</strong></span>
                            @if($announcement->target_role !== 'all')
                                <span>&bull;</span>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill">Target: {{ $announcement->target_role_label }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
