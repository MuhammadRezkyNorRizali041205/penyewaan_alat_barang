<?php

namespace App\Filament\Widgets;

use App\Models\Penyewaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class OmzetPenyewaanChart extends ChartWidget
{
    protected static ?string $heading = 'Omzet Penyewaan per Bulan';

    protected static ?string $maxContentWidth = 'full';

    protected static string $color = 'success';

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getData(): array
    {
        // Get revenue data for the last 12 months
        $data = Penyewaan::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_harga) as total_revenue')
            ->whereYear('created_at', '>=', now()->subMonths(12)->year)
            ->where('status', 'approved')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->groupBy('year');

        $currentYear = now()->year;
        $yearData = $data->get($currentYear, collect());

        $labels = [];
        $revenues = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $yearData->firstWhere('month', $month);
            $labels[] = $this->getMonthName($month);
            $revenues[] = (int) ($monthData?->total_revenue ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet Penyewaan (Rp)',
                    'data' => $revenues,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.3,
                    'fill' => true,
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
                        'callback' => 'function(value) { return "Rp " + (value/1000000).toFixed(1) + "M"; }',
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
