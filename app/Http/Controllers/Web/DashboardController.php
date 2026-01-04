<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real data from database.
     */
    public function __invoke(): Response
    {
        $user = auth()->user();

        // Get real statistics from database
        $totalPenyewaan = Penyewaan::where('penyewa_id', $user->id)->count();
        $activePenyewaan = Penyewaan::where('penyewa_id', $user->id)
            ->whereIn('status', ['approved', 'paid'])
            ->count();
        $pendingApproval = Penyewaan::where('penyewa_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Get total fines by joining pengembalian through penyewaan
        $totalDenda = Pengembalian::whereHas('penyewaan', function ($query) use ($user) {
            $query->where('penyewa_id', $user->id);
        })
            ->where('status_pengembalian', '!=', 'paid')
            ->sum('denda');

        // Get recent rentals with real data
        $recentActivity = Penyewaan::where('penyewa_id', $user->id)
            ->with('alats', 'pengembalian')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($penyewaan) => [
                'id' => $penyewaan->id,
                'items' => $penyewaan->alats->pluck('nama')->join(', '),
                'status' => $penyewaan->status,
                'date' => $penyewaan->created_at->format('Y-m-d'),
                'amount' => $penyewaan->total_harga,
            ]);

        // Get penyewaan yang perlu pembayaran
        $needsPayment = Penyewaan::where('penyewa_id', $user->id)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->with('alats')
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalPenyewaan' => $totalPenyewaan,
                'activePenyewaan' => $activePenyewaan,
                'pendingApproval' => $pendingApproval,
                'totalDenda' => (float) $totalDenda,
            ],
            'recentActivity' => $recentActivity,
            'needsPayment' => $needsPayment,
        ]);
    }
}
