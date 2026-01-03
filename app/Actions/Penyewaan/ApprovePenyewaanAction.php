<?php

namespace App\Actions\Penyewaan;

use App\Domains\Alat\StockValidator;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApprovePenyewaanAction
{
    /**
     * Execute the rental approval action.
     *
     * @throws \Exception
     */
    public function execute(Penyewaan $penyewaan, User $approver): Penyewaan
    {
        return DB::transaction(function () use ($penyewaan, $approver) {
            // Validate stock availability
            $items = $penyewaan->alats()->get()->map(fn ($alat) => [
                'alat_id' => $alat->id,
                'jumlah' => $alat->pivot->jumlah,
            ])->toArray();

            $validation = StockValidator::validateStockAvailability($items);
            if (! $validation['valid']) {
                throw new \Exception($validation['message']);
            }

            // Reserve stock
            StockValidator::reserveStock($items);

            // Update rental status
            $penyewaan->update([
                'status' => 'approved',
                'petugas_id' => $approver->id,
                'tanggal_approval' => now(),
            ]);

            return $penyewaan->refresh();
        });
    }
}
