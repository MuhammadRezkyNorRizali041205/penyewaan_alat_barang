<?php

namespace App\Domains\Alat;

use App\Models\Alat;

class StockValidator
{
    /**
     * Validate if stock is available for rental items.
     *
     * @param  array<array{alat_id: int, jumlah: int}>  $items
     * @return array{valid: bool, message?: string}
     */
    public static function validateStockAvailability(array $items): array
    {
        foreach ($items as $item) {
            $alat = Alat::find($item['alat_id']);

            if (! $alat) {
                return [
                    'valid' => false,
                    'message' => "Alat dengan ID {$item['alat_id']} tidak ditemukan.",
                ];
            }

            if ($alat->stok_tersedia < $item['jumlah']) {
                return [
                    'valid' => false,
                    'message' => "Stok {$alat->nama_alat} tidak cukup. Tersedia: {$alat->stok_tersedia}, Diminta: {$item['jumlah']}.",
                ];
            }
        }

        return ['valid' => true];
    }

    /**
     * Reserve stock for rental items.
     */
    public static function reserveStock(array $items): void
    {
        foreach ($items as $item) {
            $alat = Alat::find($item['alat_id']);
            if ($alat) {
                $alat->decrement('stok_tersedia', $item['jumlah']);
                $alat->increment('stok_disewa', $item['jumlah']);
            }
        }
    }

    /**
     * Release reserved stock on rejection or cancellation.
     */
    public static function releaseStock(array $items): void
    {
        foreach ($items as $item) {
            $alat = Alat::find($item['alat_id']);
            if ($alat) {
                $alat->increment('stok_tersedia', $item['jumlah']);
                $alat->decrement('stok_disewa', $item['jumlah']);
            }
        }
    }
}
