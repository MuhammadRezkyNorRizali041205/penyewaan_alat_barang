<?php

namespace App\Actions\Penyewaan;

use App\Domains\Alat\StockValidator;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RejectPenyewaanAction
{
    /**
     * Execute the rental rejection action.
     */
    public function execute(Penyewaan $penyewaan, User $rejector, string $reason): Penyewaan
    {
        return DB::transaction(function () use ($penyewaan, $rejector, $reason) {
            // Release reserved stock if any
            if ($penyewaan->status === 'approved') {
                $items = $penyewaan->alats()->get()->map(fn ($alat) => [
                    'alat_id' => $alat->id,
                    'jumlah' => $alat->pivot->jumlah,
                ])->toArray();

                StockValidator::releaseStock($items);
            }

            // Update rental status
            $penyewaan->update([
                'status' => 'rejected',
                'petugas_id' => $rejector->id,
                'alasan_penolakan' => $reason,
                'tanggal_approval' => now(),
            ]);

            return $penyewaan->refresh();
        });
    }
}
