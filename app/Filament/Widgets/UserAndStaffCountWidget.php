<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserAndStaffCountWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    protected function getStats(): array
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        return [
            Stat::make(
                'Total Pengguna',
                $totalUsers
            )
                ->description('User regular')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),

            Stat::make(
                'Total Pegawai',
                $totalPegawai
            )
                ->description('Staff')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),

            Stat::make(
                'Total Admin',
                $totalAdmin
            )
                ->description('Administrator')
                ->descriptionIcon('heroicon-o-shield-check')
                ->color('danger'),
        ];
    }
}
