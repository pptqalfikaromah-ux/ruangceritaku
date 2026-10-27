<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    public function counselor()
    {
        return $this->hasOne(Counselor::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function getStudentAttribute()
    {
        if ($this->relationLoaded('student')) {
            $existing = $this->getRelationValue('student');
            if ($existing) {
                return $existing;
            }
        }

        // Coba relasi hasOne standar
        $relation = $this->student()->first();
        if ($relation) {
            $this->setRelation('student', $relation);
            return $relation;
        }

        // Fallback cerdas: cari berdasarkan username = NISN
        $fallback = Student::where('nisn', $this->username)->first();
        if ($fallback) {
            if ($fallback->user_id !== $this->id) {
                $fallback->user_id = $this->id;
                $fallback->saveQuietly();
            }
            $this->setRelation('student', $fallback);
            return $fallback;
        }

        // Auto-heal / auto-provision profil murid jika belum ada
        if ($this->role === 'murid') {
            $newStudent = Student::create([
                'user_id' => $this->id,
                'nisn' => $this->username,
                'name' => $this->name,
                'status' => 'aktif',
            ]);
            $this->setRelation('student', $newStudent);
            return $newStudent;
        }

        return null;
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuruBk(): bool
    {
        return $this->role === 'guru_bk';
    }

    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }

    public function isMurid(): bool
    {
        return $this->role === 'murid';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'guru_bk' => 'Guru BK',
            'wali_kelas' => 'Wali Kelas',
            'murid' => 'Murid',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }
}
