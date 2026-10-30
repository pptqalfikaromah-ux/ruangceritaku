<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImsAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'semester_id',
        'status',
        'final_notes',
        'consult_to',
        'submitted_at',
        'read_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ImsAnswer::class);
    }

    public function customStatements(): HasMany
    {
        return $this->hasMany(ImsCustomStatement::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}
