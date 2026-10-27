<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounselingService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_type_id',
        'guidance_field_id',
        'counselor_id',
        'academic_year_id',
        'semester_id',
        'counseling_request_id',
        'student_complaint_id',
        'title',
        'target_type',
        'target_classroom_id',
        'target_student_id',
        'participant_count',
        'execution_date',
        'duration_minutes',
        'location',
        'activity_description',
        'evaluation_result',
        'follow_up_plan',
    ];

    protected $casts = [
        'execution_date' => 'date',
        'participant_count' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }

    public function targetClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'target_classroom_id');
    }

    public function targetStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'target_student_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(CounselingRequest::class, 'counseling_request_id');
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(StudentComplaint::class, 'student_complaint_id');
    }
}
