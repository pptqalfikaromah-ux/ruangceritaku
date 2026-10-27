@props(['title', 'value', 'icon', 'bgClass' => 'bg-teal-subtle text-primary-teal', 'subtext' => null])

<div class="card card-stat">
    <div class="card-stat-icon {{ $bgClass }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="flex-grow-1 overflow-hidden">
        <div class="text-muted small fw-semibold text-truncate">{{ $title }}</div>
        <div class="fs-4 fw-bold text-slate-800">{{ $value }}</div>
        @if($subtext)
            <div class="text-muted smaller text-truncate mt-1">{{ $subtext }}</div>
        @endif
    </div>
</div>
