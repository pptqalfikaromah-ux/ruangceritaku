<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImsStatement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'statement_number',
        'service_type_id',
        'guidance_field_id',
        'statement_text',
        'is_active',
    ];

    protected $casts = [
        'statement_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function guidanceField(): BelongsTo
    {
        return $this->belongsTo(GuidanceField::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ImsAnswer::class);
    }
}
