<?php

namespace App\Filament\Widgets;

use App\Models\Penyewaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PenyewaanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Penyewaan per Bulan';

    protected static ?string $maxContentWidth = 'full';

    protected static string $color = 'info';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getData(): array
    {
        // Get data for the last 12 months
        $data = Penyewaan::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->whereYear('created_at', '>=', now()->subMonths(12)->year)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->groupBy('year');

        $currentYear = now()->year;
        $yearData = $data->get($currentYear, collect());

        $labels = [];
        $counts = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $yearData->firstWhere('month', $month);
            $labels[] = $this->getMonthName($month);
            $counts[] = $monthData?->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penyewaan',
                    'data' => $counts,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getChartOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
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
