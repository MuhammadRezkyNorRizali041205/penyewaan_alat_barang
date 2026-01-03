<?php

namespace Tests\Unit;

use App\Domains\Denda\DendaCalculator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DendaCalculatorTest extends TestCase
{
    #[Test]
    public function calculate_fine_with_no_late_days(): void
    {
        $fine = DendaCalculator::calculateFine(100000, 0);
        $this->assertEquals(0, $fine);
    }

    #[Test]
    public function calculate_fine_with_late_days(): void
    {
        $dailyRate = 100000;
        $daysLate = 5;

        $expectedFine = $dailyRate * $daysLate * DendaCalculator::FINE_MULTIPLIER;
        $fine = DendaCalculator::calculateFine($dailyRate, $daysLate);

        $this->assertEquals($expectedFine, $fine);
    }

    #[Test]
    public function is_late_with_future_return_date(): void
    {
        $dueDate = new \DateTime('2025-01-05');
        $returnDate = new \DateTime('2025-01-10');

        $this->assertTrue(DendaCalculator::isLate($dueDate, $returnDate));
    }

    #[Test]
    public function is_not_late_with_past_return_date(): void
    {
        $dueDate = new \DateTime('2025-01-10');
        $returnDate = new \DateTime('2025-01-05');

        $this->assertFalse(DendaCalculator::isLate($dueDate, $returnDate));
    }

    #[Test]
    public function calculate_days_late(): void
    {
        $dueDate = new \DateTime('2025-01-05');
        $returnDate = new \DateTime('2025-01-10');

        $daysLate = DendaCalculator::calculateDaysLate($dueDate, $returnDate);
        $this->assertEquals(5, $daysLate);
    }

    #[Test]
    public function calculate_days_late_with_no_delay(): void
    {
        $dueDate = new \DateTime('2025-01-10');
        $returnDate = new \DateTime('2025-01-05');

        $daysLate = DendaCalculator::calculateDaysLate($dueDate, $returnDate);
        $this->assertEquals(0, $daysLate);
    }
}
