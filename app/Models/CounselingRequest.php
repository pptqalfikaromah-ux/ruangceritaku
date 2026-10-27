<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounselingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'source',
        'student_id',
        'ims_assessment_id',
        'purpose',
        'counseling_method',
        'guidance_field_id',
        'service_type_id',
        'is_service_unknown',
        'description',
        'additional_notes',
        'preferred_schedule',
        'status',
        'assigned_counselor_id',
        'status_notes',
        'resolution_notes',
        'completed_at',
        'read_at',
    ];

    protected $casts = [
        'is_service_unknown' => 'boolean',
        'completed_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function imsAssessment(): BelongsTo
    {
        return $this->belongsTo(ImsAssessment::class);
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function assignedCounselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'assigned_counselor_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CounselingRequestHistory::class)->latest();
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(CounselingSchedule::class);
    }

    public function isFromIms(): bool
    {
        return $this->source === 'ims';
    }

    public function isPersonal(): bool
    {
        return $this->source === 'pribadi' || empty($this->source);
    }

    public function getSourceLabelAttribute(): string
    {
        return $this->isFromIms() ? 'Tindak Lanjut IMS' : 'Konseling Pribadi';
    }

    public function getSourceBadgeClassAttribute(): string
    {
        return $this->isFromIms()
            ? 'bg-purple-subtle text-purple border-purple'
            : 'bg-primary-subtle text-primary border-primary';
    }

    public function isOnlineMeet(): bool
    {
        return $this->counseling_method === 'online_meet';
    }

    public function isTatapMuka(): bool
    {
        return $this->counseling_method !== 'online_meet';
    }

    public function getMethodLabelAttribute(): string
    {
        return $this->isOnlineMeet() ? 'Online (Google Meet)' : 'Tatap Muka di Ruang BK';
    }

    public function getMethodBadgeClassAttribute(): string
    {
        return $this->isOnlineMeet()
            ? 'bg-info-subtle text-info border border-info'
            : 'bg-emerald-subtle text-emerald border border-emerald';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Peninjauan',
            'needs_information' => 'Perlu Kelengkapan Info',
            'approved' => 'Disetujui',
            'awaiting_student_confirmation' => 'Menunggu Konfirmasi Jadwal Murid',
            'scheduled' => 'Disetujui Rencana (Terjadwal)',
            'completed' => 'Sudah Tertangani / Selesai',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'reschedule_requested' => 'Permintaan Jadwal Ulang',
            default => ucfirst($this->status),
        };
    }

    public function getStudentStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Peninjauan',
            'needs_information' => 'Perlu Kelengkapan Info',
            'approved', 'scheduled' => 'Disetujui',
            'awaiting_student_confirmation' => 'Konfirmasi Jadwal Diperlukan',
            'completed' => 'Selesai / Sudah Tertangani',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'reschedule_requested' => 'Jadwal Ulang Diajukan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning-subtle text-warning border-warning',
            'needs_information' => 'bg-info-subtle text-info border-info',
            'approved' => 'bg-primary-subtle text-primary border-primary',
            'awaiting_student_confirmation' => 'bg-indigo-subtle text-indigo border-indigo',
            'scheduled' => 'bg-teal-subtle text-teal border-teal',
            'completed' => 'bg-success-subtle text-success border-success',
            'rejected' => 'bg-danger-subtle text-danger border-danger',
            'cancelled' => 'bg-secondary-subtle text-secondary border-secondary',
            'reschedule_requested' => 'bg-orange-subtle text-orange border-orange',
            default => 'bg-light text-dark',
        };
    }
}
