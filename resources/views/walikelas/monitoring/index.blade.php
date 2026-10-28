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

        {{-- MOBILE CARDS VIEW --}}
        <div class="d-md-none p-3">
            @forelse($counselings as $counseling)
                <div class="card border rounded-3 p-3 mb-2.5 shadow-xs bg-white">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2">
                        <span class="font-monospace fw-bold text-primary fs-xs">#{{ $counseling->ticket_number }}</span>
                        <span class="badge rounded-pill bg-teal-subtle text-mint px-2.5 py-1 fs-xxs">
                            {{ ucfirst(str_replace('_', ' ', $counseling->status)) }}
                        </span>
                    </div>

                    <div class="fw-bold text-dark fs-sm mb-0.5">{{ $counseling->student?->name }}</div>
                    <div class="text-muted fs-xxs mb-2">NISN: {{ $counseling->student?->nisn }} &bull; {{ $counseling->service?->name ?? 'Layanan Mandiri' }}</div>

                    <div class="small text-muted bg-light p-2 rounded-2 mb-2 fs-xs">
                        {{ Str::limit($counseling->purpose, 80) }}
                    </div>

                    <div class="d-flex flex-column gap-1 fs-xxs text-muted mb-2.5">
                        <div>
                            <i class="bi bi-person-badge text-primary me-1"></i>Konselor: <strong>{{ $counseling->assignedCounselor?->name ?? 'Belum Ditentukan' }}</strong>
                        </div>
                        @if($counseling->schedule)
                            <div>
                                <i class="bi bi-calendar-event text-warning me-1"></i>Jadwal: {{ $counseling->schedule->counseling_date?->format('d M Y') }} ({{ $counseling->schedule->start_time }} - {{ $counseling->schedule->end_time }})
                            </div>
                        @endif
                    </div>

                    <div class="pt-2 border-top">
                        <a href="{{ route('walikelas.monitoring.show', $counseling) }}" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-bold fs-xs">
                            <i class="bi bi-eye me-1"></i> Rincian Konseling
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
