<?php

namespace App\Filament\Widgets;

use App\Exports\DendaExport;
use App\Exports\PengembalianExport;
use App\Exports\PenyewaanExport;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportDashboardWidget extends Widget
{
    protected static string $view = 'filament.widgets.export-dashboard-widget';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    public function exportPenyewaan()
    {
        return Excel::download(new PenyewaanExport, 'penyewaan-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPengembalian()
    {
        return Excel::download(new PengembalianExport, 'pengembalian-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportDenda()
    {
        return Excel::download(new DendaExport, 'denda-'.now()->format('Y-m-d').'.xlsx');
    }
}
