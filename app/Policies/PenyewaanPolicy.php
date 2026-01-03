<?php

namespace App\Policies;

use App\Models\Penyewaan;
use App\Models\User;

class PenyewaanPolicy
{
    /**
     * Determine if the user can view the rental.
     */
    public function view(User $user, Penyewaan $penyewaan): bool
    {
        // Staff (admin/pegawai) can view all rentals
        if ($user->isStaff()) {
            return true;
        }

        // Penyewa can only view their own rentals
        return $user->id === $penyewaan->penyewa_id;
    }

    /**
     * Determine if the user can create a rental.
     */
    public function create(User $user): bool
    {
        // Only regular users can create rentals
        return $user->role === 'user';
    }

    /**
     * Determine if the user can approve a rental.
     */
    public function approve(User $user, Penyewaan $penyewaan): bool
    {
        // Only staff (admin/pegawai) can approve rentals
        // Rental must be in pending status
        return $user->isStaff() && $penyewaan->status === 'pending';
    }

    /**
     * Determine if the user can reject a rental.
     */
    public function reject(User $user, Penyewaan $penyewaan): bool
    {
        // Only staff (admin/pegawai) can reject rentals
        // Rental must be in pending or approved status
        return $user->isStaff() && in_array($penyewaan->status, ['pending', 'approved']);
    }

    /**
     * Determine if the user can delete a rental.
     */
    public function delete(User $user, Penyewaan $penyewaan): bool
    {
        // Only admin can delete rentals
        return $user->isAdmin();
    }
}
