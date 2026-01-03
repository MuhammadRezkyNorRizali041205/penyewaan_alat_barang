<?php

namespace App\Filament\Widgets;

use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DailySummary extends BaseWidget
{
    protected static ?int $sort = 20;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getStats(): array
    {
        $today = now()->toDateString();

        $todayRentals = Penyewaan::whereDate('created_at', $today)->count();

        $todayRevenue = Penyewaan::whereDate('created_at', $today)
            ->where('status', 'approved')
            ->sum('total_harga');

        $lateReturns = Pengembalian::whereDate('tanggal_pengembalian', $today)
            ->where('denda', '>', 0)
            ->count();

        $pendingApprovals = Penyewaan::where('status', 'pending')->count();

        return [
            Stat::make('Penyewaan Hari Ini', $todayRentals)
                ->description('Total transaksi hari ini')
                ->icon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Omzet Hari Ini', 'Rp '.number_format($todayRevenue, 0, ',', '.'))
                ->description('Approved today')
                ->icon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pengembalian Terlambat', $lateReturns)
                ->description('Hari ini')
                ->icon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Approval Menunggu', $pendingApprovals)
                ->description('Perlu tindakan')
                ->icon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}
