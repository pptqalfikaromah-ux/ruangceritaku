<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'counseling_request_id',
        'counselor_id',
        'counseling_method',
        'scheduled_date',
        'start_time',
        'end_time',
        'location',
        'meeting_link',
        'schedule_status',
        'student_confirmation',
        'reschedule_reason',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CounselingRequest::class, 'counseling_request_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }
}
