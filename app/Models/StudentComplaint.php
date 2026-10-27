<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentComplaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'student_id',
        'is_anonymous',
        'reporter_name',
        'urgency_level',
        'category',
        'incident_date',
        'incident_location',
        'description',
        'status',
        'safety_risk_flag',
        'handling_notes',
        'handled_by_counselor_id',
        'read_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'safety_risk_flag' => 'boolean',
        'incident_date' => 'date',
        'read_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'handled_by_counselor_id');
    }

    public function getUrgencyBadgeClassAttribute(): string
    {
        return match ($this->urgency_level) {
            'darurat' => 'bg-danger text-white fw-bold',
            'tinggi' => 'bg-warning-subtle text-danger-emphasis border-danger',
            'sedang' => 'bg-warning-subtle text-warning-emphasis',
            default => 'bg-light text-dark border',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'investigating' => 'Sedang Ditindaklanjuti',
            'resolved' => 'Selesai Ditangani',
            'closed' => 'Ditutup',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning-subtle text-warning border-warning',
            'investigating' => 'bg-info-subtle text-info border-info',
            'resolved' => 'bg-success-subtle text-success border-success',
            'closed' => 'bg-secondary-subtle text-secondary',
            default => 'bg-light text-dark',
        };
    }
}
