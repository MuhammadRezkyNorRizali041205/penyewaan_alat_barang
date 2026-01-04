<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Calculate total_harga from alat_penyewaan pivot table for rentals with NULL total_harga
        DB::statement('
            UPDATE penyewaans
            SET total_harga = COALESCE(
                (SELECT SUM(subtotal) FROM alat_penyewaan WHERE alat_penyewaan.penyewaan_id = penyewaans.id),
                0
            )
            WHERE total_harga IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set total_harga back to NULL for rentals we calculated
        DB::statement('
            UPDATE penyewaans
            SET total_harga = NULL
            WHERE id IN (
                SELECT penyewaan_id FROM alat_penyewaan
            )
        ');
    }
};
