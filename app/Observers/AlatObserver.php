<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AlatObserver
{
    /**
     * Handle the Alat "updated" event.
     */
    public function updated(Alat $alat): void
    {
        $changes = $alat->getChanges();

        // Only log stock-related changes
        $stockFields = ['stok_total', 'stok_tersedia', 'stok_disewa'];
        $stockChanges = array_intersect_key($changes, array_flip($stockFields));

        if (! empty($stockChanges)) {
            AuditLog::create([
                'model_type' => Alat::class,
                'model_id' => $alat->id,
                'user_id' => Auth::id(),
                'action' => 'stock_updated',
                'description' => "Stok {$alat->nama_alat} diperbarui.",
                'changes' => $stockChanges,
            ]);
        }
    }
}
