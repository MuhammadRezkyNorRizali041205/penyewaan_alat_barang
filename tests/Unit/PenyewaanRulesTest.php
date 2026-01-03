<?php

namespace Tests\Unit;

use App\Domains\Penyewaan\PenyewaanRules;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PenyewaanRulesTest extends TestCase
{
    #[Test]
    public function validate_rental_days_within_limit(): void
    {
        $this->assertTrue(PenyewaanRules::validateRentalDays(15));
    }

    #[Test]
    public function validate_rental_days_at_limit(): void
    {
        $this->assertTrue(PenyewaanRules::validateRentalDays(30));
    }

    #[Test]
    public function validate_rental_days_exceeds_limit(): void
    {
        $this->assertFalse(PenyewaanRules::validateRentalDays(31));
    }

    #[Test]
    public function validate_rental_days_zero(): void
    {
        $this->assertFalse(PenyewaanRules::validateRentalDays(0));
    }

    #[Test]
    public function validate_rental_period_valid(): void
    {
        $startDate = new \DateTime('2025-01-01');
        $endDate = new \DateTime('2025-01-15');

        $this->assertTrue(PenyewaanRules::validateRentalPeriod($startDate, $endDate));
    }

    #[Test]
    public function validate_rental_period_exceeds_max(): void
    {
        $startDate = new \DateTime('2025-01-01');
        $endDate = new \DateTime('2025-02-05');

        $this->assertFalse(PenyewaanRules::validateRentalPeriod($startDate, $endDate));
    }

    #[Test]
    public function calculate_rental_days(): void
    {
        $startDate = new \DateTime('2025-01-01');
        $endDate = new \DateTime('2025-01-11');

        $days = PenyewaanRules::calculateRentalDays($startDate, $endDate);
        $this->assertEquals(10, $days);
    }

    #[Test]
    public function validate_status_pending(): void
    {
        $this->assertTrue(PenyewaanRules::validateStatus('pending'));
    }

    #[Test]
    public function validate_status_approved(): void
    {
        $this->assertTrue(PenyewaanRules::validateStatus('approved'));
    }

    #[Test]
    public function validate_status_invalid(): void
    {
        $this->assertFalse(PenyewaanRules::validateStatus('invalid_status'));
    }
}
