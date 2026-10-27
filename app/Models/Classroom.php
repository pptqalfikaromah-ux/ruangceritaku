<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'name',
        'level',
        'major',
        'homeroom_teacher',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'classroom_student')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(CounselorAssignment::class);
    }

    public function currentAssignment()
    {
        $activeSemester = active_semester();
        return $this->hasOne(CounselorAssignment::class)
            ->where('semester_id', $activeSemester?->id ?? 0);
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function currentHomeroomAssignment()
    {
        $activeSemester = active_semester();
        return $this->hasOne(HomeroomAssignment::class)
            ->where('semester_id', $activeSemester?->id ?? 0);
    }

    public function homeroomComplaints()
    {
        return $this->hasMany(HomeroomComplaint::class);
    }

    /**
     * Scope untuk menyaring rombel:
     * - Jika Guru BK, hanya tampilkan rombel binaan di semester aktif
     * - Jika Admin, tampilkan semua
     */
    public function scopeBinaan($query)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'guru_bk') {
            return $query;
        }

        $counselor = Counselor::where('user_id', $user->id)->first();
        if (!$counselor) {
            return $query->whereRaw('1 = 0'); // Tidak ada rombel
        }

        $activeSemester = active_semester();
        if (!$activeSemester) {
            return $query->whereRaw('1 = 0');
        }

        $classroomIds = CounselorAssignment::where('counselor_id', $counselor->id)
            ->where('semester_id', $activeSemester->id)
            ->pluck('classroom_id');

        return $query->whereIn('classrooms.id', $classroomIds);
    }
}
