@php
    $publishedPamphlets = \App\Models\StudentPamphlet::published()
        ->with(['student', 'classroom'])
        ->limit(10)
        ->get();
@endphp

@if($publishedPamphlets->isNotEmpty())
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #0f172a;">
        <!-- Header Carousel -->
        <div class="p-3 px-4 d-flex align-items-center justify-content-between text-white border-bottom border-secondary border-opacity-25" style="background: rgba(15, 23, 42, 0.85);">
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-1 fw-bold text-dark" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="bi bi-stars me-1"></i> ETALASE KREATIVITAS SISWA
                </span>
                <span class="text-white-50 small d-none d-md-inline">&bull; Karya & Inspirasi Terpilih Murid</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                @if(auth()->user()?->role === 'murid')
                    <a href="{{ route('murid.pamflet.create') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-semibold" style="font-size: .78rem;">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Unggah Karyamu
                    </a>
                @elseif(auth()->user()?->role === 'admin' || auth()->user()?->role === 'guru_bk')
                    <a href="{{ route('panel.pamflet-kreatifitas.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-semibold" style="font-size: .78rem;">
                        <i class="bi bi-gear-wide-connected me-1"></i> Kelola Pamflet
                    </a>
                @endif
            </div>
        </div>

        <!-- Bootstrap Carousel -->
        <div id="studentPamphletCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
            <!-- Carousel Indicators -->
            @if($publishedPamphlets->count() > 1)
                <div class="carousel-indicators mb-2">
                    @foreach($publishedPamphlets as $idx => $p)
                        <button type="button" 
                                data-bs-target="#studentPamphletCarousel" 
                                data-bs-slide-to="{{ $idx }}" 
                                class="{{ $idx === 0 ? 'active' : '' }}" 
                                aria-current="{{ $idx === 0 ? 'true' : 'false' }}" 
                                aria-label="Karya {{ $idx + 1 }}"></button>
                    @endforeach
                </div>
            @endif

            <!-- Carousel Items -->
            <div class="carousel-inner">
                @foreach($publishedPamphlets as $idx => $pamphlet)
                    @php
                        $studentName = $pamphlet->student?->name ?? 'Murid Berbakat';
                        $className = $pamphlet->classroom?->name ?? $pamphlet->student?->current_classroom?->name ?? 'Siswa';
                    @endphp
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <div class="position-relative d-flex align-items-center justify-content-center" style="min-height: 340px; max-height: 440px; overflow: hidden; background: #0b0f19;">
                            <!-- Blurry Background Ambient -->
                            <img src="{{ $pamphlet->image_url }}" alt="" class="position-absolute w-100 h-100 object-fit-cover" style="filter: blur(28px) brightness(0.35); transform: scale(1.15);">

                            <!-- Crisp Centered Image -->
                            <div class="position-relative text-center p-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#carouselModal{{ $pamphlet->id }}" style="z-index: 2; max-height: 420px;">
                                <img src="{{ $pamphlet->image_url }}" 
                                     alt="{{ $pamphlet->title }}" 
                                     class="img-fluid rounded-3 shadow-lg" 
                                     style="max-height: 340px; max-width: 100%; object-fit: contain; border: 2px solid rgba(255,255,255,0.15); transition: transform .3s ease;">
                            </div>

                            <!-- Bottom Overlay Details (Murid & Kelas) -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 p-md-4 text-white" style="z-index: 3; background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.95) 85%);">
                                <div class="container-fluid p-0 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2">
                                    <div>
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="badge bg-teal rounded-pill px-3 py-1 fw-bold" style="background-color: #0d9488; font-size: .78rem;">
                                                <i class="bi bi-person-fill me-1"></i> Karya: {{ $studentName }}
                                            </span>
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold" style="font-size: .78rem;">
                                                <i class="bi bi-door-open-fill me-1"></i> Kelas: {{ $className }}
                                            </span>
                                        </div>
                                        <h5 class="fw-bold text-white mb-1 tracking-tight" style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">
                                            {{ $pamphlet->title }}
                                        </h5>
                                        @if($pamphlet->description)
                                            <p class="text-white-50 small mb-0 d-none d-md-block" style="max-width: 650px; font-size: .83rem;">
                                                {{ Str::limit($pamphlet->description, 130) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#carouselModal{{ $pamphlet->id }}">
                                            <i class="bi bi-arrows-fullscreen me-1"></i> Perbesar Karya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lightbox Modal untuk Slide Ini -->
                    <div class="modal fade" id="carouselModal{{ $pamphlet->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 rounded-4 overflow-hidden shadow">
                                <div class="modal-header border-0 bg-dark text-white p-3 px-4">
                                    <div>
                                        <h6 class="modal-title fw-bold mb-0 text-white">{{ $pamphlet->title }}</h6>
                                        <div class="small text-white-50 mt-1">
                                            Karya oleh: <strong class="text-warning">{{ $studentName }}</strong> &bull; Rombel: <strong class="text-info">{{ $className }}</strong>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0 text-center bg-black">
                                    <img src="{{ $pamphlet->image_url }}" alt="{{ $pamphlet->title }}" class="img-fluid" style="max-height: 82vh; object-fit: contain;">
                                </div>
                                @if($pamphlet->description)
                                    <div class="modal-footer border-0 bg-dark text-white text-start d-block p-3 px-4">
                                        <div class="fw-bold small text-warning mb-1"><i class="bi bi-info-circle me-1"></i> Pesan & Deskripsi Karya:</div>
                                        <p class="small text-white-50 mb-0 lh-base">{{ $pamphlet->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            @if($publishedPamphlets->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#studentPamphletCarousel" data-bs-slide="prev" style="width: 5%;">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8));"></span>
                    <span class="visually-hidden">Sebelumnya</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#studentPamphletCarousel" data-bs-slide="next" style="width: 5%;">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8));"></span>
                    <span class="visually-hidden">Selanjutnya</span>
                </button>
            @endif
        </div>
    </div>
@elseif(auth()->user()?->role === 'murid')
    <!-- Banner Ajakan Unggah untuk Siswa jika belum ada pamflet disetujui -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0d9488 0%, #134e4a 100%);">
        <div class="card-body p-4 text-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-white flex-shrink-0" style="width: 54px; height: 54px;">
                        <i class="bi bi-palette-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Pojok Kreativitas Murid: Tunjukkan Karyamu!</h6>
                        <p class="mb-0 text-white-50 small">Punya poster edukasi, motivasi belajar, atau pamflet karya seni? Unggah sekarang agar tayang di Dashboard utama madrasah.</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('murid.pamflet.create') }}" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Pamflet
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
