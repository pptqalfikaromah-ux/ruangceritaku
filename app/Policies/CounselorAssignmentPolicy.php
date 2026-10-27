<?php

namespace App\Policies;

use App\Models\CounselorAssignment;
use App\Models\User;

class CounselorAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru_bk']);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, CounselorAssignment $assignment): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, CounselorAssignment $assignment): bool
    {
        return $user->isAdmin();
    }
}
