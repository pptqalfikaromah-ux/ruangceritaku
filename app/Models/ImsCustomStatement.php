<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImsCustomStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ims_assessment_id',
        'service_type_id',
        'guidance_field_id',
        'statement_text',
        'student_note',
        'is_selected',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(ImsAssessment::class, 'ims_assessment_id');
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class, 'guidance_field_id');
    }
}
