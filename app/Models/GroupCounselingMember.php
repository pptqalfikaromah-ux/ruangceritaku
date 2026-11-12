<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupCounselingMember extends Model
{
    use HasFactory;

    protected $table = 'group_counseling_members';

    protected $fillable = [
        'group_counseling_id',
        'student_id',
        'notes',
    ];

    public function groupCounseling(): BelongsTo
    {
        return $this->belongsTo(GroupCounseling::class, 'group_counseling_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}

