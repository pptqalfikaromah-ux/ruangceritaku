<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImsAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ims_assessment_id',
        'ims_statement_id',
        'is_selected',
        'is_heavy',
        'student_note',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
        'is_heavy' => 'boolean',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(ImsAssessment::class, 'ims_assessment_id');
    }

    public function statement(): BelongsTo
    {
        return $this->belongsTo(ImsStatement::class, 'ims_statement_id');
    }
}
