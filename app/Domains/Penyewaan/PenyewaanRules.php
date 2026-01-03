<?php

namespace App\Domains\Penyewaan;

class PenyewaanRules
{
    public const MAX_RENTAL_DAYS = 30;

    public static function validateRentalDays(int $days): bool
    {
        return $days > 0 && $days <= self::MAX_RENTAL_DAYS;
    }

    public static function validateRentalPeriod(\DateTime $startDate, \DateTime $endDate): bool
    {
        $days = $endDate->diff($startDate)->days;

        return self::validateRentalDays($days);
    }

    public static function calculateRentalDays(\DateTime $startDate, \DateTime $endDate): int
    {
        return (int) $endDate->diff($startDate)->days;
    }

    public static function validateStatus(string $status): bool
    {
        $validStatuses = ['pending', 'approved', 'rejected', 'returned', 'cancelled'];

        return in_array($status, $validStatuses);
    }
}
