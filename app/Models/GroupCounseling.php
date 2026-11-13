<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupCounseling extends Model
{
    use HasFactory;

    protected $table = 'group_counselings';

    protected $fillable = [
        'ticket_number',
        'service_type',
        'leader_student_id',
        'academic_year_id',
        'semester_id',
        'topic',
        'description',
        'group_goal',
        'preferred_date',
        'preferred_session',
        'status',
        'counselor_id',
        'scheduled_date',
        'start_time',
        'end_time',
        'location',
        'meeting_link',
        'counselor_notes',
        'evaluation_result',
        'follow_up_plan',
        'duration_minutes',
        'completed_at',
        'read_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
        'read_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'leader_student_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class, 'counselor_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function memberRecords(): HasMany
    {
        return $this->hasMany(GroupCounselingMember::class, 'group_counseling_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'group_counseling_members', 'group_counseling_id', 'student_id')
            ->withTimestamps();
    }

    public function requests(): HasMany
    {
        return $this->hasMany(GroupCounselingRequest::class, 'group_counseling_id');
    }

    /**
     * Label jenis layanan
     */
    public function getServiceTypeLabelAttribute(): string
    {
        return match ($this->service_type) {
            'konseling_kelompok' => 'Konseling Kelompok (KKP)',
            default => 'Bimbingan Kelompok (BKP)',
        };
    }

    /**
     * Badge class jenis layanan
     */
    public function getServiceTypeBadgeClassAttribute(): string
    {
        return match ($this->service_type) {
            'konseling_kelompok' => 'bg-purple-subtle text-purple',
            default => 'bg-teal-subtle text-mint',
        };
    }

    /**
     * Label status layanan
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'Terjadwal',
            'selesai' => 'Selesai Dilaksanakan',
            'dibatalkan' => 'Dibatalkan',
            default => 'Menunggu Konfirmasi',
        };
    }

    /**
     * Badge class status layanan
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'bg-info-subtle text-info',
            'selesai' => 'bg-success-subtle text-success',
            'dibatalkan' => 'bg-danger-subtle text-danger',
            default => 'bg-warning-subtle text-warning',
        };
    }

    /**
     * Generate tiket unik
     */
    public static function generateTicketNumber(string $type = 'bimbingan_kelompok'): string
    {
        $prefix = $type === 'konseling_kelompok' ? 'KKP' : 'BKP';
        $dateCode = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 4));

        return "{$prefix}-{$dateCode}-{$random}";
    }
}

