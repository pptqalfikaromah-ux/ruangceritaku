<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BkRpl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bk_program_id',
        'counselor_id',
        'service_type_id',
        'guidance_field_id',
        'topic',
        'target_class',
        'allocation_time',
        'general_goals',
        'specific_goals',
        'materials',
        'methods',
        'media',
        'steps',
        'evaluation',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(BkProgram::class, 'bk_program_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(Counselor::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class);
    }
}
