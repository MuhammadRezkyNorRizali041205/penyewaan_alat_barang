<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can approve a pegawai (staff).
     * Only admin can approve other pegawai.
     */
    public function approve(User $admin, User $pegawai): bool
    {
        return $admin->role === 'admin' && $pegawai->role === 'pegawai';
    }

    /**
     * Determine if the user can reject/deactivate a pegawai.
     * Only admin can reject other pegawai.
     */
    public function reject(User $admin, User $pegawai): bool
    {
        return $admin->role === 'admin' && $pegawai->role === 'pegawai';
    }

    /**
     * Determine if the user can reset password for a pegawai.
     * Only admin can reset pegawai passwords.
     */
    public function resetPassword(User $admin, User $pegawai): bool
    {
        return $admin->role === 'admin' && $pegawai->role === 'pegawai';
    }
}
