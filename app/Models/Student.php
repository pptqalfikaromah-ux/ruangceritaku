<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'address',
        'guardian_name',
        'guardian_phone',
        'photo',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student')
            ->withPivot('academic_year_id')
            ->withTimestamps();
    }

    public function imsAssessments()
    {
        return $this->hasMany(ImsAssessment::class);
    }

    public function counselingRequests()
    {
        return $this->hasMany(CounselingRequest::class);
    }

    public function studentComplaints()
    {
        return $this->hasMany(StudentComplaint::class);
    }

    public function pamphlets()
    {
        return $this->hasMany(StudentPamphlet::class);
    }

    /**
     * Rombel pada tahun ajaran aktif saat ini
     */
    public function currentClassroom()
    {
        $activeYear = active_academic_year();
        return $this->belongsToMany(Classroom::class, 'classroom_student')
            ->wherePivot('academic_year_id', $activeYear?->id ?? 0)
            ->withTimestamps();
    }

    public function getCurrentClassroomAttribute(): ?Classroom
    {
        $activeYear = active_academic_year();
        if (!$activeYear) {
            return $this->classrooms()->latest()->first();
        }

        return $this->classrooms()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->first();
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
                return $this->photo;
            }
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if ($disk === 's3') {
                return Storage::disk('s3')->url($this->photo);
            }
            if (Storage::disk('public')->exists($this->photo)) {
                return asset('storage/' . $this->photo);
            }
            return asset('storage/' . $this->photo);
        }

        // Placeholder avatar berdasarkan jenis kelamin
        $initial = urlencode($this->name);
        $bgColor = $this->gender === 'L' ? '0d9488' : 'ec4899';
        return "https://ui-avatars.com/api/?name={$initial}&background={$bgColor}&color=fff&size=128";
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'bg-success',
            'lulus' => 'bg-primary',
            'pindah' => 'bg-warning text-dark',
            'nonaktif' => 'bg-secondary',
            default => 'bg-light text-dark',
        };
    }

    /**
     * Scope untuk menyaring murid:
     * Seluruh akun Guru BK dan Admin dapat mengakses semua murid
     */
    public function scopeBinaan($query)
    {
        return $query;
    }

    public function scopeForCounselor($query, User $user)
    {
        return $query;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        });

        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        $query->when($filters['gender'] ?? null, function ($q, $gender) {
            $q->where('gender', $gender);
        });

        $query->when($filters['classroom_id'] ?? null, function ($q, $classroomId) {
            $q->whereHas('classrooms', function ($sub) use ($classroomId) {
                $sub->where('classrooms.id', $classroomId);
            });
        });

        return $query;
    }
}
