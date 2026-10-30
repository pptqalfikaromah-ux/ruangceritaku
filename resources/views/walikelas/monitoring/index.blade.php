@extends('layouts.walikelas')

@section('title', 'Monitoring Konseling Murid')
@section('page_title', 'Monitoring Konseling Murid')
@section('page_subtitle', $classroom ? 'Pemantauan Konseling Rombel ' . $classroom->name : '')

@section('content')
<div class="card card-app">
    <div class="card-header bg-white py-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-chat-heart-fill text-primary me-1.5"></i> Pemantauan Konseling Murid Kelas {{ $classroom?->name ?? '' }}
            </h6>
            <div class="text-muted fs-xs">Wali kelas dapat memantau siswa binaan yang mengajukan atau sedang menjalani bimbingan konseling bersama Guru BK.</div>
        </div>

        <form method="GET" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Peninjauan</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                <option value="in_counseling" {{ request('status') === 'in_counseling' ? 'selected' : '' }}>Sedang Konseling</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / tiket..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-warning text-dark fw-bold px-3">Cari</button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        {{-- DESKTOP TABLE VIEW --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light fs-xs text-muted">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>No. Tiket</th>
                        <th>Nama Murid</th>
                        <th>Kebutuhan / Masalah</th>
                        <th>Konselor BK PIC</th>
                        <th>Jadwal Sesi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 90px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($counselings as $index => $counseling)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $counselings->firstItem() + $index }}</td>
                            <td class="font-monospace small fw-bold text-primary">{{ $counseling->ticket_number }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $counseling->student?->name }}</div>
                                <div class="text-muted fs-xs">NISN: {{ $counseling->student?->nisn }}</div>
                            </td>
                            <td>
                                <div class="text-dark small fw-semibold">{{ Str::limit($counseling->purpose, 45) }}</div>
                                <div class="text-muted fs-xxs">{{ $counseling->service?->name ?? 'Layanan Mandiri' }}</div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $counseling->assignedCounselor?->name ?? 'Belum Ditentukan' }}</div>
                            </td>
                            <td class="small text-muted">
                                @if($counseling->schedule)
                                    <div><i class="bi bi-calendar3 me-1"></i>{{ $counseling->schedule->counseling_date?->format('d M Y') }}</div>
                                    <div class="fs-xxs"><i class="bi bi-clock me-1"></i>{{ $counseling->schedule->start_time }} - {{ $counseling->schedule->end_time }}</div>
                                @else
                                    <span class="text-muted fs-xs">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-teal-subtle text-mint px-2.5 py-1">
                                    {{ ucfirst(str_replace('_', ' ', $counseling->status)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('walikelas.monitoring.show', $counseling) }}" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 fw-bold text-muted">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-text fs-2 d-block mb-2 text-muted opacity-50"></i>
                                Belum ada catatan aktivitas konseling dari murid rombel binaan Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARDS VIEW (Spacious & Clean Mobile UI) --}}
        <div class="d-md-none p-2 p-sm-3">
            @forelse($counselings as $counseling)
                <div class="mobile-card mb-3 shadow-xs">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                        <span class="font-monospace fw-bold text-primary fs-xs">
                            <i class="bi bi-ticket-perforated me-1"></i>#{{ $counseling->ticket_number }}
                        </span>
                        <span class="badge rounded-pill bg-teal-subtle text-mint px-2.5 py-1 fs-xs fw-bold">
                            {{ ucfirst(str_replace('_', ' ', $counseling->status)) }}
                        </span>
                    </div>

                    <div class="card-name mb-1">{{ $counseling->student?->name }}</div>
                    
                    <div class="card-sub mb-2.5">
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <span class="text-muted fs-xs">NISN: {{ $counseling->student?->nisn }}</span>
                            <span class="text-muted fs-xs">&bull;</span>
                            <span class="text-dark fw-semibold fs-xs">{{ $counseling->service?->name ?? 'Layanan Mandiri' }}</span>
                        </div>
                    </div>

                    <div class="card-info-box mb-2.5 fs-xs">
                        <div class="fw-bold text-muted fs-xxs text-uppercase mb-1">Kebutuhan / Masalah:</div>
                        <div class="text-dark">{{ Str::limit($counseling->purpose, 110) }}</div>
                    </div>

                    <div class="d-flex flex-column gap-1.5 fs-xs text-muted mb-2.5 p-2.5 rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-1.5">
                            <i class="bi bi-person-badge text-primary"></i>
                            <span>Guru BK PIC: <strong class="text-dark">{{ $counseling->assignedCounselor?->name ?? 'Belum Ditentukan' }}</strong></span>
                        </div>
                        @if($counseling->schedule)
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-calendar-event text-warning"></i>
                                <span>Jadwal: <strong class="text-dark">{{ $counseling->schedule->counseling_date?->format('d M Y') }}</strong> ({{ $counseling->schedule->start_time }} - {{ $counseling->schedule->end_time }})</span>
                            </div>
                        @else
                            <div class="text-muted fs-xxs fst-italic">Jadwal sesi belum ditetapkan konselor</div>
                        @endif
                    </div>

                    <div class="pt-2 border-top">
                        <a href="{{ route('walikelas.monitoring.show', $counseling) }}" class="btn btn-card-action w-100 text-center">
                            <i class="bi bi-eye me-1 text-primary"></i> Rincian Konseling
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-square-text fs-1 d-block mb-2 text-muted opacity-50"></i>
                    Belum ada catatan aktivitas konseling dari murid rombel binaan Anda.
                </div>
            @endforelse
        </div>
    </div>

    @if($counselings->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $counselings->links() }}
        </div>
    @endif
</div>
@endsection
