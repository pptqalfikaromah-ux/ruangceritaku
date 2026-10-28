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
        'photo',
        'status',
    ];

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
                return $this->photo;
            }
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if ($disk === 's3') {
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->photo);
            }
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
                return asset('storage/' . $this->photo);
            }
            return asset('storage/' . $this->photo);
        }

        $initial = urlencode($this->name);
        $bgColor = $this->gender === 'L' ? 'd97706' : 'db2777';
        return "https://ui-avatars.com/api/?name={$initial}&background={$bgColor}&color=fff&size=128";
    }

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
