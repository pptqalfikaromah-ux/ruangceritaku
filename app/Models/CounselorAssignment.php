<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CounselorAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'counselor_id',
        'classroom_id',
        'semester_id',
        'is_primary',
        'notes',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function scopeActiveSemester($query)
    {
        $activeSemester = active_semester();
        return $query->where('semester_id', $activeSemester?->id ?? 0);
    }

    public function scopeBinaan($query)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'guru_bk') {
            return $query;
        }

        $counselor = Counselor::where('user_id', $user->id)->first();
        if (!$counselor) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('counselor_id', $counselor->id);
    }
}
