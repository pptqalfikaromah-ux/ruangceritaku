@extends('layouts.student')

@section('title', 'Asesmen: ' . $service->name)

@section('breadcrumb')
    <a href="{{ route('murid.dashboard') }}">Beranda</a>
    <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
    <a href="{{ route('murid.ims.index') }}">Asesmen IMS</a>
    <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
    <span class="active">{{ $service->name }}</span>
@endsection

@section('content')
<div class="space-y-4 pb-5">

    <!-- Header Banner Layanan & Statistik Cepat -->
    <div class="card border-0 rounded-4 bg-white p-3.5 p-md-4 shadow-sm mb-4" style="border: 1px solid #E2ECE9 !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill px-3 py-1.5 fs-xs fw-bold" style="background: #00B894; color: #ffffff;">
                        <i class="bi bi-compass-fill me-1"></i> {{ $service->name }}
                    </span>
                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xs fw-semibold">
                        Layanan 0{{ ($currentIndex ?? 0) + 1 }} dari {{ $allServices->count() }}
                    </span>
                    <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1 fs-xs">
                        Total 20 Butir Asesmen
                    </span>
                </div>
                <h3 class="fw-extrabold text-dark mb-1 tracking-tight" style="font-size: clamp(1.2rem, 2vw, 1.55rem);">
                    Pilih Butir Masalah yang Sedang Kamu Alami
                </h3>
                <p class="text-muted fs-xs mb-0" style="max-width: 720px; line-height: 1.6;">
                    Centang setiap butir masalah yang sesuai dengan kondisimu. Kamu juga dapat menandai <strong>1 butir masalah yang dirasa paling berat</strong> dalam layanan ini (sifatnya <em>opsional</em>).
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('murid.ims.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold">
                    <i class="bi bi-grid me-1"></i> Semua Layanan
                </a>
                @if($prevService)
                    <a href="{{ route('murid.ims.service', $prevService->id) }}" class="btn btn-light border btn-sm rounded-pill px-3 py-2 fw-semibold text-muted" title="{{ $prevService->name }}">
                        <i class="bi bi-chevron-left"></i> Sebelumnya
                    </a>
                @endif
                @if($nextService)
                    <a href="{{ route('murid.ims.service', $nextService->id) }}" class="btn btn-light border btn-sm rounded-pill px-3 py-2 fw-semibold text-muted" title="{{ $nextService->name }}">
                        Berikutnya <i class="bi bi-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Bar Petunjuk & Status Masalah Terberat -->
        <div class="mt-3.5 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2.5 bg-light-subtle p-2.5 rounded-3">
            <div class="d-flex align-items-center gap-2 fs-xs">
                <span class="badge bg-white border text-dark fw-bold px-2 py-1">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Petunjuk
                </span>
                <span class="text-muted">
                    Bisa memilih lebih dari 1 butir lintas bidang. Tanda bintang <span class="text-warning fw-bold">⭐</span> untuk 1 masalah terberat.
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-link text-decoration-none text-muted p-0 fs-xs" id="btnResetHeavy" style="display: {{ $heavyStatementId ? 'inline-block' : 'none' }};">
                    <i class="bi bi-x-circle me-1 text-danger"></i> Batalkan Masalah Terberat
                </button>
            </div>
        </div>
    </div>

    <!-- Form Instrumen Layanan -->
    <form method="POST" action="{{ route('murid.ims.saveServiceInstrument', $service->id) }}" id="serviceInstrumentForm">
        @csrf
        <input type="hidden" name="action" id="actionInput" value="save">
        @if($nextService)
            <input type="hidden" name="next_service_id" value="{{ $nextService->id }}">
        @endif

        <!-- Daftar 20 Butir Asesmen -->
        <div class="space-y-3 mb-5" id="statementListContainer">
            @foreach($statements as $stmt)
                @php
                    $ans = $existingAnswers->get($stmt->id);
                    $isSelected = $ans?->is_selected ?? false;
                    $isHeavy = ($heavyStatementId == $stmt->id);
                    $fieldCode = $stmt->guidanceField?->code ?? '';
                    $fieldName = $stmt->guidanceField?->name ?? 'Umum';

                    // Styling Badge Bidang yang Konsisten
                    $badgeStyle = match($fieldCode) {
                        'BID-01' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#bfdbfe', 'icon' => 'bi-person-fill'],
                        'BID-02' => ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#a7f3d0', 'icon' => 'bi-people-fill'],
                        'BID-03' => ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fde68a', 'icon' => 'bi-book-fill'],
                        'BID-04' => ['bg' => '#faf5ff', 'text' => '#7e22ce', 'border' => '#e9d5ff', 'icon' => 'bi-briefcase-fill'],
                        default  => ['bg' => '#f1f5f9', 'text' => '#475569', 'border' => '#cbd5e1', 'icon' => 'bi-tag-fill'],
                    };
                @endphp

                <input type="hidden" name="scope_statement_ids[]" value="{{ $stmt->id }}">

                <div class="card border-0 rounded-4 modern-statement-item p-3.5 p-sm-4 {{ $isSelected ? 'statement-active' : '' }} {{ $isHeavy ? 'statement-heavy' : '' }}"
                     id="card-stmt-{{ $stmt->id }}"
                     style="background: #ffffff; border: 1.5px solid {{ $isHeavy ? '#f59e0b' : ($isSelected ? '#00B894' : '#E2ECE9') }} !important;">
                    
                    <div class="d-flex align-items-start gap-3">
                        <!-- Checkbox Pilihan Butir -->
                        <div class="pt-1 flex-shrink-0">
                            <div class="form-check m-0 p-0">
                                <input class="form-check-input modern-checkbox cursor-pointer m-0"
                                       type="checkbox"
                                       name="selected[]"
                                       value="{{ $stmt->id }}"
                                       id="chk_{{ $stmt->id }}"
                                       data-card-id="card-stmt-{{ $stmt->id }}"
                                       data-radio-id="radio_heavy_{{ $stmt->id }}"
                                       {{ $isSelected ? 'checked' : '' }}
                                       {{ $assessment->isSubmitted() ? 'disabled' : '' }}
                                       style="width: 22px; height: 22px; cursor: pointer;">
                            </div>
                        </div>

                        <!-- Konten Butir: Nomor, Badge Bidang, Pernyataan Memanjang ke Samping -->
                        <div class="flex-grow-1 min-w-0">
                            <label for="chk_{{ $stmt->id }}" class="text-dark mb-0 fs-sm cursor-pointer lh-base d-block" style="user-select: none;">
                                <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 me-1.5 fs-xxs fw-bold font-monospace align-middle">#{{ $stmt->statement_number }}</span>
                                <span class="badge rounded-pill px-2.5 py-1 me-2 fs-xxs fw-semibold align-middle"
                                      style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['text'] }}; border: 1px solid {{ $badgeStyle['border'] }};">
                                    {{ $fieldName }}
                                </span>
                                <span class="statement-text fw-medium align-middle" style="line-height: 1.65;">{{ $stmt->statement_text }}</span>
                            </label>

                            <!-- Indikator Visual Masalah Paling Mengganggu -->
                            <div class="heavy-badge-label mt-1.5" id="heavyLabel_{{ $stmt->id }}" style="display: {{ $isHeavy ? 'inline-flex' : 'none' }};">
                                <span class="badge rounded-pill px-3 py-1 fs-xxs fw-bold text-white shadow-xs" style="background: #d97706;">
                                    <i class="bi bi-star-fill text-warning me-1"></i> Masalah Terberat di Layanan Ini
                                </span>
                            </div>

                            <!-- Pilihan Masalah Paling Mengganggu (Radio Option - Maksimal 1 per Layanan, Opsional) -->
                            @if(!$assessment->isSubmitted())
                                <div class="mt-2 pt-1.5 border-top border-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2 heavy-selector-box"
                                     id="heavyBox_{{ $stmt->id }}"
                                     style="{{ $isSelected ? '' : 'opacity: 0.6;' }}">
                                    <div class="form-check form-check-inline m-0 d-flex align-items-center gap-1.5">
                                        <input class="form-check-input heavy-radio cursor-pointer m-0"
                                               type="radio"
                                               name="heavy_statement_id"
                                               value="{{ $stmt->id }}"
                                               id="radio_heavy_{{ $stmt->id }}"
                                               data-stmt-id="{{ $stmt->id }}"
                                               data-chk-id="chk_{{ $stmt->id }}"
                                               {{ $isHeavy ? 'checked' : '' }}
                                               style="width: 15px; height: 15px; cursor: pointer;">
                                        <label class="form-check-label fs-xxs fw-semibold text-dark cursor-pointer" for="radio_heavy_{{ $stmt->id }}">
                                            <span class="text-warning fw-bold">⭐</span> 
                                            <span class="d-none d-sm-inline">Tandai Masalah Paling Mengganggu</span>
                                            <span class="d-sm-none">Masalah Terberat</span>
                                            <span class="badge bg-secondary-subtle text-secondary fs-xxs fw-normal ms-1 d-none d-md-inline">Opsional</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Floating Action Bar Modern (Mobile & Desktop Responsive) -->
        @if(!$assessment->isSubmitted())
            <div class="floating-action-bar rounded-4 shadow-lg p-3 d-flex flex-wrap justify-content-between align-items-center gap-2.5">
                <a href="{{ route('murid.ims.index') }}" class="btn btn-light border rounded-pill px-3.5 py-2 fs-xs fw-semibold text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Menu Layanan
                </a>

                <div class="d-flex align-items-center gap-2 fs-xs text-muted d-none d-md-flex">
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-bold">
                        <i class="bi bi-check2-circle text-success me-1"></i> <span id="selectedCountBadge">0</span> Butir Dipilih
                    </span>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-bold" id="heavyStatusBadge">
                        ⭐ Terberat: Belum Ada
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2 w-100-mobile justify-content-end">
                    <!-- 1. Simpan Pilihan di Layanan Ini -->
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3.5 py-2 fw-semibold" id="btnSaveOnly">
                        <i class="bi bi-floppy me-1"></i> Simpan Pilihan
                    </button>

                    <!-- 2. Simpan & Lanjut / Menu Layanan -->
                    @if($nextService)
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm" id="btnSaveAndNext">
                            <i class="bi bi-arrow-right-circle-fill me-1.5"></i> Simpan & Lanjut ke {{ str_replace('Layanan ', '', $nextService->name) }} &rarr;
                        </button>
                    @else
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm" id="btnSaveAndIndex">
                            <i class="bi bi-check-all me-1.5"></i> Simpan & Kembali ke Menu Layanan &rarr;
                        </button>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-4 bg-white rounded-4 border p-4 shadow-sm">
                <div class="text-success fs-1 mb-2">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="fw-bold text-dark">Asesmen IMS Kamu Sudah Terkirim</h5>
                <p class="text-muted fs-xs mb-3">Seluruh butir masalah telah disimpan dalam berkas pengolahan asesmen AUM.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('murid.ims.index') }}" class="btn btn-emerald rounded-pill px-4 py-2 fs-xs fw-bold text-white shadow-xs">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu Layanan
                    </a>
                </div>
            </div>
        @endif
    </form>
