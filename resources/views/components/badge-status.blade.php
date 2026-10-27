@props(['status'])

@php
    $statusKey = strtolower(trim((string)$status));

    $class = match($statusKey) {
        'aktif', '1', 'true', 'disetujui', 'approved', 'completed', 'selesai' 
            => 'bg-success-subtle text-success border border-success-subtle',
        'scheduled', 'terjadwal' 
            => 'bg-info-subtle text-info-emphasis border border-info-subtle',
        'pending', 'submitted', 'menunggu_persetujuan', 'menunggu_tindak_lanjut', 'baru' 
            => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
        'in_progress', 'diproses', 'needs_information' 
            => 'bg-primary-subtle text-primary border border-primary-subtle',
        'ditolak', 'cancelled', 'dibatalkan', 'drop_out' 
            => 'bg-danger-subtle text-danger border border-danger-subtle',
        'nonaktif', '0', 'false', 'arsip' 
            => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
        'lulus' 
            => 'bg-primary-subtle text-primary border border-primary-subtle',
        'pindah' 
            => 'bg-warning-subtle text-warning border border-warning-subtle',
        default 
            => 'bg-light text-dark border',
    };

    $label = match($statusKey) {
        'aktif', '1', 'true' => 'Aktif',
        'nonaktif', '0', 'false' => 'Nonaktif',
        'lulus' => 'Lulus',
        'pindah' => 'Pindah',
        'pending', 'submitted', 'menunggu_persetujuan' => 'Menunggu Persetujuan',
        'menunggu_tindak_lanjut' => 'Menunggu Tindak Lanjut',
        'scheduled', 'terjadwal' => 'Disetujui Rencana',
        'completed', 'selesai' => 'Sudah Tertangani',
        'in_progress', 'diproses' => 'Sedang Diproses',
        'needs_information' => 'Butuh Informasi',
        'ditolak' => 'Ditolak',
        'cancelled', 'dibatalkan' => 'Dibatalkan',
        default => ucwords(str_replace('_', ' ', $statusKey)),
    };
@endphp

<span class="badge rounded-pill px-2.5 py-1.5 fw-semibold {{ $class }}">
    {{ $label }}
</span>
