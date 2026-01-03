<?php

namespace App\Policies;

use App\Models\Alat;
use App\Models\User;

class AlatPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Alat $alat): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Alat $alat): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }
}
