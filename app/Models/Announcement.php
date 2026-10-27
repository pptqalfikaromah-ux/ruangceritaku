<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_user_id',
        'title',
        'content',
        'target_role',
        'category',
        'is_active',
        'is_pinned',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    /**
     * Scope untuk pengumuman aktif dan tersortir (pinned pertama, lalu latest)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderByDesc('is_pinned')
            ->latest();
    }

    /**
     * Scope untuk role pengguna
     */
    public function scopeForRole(Builder $query, ?string $role): Builder
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target_role', 'all');
            if ($role) {
                $q->orWhere('target_role', $role);
            }
        });
    }

    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'penting' => 'bg-danger text-white',
            'agenda' => 'bg-warning text-dark',
            'kegiatan' => 'bg-success text-white',
            default => 'bg-info text-white',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'penting' => 'Penting & Mendesak',
            'agenda' => 'Agenda & Jadwal',
            'kegiatan' => 'Kegiatan Sekolah',
            default => 'Informasi Umum',
        };
    }
}
