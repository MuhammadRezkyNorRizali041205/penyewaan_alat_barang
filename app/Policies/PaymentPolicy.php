<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        // User can only view their own payments
        return $user->id === $payment->user_id || $user->isStaff();
    }

    /**
     * Determine if the user can process payment.
     */
    public function process(User $user, Payment $payment): bool
    {
        // User can only process their own pending payments
        return $user->id === $payment->user_id && $payment->status === 'pending';
    }

    /**
     * Determine if the user can download invoice.
     */
    public function downloadInvoice(User $user, Payment $payment): bool
    {
        // Only paid payments can generate invoices
        if ($payment->status !== 'paid') {
            return false;
        }

        // User can download their own invoice or admin can download any
        return $user->id === $payment->user_id || $user->isStaff();
    }
}
