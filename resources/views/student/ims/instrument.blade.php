@extends('layouts.student')

@section('title', 'Instrumen Masalah: ' . $service->name . ' - Bidang ' . $field->name)

@section('breadcrumb')
    <a href="{{ route('murid.ims.index') }}">IMS</a>
    <span>/</span>
    <a href="{{ route('murid.ims.service', $service->id) }}">{{ $service->name }}</a>
    <span>/</span>
    <span class="active">Bidang {{ $field->name }}</span>
@endsection

@section('content')
<div class="space-y-4 pb-5">
    <!-- Step Navigation Indicator (Mobile Friendly) -->
    <div class="card border-0 rounded-4 bg-white p-3 shadow-xs mb-3" style="border: 1px solid #E2ECE9 !important;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2.5">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('murid.ims.service', $service->id) }}" class="btn btn-sm btn-light rounded-pill px-3 py-1.5 fs-xs fw-semibold border">
                    <i class="bi bi-arrow-left me-1"></i> Ganti Bidang
                </a>
                <span class="text-muted fs-xs d-none d-sm-inline">|</span>
                <span class="fs-xs text-muted fw-semibold d-none d-sm-inline">{{ $service->name }}</span>
            </div>

            <div class="d-flex align-items-center gap-1.5 fs-xs flex-wrap">
                <a href="{{ route('murid.ims.index') }}" class="badge text-decoration-none rounded-pill px-2.5 py-1" style="background: #E6FAF5; color: #008F72;">
                    <i class="bi bi-check-circle-fill me-1"></i>1. Layanan
                </a>
                <i class="bi bi-chevron-right text-muted fs-xxs"></i>
                <a href="{{ route('murid.ims.service', $service->id) }}" class="badge text-decoration-none rounded-pill px-2.5 py-1" style="background: #E6FAF5; color: #008F72;">
                    <i class="bi bi-check-circle-fill me-1"></i>2. Bidang
                </a>
                <i class="bi bi-chevron-right text-muted fs-xxs"></i>
                <span class="badge rounded-pill px-3 py-1 fw-bold shadow-xs" style="background: #00B894; color: #ffffff;">
                    3. Isi Lembar & Catatan
                </span>
            </div>
        </div>
    </div>

    <!-- Banner Header Bagian Ini -->
    <div class="card border-0 rounded-4 bg-white p-3.5 p-md-4 shadow-sm mb-4" style="border: 1px solid #E2ECE9 !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill px-3 py-1 fs-xs fw-semibold" style="background: #E6FAF5; color: #008F72; border: 1px solid #A7F3D0;">
                        <i class="bi bi-bookmark-fill me-1"></i>{{ $service->name }}
                    </span>
                    <span class="badge rounded-pill px-3 py-1 fs-xs fw-bold" style="background: #FFF8E6; color: #B45309; border: 1px solid #FDE68A;">
                        Bidang {{ $field->name }}
                    </span>
                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 fs-xs">
                        5 Butir Standar
                    </span>
                    @if($customStatements->count() > 0)
                        <span class="badge rounded-pill px-2.5 py-1 fs-xs fw-semibold" style="background: #ede9fe; color: #6d28d9;">
                            +{{ $customStatements->count() }} Masalah Kustom
                        </span>
                    @endif
                </div>
                <h3 class="fw-extrabold text-dark mb-1 tracking-tight" style="font-size: clamp(1.2rem, 2vw, 1.55rem);">
                    Tandai Masalah yang Sedang Kamu Alami
                </h3>
                <p class="text-muted fs-xs mb-0" style="max-width: 680px; line-height: 1.6;">
                    Centang satu atau lebih butir masalah di bawah ini. Kamu juga dapat menuliskan <strong>catatan khusus pada setiap butir</strong>, atau <strong>menambahkan masalahmu sendiri</strong> jika tidak tercantum dalam pilihan standar.
                </p>
            </div>
            <div>
                <a href="{{ route('murid.ims.service', $service->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold text-nowrap">
                    <i class="bi bi-arrow-left me-1"></i> Bidang Lain
                </a>
            </div>
        </div>
    </div>

    <!-- Form Butir Instrumen & Catatan -->
    <form method="POST" action="{{ route('murid.ims.saveInstrument', [$service->id, $field->id]) }}" id="instrumentForm">
        @csrf
        <input type="hidden" name="action" id="actionInput" value="save">
        <input type="hidden" name="delete_custom_id" id="deleteCustomId" value="">

        <!-- SECTION 1: DAFTAR 5 BUTIR PERNYATAAN STANDAR -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0 fs-sm text-uppercase tracking-wider">
                    <i class="bi bi-check2-square text-mint me-1"></i> Butir Masalah Standar (5 Butir)
                </h5>
                <span class="text-muted fs-xs fst-italic">Bisa centang lebih dari 1</span>
            </div>

            <div class="space-y-3">
                @foreach($statements as $stmt)
                    @php
                        $ans = $existingAnswers->get($stmt->id);
                        $isSelected = $ans?->is_selected ?? false;
                        $itemNote = $ans?->student_note ?? '';
                    @endphp
                    <input type="hidden" name="scope_statement_ids[]" value="{{ $stmt->id }}">

                    <div class="card border-0 rounded-4 bg-white p-3 p-md-3.5 modern-statement-item {{ $isSelected ? 'statement-active' : '' }}" 
                         id="card_stmt_{{ $stmt->id }}"
                         style="border: 1.5px solid #E2ECE9 !important;">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Custom Styled Checkbox -->
                            <div class="form-check mt-1">
                                <input class="form-check-input modern-checkbox fs-5 cursor-pointer" 
                                       type="checkbox" 
                                       name="selected[]" 
                                       value="{{ $stmt->id }}" 
                                       id="stmt_{{ $stmt->id }}"
                                       data-card-id="card_stmt_{{ $stmt->id }}"
                                       data-note-box="note_box_{{ $stmt->id }}"
                                       {{ $isSelected ? 'checked' : '' }}
                                       {{ $assessment->isSubmitted() ? 'disabled' : '' }}>
                            </div>

                            <div class="flex-grow-1">
                                <label class="form-check-label d-block cursor-pointer mb-2" for="stmt_{{ $stmt->id }}">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-light text-muted border font-monospace fw-bold fs-xs rounded-pill px-2.5 py-0.5">
                                            #Butir {{ $stmt->statement_number }}
                                        </span>
                                        <span class="badge rounded-pill px-2 py-0.5 fs-xs" style="background: #E6FAF5; color: #008F72;">
                                            Bidang {{ $field->name }}
                                        </span>
                                    </div>
                                    <div class="fw-bold text-dark fs-sm lh-base">
                                        {{ $stmt->statement_text }}
                                    </div>
                                </label>

                                <!-- Catatan Khusus untuk Butir Ini -->
                                <div class="statement-note-box mt-2" id="note_box_{{ $stmt->id }}" style="{{ $isSelected || !empty($itemNote) ? '' : 'display: none;' }}">
                                    <div class="p-2.5 rounded-3" style="background: #F4FBF9; border: 1px dashed #A7F3D0;">
                                        <label class="form-label fs-xxs fw-bold text-uppercase text-muted mb-1 d-flex align-items-center gap-1">
                                            <i class="bi bi-chat-left-text text-mint"></i> Catatan Khusus untuk Butir Ini:
                                        </label>
                                        <textarea name="notes[{{ $stmt->id }}]" 
                                                  rows="2" 
                                                  class="form-control form-control-sm border-0 rounded-2 bg-white" 
                                                  placeholder="Jelaskan kondisi atau contoh kejadian yang kamu alami pada masalah ini..."
                                                  {{ $assessment->isSubmitted() ? 'readonly' : '' }}>{{ $itemNote }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SECTION 2: BUTIR MASALAH KUSTOM / BUATAN SENDIRI -->
        <div class="card border-0 rounded-4 bg-white p-3.5 p-md-4 shadow-sm mb-4" style="border: 1.5px solid #E2ECE9 !important;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-sm d-flex align-items-center gap-1.5">
                        <i class="bi bi-plus-circle-fill text-mint"></i>
                        Masalah Tambahan / Buatan Sendiri (Kustom)
                    </h5>
                    <span class="text-muted fs-xs">Jika masalah yang kamu hadapi tidak ada pada 5 pilihan di atas, tuliskan di sini</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fs-xs fw-bold" onclick="toggleCustomForm()">
                    <i class="bi bi-pencil-plus me-1"></i> + Tulis Masalah Baru
                </button>
            </div>

            <!-- Daftar Butir Kustom yang Sudah Pernah Ditambahkan -->
            @if($customStatements->isNotEmpty())
                <div class="space-y-3 mb-3">
                    @foreach($customStatements as $cStmt)
                        <div class="p-3 rounded-4" style="background: #F8FAFC; border: 1.5px solid #CBD5E1;">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div class="form-check mt-1">
                                    <input class="form-check-input fs-5 cursor-pointer" 
                                           type="checkbox" 
                                           name="selected_custom[]" 
                                           value="{{ $cStmt->id }}" 
                                           id="custom_{{ $cStmt->id }}"
                                           {{ $cStmt->is_selected ? 'checked' : '' }}
                                           {{ $assessment->isSubmitted() ? 'disabled' : '' }}>
                                </div>
                                <div class="flex-grow-1">
                                    <label class="form-check-label d-block cursor-pointer" for="custom_{{ $cStmt->id }}">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge rounded-pill px-2.5 py-0.5 fs-xs fw-bold" style="background: #ede9fe; color: #6d28d9;">
                                                <i class="bi bi-stars me-1"></i>Masalah Kustom Saya
                                            </span>
                                            <span class="badge rounded-pill px-2 py-0.5 fs-xs" style="background: #E6FAF5; color: #008F72;">
                                                Bidang {{ $field->name }}
                                            </span>
                                        </div>
                                        <div class="fw-bold text-dark fs-sm lh-base">
                                            {{ $cStmt->statement_text }}
                                        </div>
                                    </label>

                                    <!-- Catatan Butir Kustom -->
                                    <div class="mt-2">
                                        <textarea name="custom_notes[{{ $cStmt->id }}]" 
                                                  rows="2" 
                                                  class="form-control form-control-sm bg-white rounded-3" 
                                                  placeholder="Catatan tambahan untuk masalah ini..."
                                                  {{ $assessment->isSubmitted() ? 'readonly' : '' }}>{{ $cStmt->student_note }}</textarea>
                                    </div>
                                </div>

                                @if(!$assessment->isSubmitted())
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-1.5" 
                                                title="Hapus butir ini"
                                                onclick="deleteCustomItem({{ $cStmt->id }})">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Form Input Butir Kustom Baru (Collapsible) -->
            <div id="newCustomCard" class="{{ $customStatements->isEmpty() ? '' : 'd-none' }} p-3.5 rounded-4 mt-2" 
                 style="background: #F4FBF9; border: 1.5px dashed #00B894;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-lightbulb-fill text-warning fs-5"></i>
                    <span class="fw-bold text-dark fs-sm">Form Tambah Butir Masalah Sendiri:</span>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fs-xs fw-bold text-slate-700">Pernyataan Masalah yang Kamu Hadapi:</label>
                    <input type="text" 
                           name="new_custom_text" 
                           id="new_custom_text" 
                           class="form-control rounded-3 p-2.5" 
                           placeholder="Contoh: Saya merasa cemas dan gemetar setiap kali guru memanggil nama saya untuk menjawab soal...">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-xs fw-bold text-slate-700">Penjelasan / Catatan Tambahan (Opsional):</label>
                    <textarea name="new_custom_note" 
                              rows="2" 
                              class="form-control rounded-3 p-2.5" 
                              placeholder="Tuliskan sejak kapan masalah ini muncul atau hal lain yang membuatmu tidak nyaman..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" onclick="toggleCustomForm()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-mint rounded-pill px-3.5 fw-bold shadow-xs">
                        <i class="bi bi-plus-lg me-1"></i> Simpan Masalah Baru
                    </button>
                </div>
            </div>
        </div>

        <!-- SECTION 3: KOLOM CATATAN UMUM / CURHAT SISWA -->
        <div class="card border-0 rounded-4 bg-white p-3.5 p-md-4 shadow-sm mb-5" style="border: 1px solid #E2ECE9 !important;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #E6FAF5; color: #00B894;">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0 fs-sm">Catatan Siswa untuk Masalah di Atas</h5>
                    <span class="text-muted fs-xs">Ceritakan secara leluasa apa yang kamu rasakan kepada Guru BK</span>
                </div>
            </div>

            <p class="text-muted fs-xs mb-3 lh-base">
                Apakah ada cerita atau kondisi tertentu yang ingin kamu sampaikan? Kamu dapat menuliskan kendala, harapan bantuan, atau hal-hal yang ingin kamu ceritakan saat konseling:
            </p>

            <textarea name="wide_note" 
                      id="wide_note" 
                      rows="4" 
                      class="form-control rounded-3 p-3 modern-textarea" 
                      placeholder="Contoh: Saya merasa kesulitan membagi waktu antara belajar dan tugas rumah tangga, sering begadang sehingga di kelas mengantuk. Saya ingin mendapat bimbingan cara mengatur jadwal harian..."
                      {{ $assessment->isSubmitted() ? 'readonly' : '' }}>{{ $wideNote }}</textarea>
            
            <div class="d-flex justify-content-between align-items-center mt-2 text-muted fs-xs">
                <span><i class="bi bi-shield-check text-mint me-1"></i>Kerahasiaan catatanmu 100% aman terjamin bersama Guru BK.</span>
            </div>
        </div>

        <!-- FLOATING ACTION BAR MODERN (MOBILE FRIENDLY & TOUCH OPTIMIZED) -->
        @if(!$assessment->isSubmitted())
            <div class="floating-action-bar rounded-4 shadow-lg p-3 d-flex flex-wrap justify-content-between align-items-center gap-2.5">
                <a href="{{ route('murid.ims.service', $service->id) }}" class="btn btn-light border rounded-pill px-3.5 py-2 fs-xs fw-semibold text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Bidang
                </a>

                <div class="d-flex flex-wrap gap-2 w-100-mobile justify-content-end">
                    <!-- 1. Simpan Jawaban & Catatan -->
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3.5 py-2 fw-semibold" id="btnSaveDraft">
                        <i class="bi bi-floppy me-1"></i> Simpan Pilihan
                    </button>

                    <!-- 2. Lanjut ke Pengajuan Konseling IMS -->
                    <button type="button" class="btn btn-gold btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm" id="btnRequestCounseling">
                        <i class="bi bi-send-check-fill me-1.5"></i> Simpan & Lanjut ke Konseling &rarr;
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <a href="{{ route('murid.ims.counseling.create') }}" class="btn btn-gold rounded-pill px-4 py-2.5 fw-bold shadow-sm">
                    <i class="bi bi-calendar-check-fill me-1.5"></i> Ajukan Konseling dari Lembar Ini &rarr;
                </a>
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
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .modern-statement-item:hover {
        border-color: #00B894 !important;
        background-color: #FAFCFB !important;
    }

    .modern-statement-item.statement-active {
        border-color: #00B894 !important;
        background-color: #F4FBF9 !important;
        box-shadow: 0 4px 14px rgba(0, 184, 148, 0.12) !important;
    }

    .modern-checkbox:checked {
        background-color: #00B894;
        border-color: #00B894;
    }

    .modern-textarea {
        font-size: 0.9rem;
        line-height: 1.6;
        border-color: #E2ECE9;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-textarea:focus {
        border-color: #00B894;
        box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.15);
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
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleCustomForm() {
        const card = document.getElementById('newCustomCard');
        if (card) {
            card.classList.toggle('d-none');
            if (!card.classList.contains('d-none')) {
                document.getElementById('new_custom_text').focus();
            }
        }
    }

    function deleteCustomItem(id) {
        if (confirm('Apakah kamu yakin ingin menghapus butir masalah kustom ini?')) {
            document.getElementById('deleteCustomId').value = id;
            document.getElementById('instrumentForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('instrumentForm');
        const actionInput = document.getElementById('actionInput');
        const btnSaveDraft = document.getElementById('btnSaveDraft');
        const btnRequestCounseling = document.getElementById('btnRequestCounseling');

        // Toggle card visual active state and note box on checkbox change
        document.querySelectorAll('.modern-checkbox').forEach(chk => {
            chk.addEventListener('change', function() {
                const card = document.getElementById(this.dataset.cardId);
                const noteBox = document.getElementById(this.dataset.noteBox);
                if (card) {
                    if (this.checked) {
                        card.classList.add('statement-active');
                        if (noteBox) noteBox.style.display = 'block';
                    } else {
                        card.classList.remove('statement-active');
                        if (noteBox) {
                            // If note is empty, hide note box
                            const txt = noteBox.querySelector('textarea');
                            if (!txt || txt.value.trim() === '') {
                                noteBox.style.display = 'none';
                            }
                        }
                    }
                }
            });
        });

        if (btnSaveDraft) {
            btnSaveDraft.addEventListener('click', function() {
                actionInput.value = 'save';
                form.submit();
            });
        }

        if (btnRequestCounseling) {
            btnRequestCounseling.addEventListener('click', function() {
                actionInput.value = 'counseling';
                form.submit();
            });
        }
    });
</script>
@endpush
