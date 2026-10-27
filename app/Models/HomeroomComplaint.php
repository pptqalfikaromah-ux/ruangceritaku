<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeroomComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'classroom_id',
        'student_id',
        'academic_year_id',
        'semester_id',
        'incident_date',
        'category',
        'title',
        'description',
        'initial_action',
        'teacher_notes',
        'status',
        'counselor_response',
        'handled_by_counselor_id',
        'handled_at',
        'read_at',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'handled_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function handledByCounselor()
    {
        return $this->belongsTo(Counselor::class, 'handled_by_counselor_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'kedisiplinan' => 'Kedisiplinan & Kerapian',
            'pelanggaran_aturan' => 'Pelanggaran Aturan / Tata Tertib',
            'akademik' => 'Akademik & Ketertinggalan Belajar',
            'sosial_pertemanan' => 'Sosial & Relasi Pertemanan',
            'perilaku_sikap' => 'Perilaku & Sikap Emosional',
            'bullying_konflik' => 'Perundungan / Konflik Antarsiswa',
            default => 'Insidental Lainnya',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_tindak_lanjut' => 'Menunggu Tindak Lanjut Guru BK',
            'sedang_ditangani' => 'Sedang Ditangani Guru BK',
            'selesai' => 'Selesai Ditindaklanjuti',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'menunggu_tindak_lanjut' => 'bg-warning-subtle text-warning-emphasis border border-warning',
            'sedang_ditangani' => 'bg-info-subtle text-info-emphasis border border-info',
            'selesai' => 'bg-success-subtle text-success border border-success',
            default => 'bg-secondary text-white',
        };
    }
}
