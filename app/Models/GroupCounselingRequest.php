<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GroupCounselingRequest extends Model
{
    use HasFactory;

    protected $table = 'group_counseling_requests';

    protected $fillable = [
        'ticket_number',
        'student_id',
        'academic_year_id',
        'semester_id',
        'service_type',
        'topic',
        'description',
        'preferred_date',
        'preferred_session',
        'status',
        'group_counseling_id',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function groupCounseling(): BelongsTo
    {
        return $this->belongsTo(GroupCounseling::class, 'group_counseling_id');
    }

    /**
     * Generate Nomor Tiket Pengajuan Mandiri Unik
     */
    public static function generateTicketNumber(string $serviceType = 'bimbingan_kelompok'): string
    {
        $prefix = $serviceType === 'konseling_kelompok' ? 'KKP' : 'BKP';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        $ticket = "REQ-{$prefix}-{$date}-{$random}";
        while (static::where('ticket_number', $ticket)->exists()) {
            $random = strtoupper(Str::random(4));
            $ticket = "REQ-{$prefix}-{$date}-{$random}";
        }

        return $ticket;
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return match ($this->service_type) {
            'konseling_kelompok' => 'Konseling Kelompok (KKP)',
            default => 'Bimbingan Kelompok (BKP)',
        };
    }

    public function getServiceTypeBadgeClassAttribute(): string
    {
        return match ($this->service_type) {
            'konseling_kelompok' => 'bg-purple-subtle text-purple',
            default => 'bg-teal-subtle text-mint',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dikelompokkan' => 'Sudah Dikelompokkan',
            'dibatalkan' => 'Dibatalkan',
            default => 'Menunggu Pengelompokan Guru BK',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'dikelompokkan' => 'bg-success text-white',
            'dibatalkan' => 'bg-danger text-white',
            default => 'bg-warning text-dark',
        };
    }
}

