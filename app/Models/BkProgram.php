<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BkProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'semester_id',
        'service_type_id',
        'guidance_field_id',
        'name',
        'target_level',
        'month_planned',
        'goals',
        'evaluation_method',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class);
    }

    public function rpls(): HasMany
    {
        return $this->hasMany(BkRpl::class);
    }
}