</div>
@endsection

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .modern-statement-item {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }

    .modern-statement-item:hover {
        border-color: #00B894 !important;
        background-color: #FAFCFB !important;
    }

    .modern-statement-item.statement-active {
        border-color: #00B894 !important;
        background-color: #F4FBF9 !important;
        box-shadow: 0 3px 12px rgba(0, 184, 148, 0.1) !important;
    }

    .modern-statement-item.statement-heavy {
        border-color: #f59e0b !important;
        background-color: #FFFDF5 !important;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.15) !important;
    }

    .modern-checkbox:checked {
        background-color: #00B894;
        border-color: #00B894;
    }

    .heavy-radio:checked {
        background-color: #f59e0b;
        border-color: #f59e0b;
    }

    .floating-action-bar {
        position: sticky;
        bottom: 1rem;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid #E2ECE9;
        z-index: 1010;
    }

    @media (max-width: 991.98px) {
        .floating-action-bar {
            bottom: 68px !important;
        }
    }

    @media (max-width: 576px) {
        .w-100-mobile {
            width: 100% !important;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .w-100-mobile button {
            width: 100%;
            padding: 10px !important;
            font-size: 0.85rem !important;
        }
        .modern-statement-item {
            padding: 10px 12px !important;
        }
        .statement-text {
            font-size: 0.84rem !important;
            line-height: 1.4 !important;
        }
    }
    .fs-xxs {
        font-size: 0.68rem !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceInstrumentForm');
        const actionInput = document.getElementById('actionInput');
        const btnSaveOnly = document.getElementById('btnSaveOnly');
        const btnSaveAndNext = document.getElementById('btnSaveAndNext');
        const btnSaveAndIndex = document.getElementById('btnSaveAndIndex');
        const btnResetHeavy = document.getElementById('btnResetHeavy');
        const selectedCountBadge = document.getElementById('selectedCountBadge');
        const heavyStatusBadge = document.getElementById('heavyStatusBadge');

        function updateCounters() {
            const checkedBoxes = document.querySelectorAll('.modern-checkbox:checked');
            const totalSelected = checkedBoxes.length;

            if (selectedCountBadge) {
                selectedCountBadge.textContent = totalSelected;
            }

            const checkedRadio = document.querySelector('.heavy-radio:checked');
            if (checkedRadio) {
                const stmtId = checkedRadio.value;
                const card = document.getElementById('card-stmt-' + stmtId);
                const numberBadge = card ? card.querySelector('.font-monospace') : null;
                const numText = numberBadge ? numberBadge.textContent.trim() : '#' + stmtId;
                if (heavyStatusBadge) {
                    heavyStatusBadge.innerHTML = '⭐ Terberat: <strong>' + numText + '</strong>';
                    heavyStatusBadge.classList.add('border-warning', 'text-warning-emphasis');
                }
                if (btnResetHeavy) btnResetHeavy.style.display = 'inline-block';
            } else {
                if (heavyStatusBadge) {
                    heavyStatusBadge.textContent = '⭐ Terberat: Belum Ada';
                    heavyStatusBadge.classList.remove('border-warning', 'text-warning-emphasis');
                }
                if (btnResetHeavy) btnResetHeavy.style.display = 'none';
            }
        }

        // Toggle visual active state & checkbox handler
        document.querySelectorAll('.modern-checkbox').forEach(chk => {
            chk.addEventListener('change', function() {
                const card = document.getElementById(this.dataset.cardId);
                const heavyRadio = document.getElementById(this.dataset.radioId);
                const heavyLabel = card ? card.querySelector('.heavy-badge-label') : null;
                const heavyBox = card ? card.querySelector('.heavy-selector-box') : null;

                if (card) {
                    if (this.checked) {
                        card.classList.add('statement-active');
                        if (heavyBox) heavyBox.style.opacity = '1';
                    } else {
                        card.classList.remove('statement-active');
                        // Jika butir ini sebelumnya dicentang sebagai terberat dan di-uncheck, uncheck juga radionya!
                        if (heavyRadio && heavyRadio.checked) {
                            heavyRadio.checked = false;
                            card.classList.remove('statement-heavy');
                            if (heavyLabel) heavyLabel.style.display = 'none';
                        }
                        if (heavyBox) heavyBox.style.opacity = '0.6';
                    }
                }
                updateCounters();
            });
        });

        // Heavy Radio Button Selection Handler (1 per service)
        document.querySelectorAll('.heavy-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const stmtId = this.dataset.stmtId;
                const chk = document.getElementById(this.dataset.chkId);

                // Jika radio ini dipilih, otomatis pastikan checkbox butir ini tercentang!
                if (chk && !chk.checked) {
                    chk.checked = true;
                    const card = document.getElementById('card-stmt-' + stmtId);
                    if (card) card.classList.add('statement-active');
                }

                // Bersihkan tampilan heavy dari card lainnya
                document.querySelectorAll('.modern-statement-item').forEach(c => {
                    c.classList.remove('statement-heavy');
                    const lbl = c.querySelector('.heavy-badge-label');
                    if (lbl) lbl.style.display = 'none';
                });

                // Aktifkan visual heavy pada card terpilih
                const selectedCard = document.getElementById('card-stmt-' + stmtId);
                if (selectedCard) {
                    selectedCard.classList.add('statement-heavy');
                    const lbl = selectedCard.querySelector('.heavy-badge-label');
                    if (lbl) lbl.style.display = 'inline-flex';
                }

                updateCounters();
            });
        });

        // Reset Heavy Selection Button
        if (btnResetHeavy) {
            btnResetHeavy.addEventListener('click', function() {
                document.querySelectorAll('.heavy-radio').forEach(r => r.checked = false);
                document.querySelectorAll('.modern-statement-item').forEach(c => {
                    c.classList.remove('statement-heavy');
                    const lbl = c.querySelector('.heavy-badge-label');
                    if (lbl) lbl.style.display = 'none';
                });
                updateCounters();
            });
        }

        // Action Buttons Submit
        if (btnSaveOnly) {
            btnSaveOnly.addEventListener('click', function() {
                actionInput.value = 'save';
                form.submit();
            });
        }

        if (btnSaveAndNext) {
            btnSaveAndNext.addEventListener('click', function() {
                actionInput.value = 'to_next';
                form.submit();
            });
        }

        if (btnSaveAndIndex) {
            btnSaveAndIndex.addEventListener('click', function() {
                actionInput.value = 'to_index';
                form.submit();
            });
        }

        // Inisialisasi awal
        updateCounters();
    });
</script>
@endpush
