<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use App\Models\Counselor;
use App\Models\CounselorAssignment;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru_bk']);
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuruBk()) {
            $counselor = Counselor::where('user_id', $user->id)->first();
            if (!$counselor) {
                return false;
            }

            $activeSemester = active_semester();
            if (!$activeSemester) {
                return false;
            }

            $counselorClassroomIds = CounselorAssignment::where('counselor_id', $counselor->id)
                ->where('semester_id', $activeSemester->id)
                ->pluck('classroom_id')
                ->toArray();

            $studentClassroomIds = $student->classrooms()
                ->wherePivot('academic_year_id', $activeSemester->academic_year_id)
                ->pluck('classrooms.id')
                ->toArray();

            return count(array_intersect($counselorClassroomIds, $studentClassroomIds)) > 0;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    public function import(User $user): bool
    {
        return $user->isAdmin();
    }

    public function export(User $user): bool
    {
        return $user->isAdmin();
    }
}
