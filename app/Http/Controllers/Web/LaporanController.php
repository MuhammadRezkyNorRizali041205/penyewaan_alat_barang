<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index()
    {
        $totalPenyewaan = Penyewaan::count();
        $totalPengembalian = Pengembalian::count();
        $totalDenda = Pengembalian::sum('denda');

        return Inertia::render('Laporan/Index', [
            'totalPenyewaan' => $totalPenyewaan,
            'totalPengembalian' => $totalPengembalian,
            'totalDenda' => $totalDenda,
        ]);
    }

    public function penyewaan()
    {
        // Simple stat: count per status
        $stats = Penyewaan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return Inertia::render('Laporan/Penyewaan', [
            'stats' => $stats,
        ]);
    }

    public function denda()
    {
        // Sum of fines by month (DB driver-aware: DATE_FORMAT for MySQL, strftime for SQLite)
        $driver = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $monthExpr = "strftime('%Y-%m', tanggal_pengembalian)";
        } else {
            $monthExpr = "DATE_FORMAT(tanggal_pengembalian, '%Y-%m')";
        }

        $dendaByMonth = Pengembalian::select(DB::raw("{$monthExpr} as month"), DB::raw('SUM(denda) as total'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return Inertia::render('Laporan/Denda', [
            'dendaByMonth' => $dendaByMonth,
        ]);
    }
}
