<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip_nuptk',
        'name',
        'gender',
        'phone',
        'email',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function currentAssignment()
    {
        $activeSemester = active_semester();
        return $this->hasOne(HomeroomAssignment::class)
            ->where('semester_id', $activeSemester?->id ?? 0);
    }

    public function complaints()
    {
        return $this->hasMany(HomeroomComplaint::class);
    }
}
