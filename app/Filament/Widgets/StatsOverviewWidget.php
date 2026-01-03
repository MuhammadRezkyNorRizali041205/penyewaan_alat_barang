<?php

namespace App\Filament\Widgets;

use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getStats(): array
    {
        $totalRentals = Penyewaan::count();
        $activeRentals = Penyewaan::where('status', 'approved')->count();
        $thisMonthFines = Pengembalian::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('denda');

        return [
            Stat::make(
                'Total Penyewaan',
                $totalRentals
            )
                ->description('Semua penyewaan')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('info'),

            Stat::make(
                'Penyewaan Aktif',
                $activeRentals
            )
                ->description('Status approved')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),

            Stat::make(
                'Total Denda Bulan Ini',
                'Rp '.number_format($thisMonthFines, 0, ',', '.')
            )
                ->description('Denda '.now()->format('F Y'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
