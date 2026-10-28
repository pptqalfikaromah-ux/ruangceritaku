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
        $query = $this->hasOne(HomeroomAssignment::class);
        if ($activeSemester) {
            $query->where('semester_id', $activeSemester->id);
        }
        return $query->whereHas('classroom')->latest('id');
    }

    public function complaints()
    {
        return $this->hasMany(HomeroomComplaint::class);
    }
}
