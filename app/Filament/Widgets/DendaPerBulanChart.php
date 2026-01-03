<?php

namespace App\Filament\Widgets;

use App\Models\Pengembalian;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DendaPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Denda per Bulan';

    protected static ?string $maxContentWidth = 'full';

    protected static string $color = 'danger';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getData(): array
    {
        // Get denda data for the last 12 months
        $data = Pengembalian::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(denda) as total_denda')
            ->whereYear('created_at', '>=', now()->subMonths(12)->year)
            ->whereNotNull('denda')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->groupBy('year');

        $currentYear = now()->year;
        $yearData = $data->get($currentYear, collect());

        $labels = [];
        $dendas = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $yearData->firstWhere('month', $month);
            $labels[] = $this->getMonthName($month);
            $dendas[] = (int) ($monthData?->total_denda ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Denda (Rp)',
                    'data' => $dendas,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => '#ef4444',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getChartOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }

    private function getMonthName(int $month): string
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return $months[$month - 1];
    }
}
