<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselingRequestHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'counseling_request_id',
        'user_id',
        'status_from',
        'status_to',
        'notes',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CounselingRequest::class, 'counseling_request_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
