<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Counselor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip_nuptk',
        'name',
        'title',
        'gender',
        'phone',
        'email',
        'photo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(CounselorAssignment::class);
    }

    public function currentAssignments()
    {
        $activeSemester = active_semester();
        return $this->hasMany(CounselorAssignment::class)
            ->where('semester_id', $activeSemester?->id ?? 0);
    }

    public function getFullNameWithTitleAttribute(): string
    {
        if ($this->title) {
            return $this->name . ', ' . $this->title;
        }
        return $this->name;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if (Storage::disk($disk)->exists($this->photo)) {
                return Storage::disk($disk)->url($this->photo);
            }
            if (Storage::disk('public')->exists($this->photo)) {
                return asset('storage/' . $this->photo);
            }
        }

        $initial = urlencode($this->name);
        $bgColor = $this->gender === 'L' ? '0f766e' : 'be185d';
        return "https://ui-avatars.com/api/?name={$initial}&background={$bgColor}&color=fff&size=128";
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
