<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use App\Models\Counselor;
use App\Models\CounselorAssignment;

class ClassroomPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru_bk']);
    }

    public function view(User $user, Classroom $classroom): bool
    {
        if ($user->isAdmin() || $user->isGuruBk()) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->isAdmin();
    }

    public function manageStudents(User $user): bool
    {
        return $user->isAdmin();
    }
}
