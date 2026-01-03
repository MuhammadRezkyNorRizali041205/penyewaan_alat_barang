<?php

namespace App\Services;

use App\Domains\Denda\DendaCalculator;
use App\Domains\Penyewaan\PenyewaanRules;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianService
{
    /**
     * Process a return for a given rental and calculate fine if late.
     */
    public function processReturn(
        Penyewaan $penyewaan,
        string $tanggalPengembalian,
        string $statusPengembalian = 'lengkap',
        ?int $petugasId = null
    ): Pengembalian {
        return DB::transaction(function () use (
            $penyewaan,
            $tanggalPengembalian,
            $statusPengembalian,
            $petugasId
        ) {
            $returnDate = new \DateTime($tanggalPengembalian);

            $dueDate = $penyewaan->tanggal_selesai instanceof \DateTimeInterface
                ? new \DateTime($penyewaan->tanggal_selesai->format('Y-m-d'))
                : new \DateTime($penyewaan->tanggal_selesai);

            $startDate = $penyewaan->tanggal_mulai instanceof \DateTimeInterface
                ? new \DateTime($penyewaan->tanggal_mulai->format('Y-m-d'))
                : new \DateTime($penyewaan->tanggal_mulai);

            $endDate = $penyewaan->tanggal_selesai instanceof \DateTimeInterface
                ? new \DateTime($penyewaan->tanggal_selesai->format('Y-m-d'))
                : new \DateTime($penyewaan->tanggal_selesai);

            $days = PenyewaanRules::calculateRentalDays($startDate, $endDate);

            $daysForRate = max(1, $days);

            $dailyRate = $penyewaan->total_harga > 0
                ? (float) $penyewaan->total_harga / $daysForRate
                : 0.0;

            $daysLate = DendaCalculator::calculateDaysLate($dueDate, $returnDate);
            $denda = DendaCalculator::calculateFine($dailyRate, $daysLate);

            $pengembalian = Pengembalian::create([
                'penyewaan_id' => $penyewaan->id,
                'tanggal_pengembalian' => $returnDate->format('Y-m-d'),
                'status_pengembalian' => $statusPengembalian,
                'denda' => $denda,
                'hari_keterlambatan' => $daysLate,
                'petugas_id' => $petugasId ?? Auth::id() ?? null,
            ]);

            $penyewaan->update([
                'status' => 'returned',
            ]);

            return $pengembalian->refresh();
        });
    }
}
