<?php

namespace App\Services;

use App\Domains\Alat\StockValidator;
use App\Domains\Penyewaan\PenyewaanRules;
use App\Models\Alat;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreatePenyewaanService
{
    /**
     * Create a rental request with validation and calculations.
     *
     * @param  array<array{alat_id: int, jumlah: int}>  $items
     *
     * @throws \Exception
     */
    public function execute(
        User $penyewa,
        string $tanggal_mulai,
        string $tanggal_selesai,
        array $items,
        ?string $catatan = null
    ): Penyewaan {
        return DB::transaction(function () use ($penyewa, $tanggal_mulai, $tanggal_selesai, $items, $catatan) {
            // Validate rental dates
            $startDate = new \DateTime($tanggal_mulai);
            $endDate = new \DateTime($tanggal_selesai);
            $days = PenyewaanRules::calculateRentalDays($startDate, $endDate);

            if (! PenyewaanRules::validateRentalDays($days)) {
                throw new \Exception('Durasi penyewaan tidak valid. Maksimal 30 hari.');
            }

            // Validate stock availability
            $validation = StockValidator::validateStockAvailability($items);
            if (! $validation['valid']) {
                throw new \Exception($validation['message']);
            }

            // Calculate total price
            $totalHarga = 0;
            $alatData = [];

            foreach ($items as $item) {
                $alat = Alat::findOrFail($item['alat_id']);
                $subtotal = $alat->harga_sewa_per_hari * $days * $item['jumlah'];
                $totalHarga += $subtotal;

                $alatData[$item['alat_id']] = [
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $alat->harga_sewa_per_hari,
                    'subtotal' => $subtotal,
                ];
            }

            // Create rental
            $penyewaan = Penyewaan::create([
                'penyewa_id' => $penyewa->id,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'status' => 'pending',
                'total_harga' => $totalHarga,
                'catatan' => $catatan,
            ]);

            // Attach alats with calculated prices
            $penyewaan->alats()->attach($alatData);

            return $penyewaan->refresh();
        });
    }
}
