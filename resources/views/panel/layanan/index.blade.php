@extends('layouts.panel')

@section('title', 'Master Layanan BK')

@section('content')
<x-page-header title="Master Layanan Bimbingan & Konseling"
               subtitle="Standar 9 Layanan, 6 Kegiatan Pendukung, 4 Bidang Bimbingan, dan 4 Fungsi Layanan Konseling.">
</x-page-header>

<div class="card card-app">
    <div class="card-header bg-white border-bottom p-0">
        <ul class="nav nav-tabs card-header-tabs mx-3 mt-2" id="layananTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="tab-9-layanan" data-bs-toggle="tab" data-bs-target="#content-9-layanan" type="button" role="tab">
                    <i class="bi bi-grid-fill me-1 text-primary-teal"></i> 9 Layanan BK
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-6-pendukung" data-bs-toggle="tab" data-bs-target="#content-6-pendukung" type="button" role="tab">
                    <i class="bi bi-tools me-1 text-primary-teal"></i> 6 Kegiatan Pendukung
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-4-bidang" data-bs-toggle="tab" data-bs-target="#content-4-bidang" type="button" role="tab">
                    <i class="bi bi-compass me-1 text-primary-teal"></i> 4 Bidang Bimbingan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-4-fungsi" data-bs-toggle="tab" data-bs-target="#content-4-fungsi" type="button" role="tab">
                    <i class="bi bi-shield-check me-1 text-primary-teal"></i> 4 Fungsi Layanan
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="layananTabsContent">
            <!-- Tab 1: 9 Layanan BK -->
            <div class="tab-pane fade show active" id="content-9-layanan" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-800 mb-0">9 Layanan Konseling Standar</h6>
                    <span class="badge bg-teal-subtle text-primary-teal">9 Layanan Aktif</span>
                </div>
                <div class="row g-3">
                    @foreach($services as $index => $srv)
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded-3 bg-light border h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary-teal rounded-circle p-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h6 class="fw-bold mb-0 text-slate-800">{{ $srv->name }}</h6>
                                </div>
                                <p class="text-muted small mb-0">{{ $srv->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 2: 6 Kegiatan Pendukung -->
            <div class="tab-pane fade" id="content-6-pendukung" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-800 mb-0">6 Kegiatan Pendukung BK</h6>
                    <span class="badge bg-secondary-subtle text-secondary">6 Kegiatan Aktif</span>
                </div>
                <div class="row g-3">
                    @foreach($supportings as $index => $spt)
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded-3 bg-light border h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-secondary rounded-circle p-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h6 class="fw-bold mb-0 text-slate-800">{{ $spt->name }}</h6>
                                </div>
                                <p class="text-muted small mb-0">{{ $spt->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 3: 4 Bidang BK -->
            <div class="tab-pane fade" id="content-4-bidang" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-800 mb-0">4 Bidang Bimbingan & Konseling</h6>
                    <span class="badge bg-success-subtle text-success">4 Bidang Utama</span>
                </div>
                <div class="row g-3">
                    @foreach($fields as $index => $fld)
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-success rounded-circle p-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h6 class="fw-bold mb-0 text-slate-800">Bidang {{ $fld->name }}</h6>
                                </div>
                                <p class="text-muted small mb-0">{{ $fld->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab 4: 4 Fungsi Layanan -->
            <div class="tab-pane fade" id="content-4-fungsi" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-slate-800 mb-0">4 Fungsi Utama Layanan Konseling</h6>
                    <span class="badge bg-warning-subtle text-warning">4 Fungsi</span>
                </div>
                <div class="row g-3">
                    @foreach($functions as $index => $fnc)
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-warning text-dark rounded-circle p-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                    <h6 class="fw-bold mb-0 text-slate-800">Fungsi {{ $fnc->name }}</h6>
                                </div>
                                <p class="text-muted small mb-0">{{ $fnc->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
