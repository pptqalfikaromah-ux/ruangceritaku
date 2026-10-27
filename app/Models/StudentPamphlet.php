<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPamphlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'title',
        'description',
        'image_path',
        'status',
        'rejection_reason',
        'approved_by_user_id',
        'approved_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Scope pamflet yang disetujui dan aktif untuk tayang di slider/carousel
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'disetujui')
            ->where('is_active', true)
            ->latest('approved_at');
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (config('filesystems.default') === 's3' || !file_exists(public_path($this->image_path))) {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->image_path);
        }

        return asset($this->image_path);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'disetujui' => 'Disetujui / Tayang di Dashboard',
            'ditolak' => 'Ditolak',
            default => 'Menunggu Moderasi Admin',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'disetujui' => 'bg-success-subtle text-success border border-success',
            'ditolak' => 'bg-danger-subtle text-danger border border-danger',
            default => 'bg-warning-subtle text-warning border border-warning',
        };
    }
}
