<?php

namespace App\Domains\Denda;

class DendaCalculator
{
    public const FINE_MULTIPLIER = 1.5;

    /**
     * Calculate fine based on daily rate and days late.
     */
    public static function calculateFine(float $dailyRate, int $daysLate): float
    {
        if ($daysLate <= 0) {
            return 0;
        }

        return $dailyRate * $daysLate * self::FINE_MULTIPLIER;
    }

    /**
     * Check if rental is late.
     */
    public static function isLate(\DateTime $dueDate, \DateTime $returnDate): bool
    {
        return $returnDate > $dueDate;
    }

    /**
     * Calculate days late.
     */
    public static function calculateDaysLate(\DateTime $dueDate, \DateTime $returnDate): int
    {
        if (! self::isLate($dueDate, $returnDate)) {
            return 0;
        }

        return (int) $returnDate->diff($dueDate)->days;
    }
}
