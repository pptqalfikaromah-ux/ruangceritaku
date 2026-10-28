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
        if ($user->isAdmin() || $user->isGuruBk()) {
            return true;
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
