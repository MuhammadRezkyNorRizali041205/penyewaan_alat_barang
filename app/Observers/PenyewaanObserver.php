<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Penyewaan;
use App\Services\ActivityLoggerService;
use Illuminate\Support\Facades\Auth;

class PenyewaanObserver
{
    /**
     * Handle the Penyewaan "created" event.
     */
    public function created(Penyewaan $penyewaan): void
    {
        AuditLog::create([
            'model_type' => Penyewaan::class,
            'model_id' => $penyewaan->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => "Penyewaan #{$penyewaan->id} dibuat oleh penyewa.",
            'changes' => $penyewaan->getAttributes(),
        ]);
    }

    /**
     * Handle the Penyewaan "updated" event.
     */
    public function updated(Penyewaan $penyewaan): void
    {
        $changes = $penyewaan->getChanges();

        // Log only significant changes
        if (count($changes) > 1 || ! isset($changes['updated_at'])) {
            AuditLog::create([
                'model_type' => Penyewaan::class,
                'model_id' => $penyewaan->id,
                'user_id' => Auth::id(),
                'action' => match ($penyewaan->status) {
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'returned' => 'returned',
                    'cancelled' => 'cancelled',
                    default => 'updated',
                },
                'description' => "Penyewaan #{$penyewaan->id} status diubah menjadi {$penyewaan->status}.",
                'changes' => $changes,
            ]);

            // Log to activity log for pegawai staff monitoring
            if (Auth::check() && Auth::user()?->isStaff()) {
                $statusLabel = match ($penyewaan->status) {
                    'approved' => 'Menyetujui penyewaan',
                    'rejected' => 'Menolak penyewaan',
                    'returned' => 'Memproses pengembalian',
                    'cancelled' => 'Membatalkan penyewaan',
                    default => 'Mengubah penyewaan',
                };

                $penyewaan->load('penyewa', 'alats');
                $alatNames = $penyewaan->alats->pluck('nama_alat')->implode(', ');

                ActivityLoggerService::log(
                    Auth::user(),
                    'penyewaan_'.$penyewaan->status,
                    "{$statusLabel} dari {$penyewaan->penyewa->name} untuk alat {$alatNames}",
                    'Penyewaan',
                    $penyewaan->id
                );
            }
        }
    }

    /**
     * Handle the Penyewaan "deleted" event.
     */
    public function deleted(Penyewaan $penyewaan): void
    {
        AuditLog::create([
            'model_type' => Penyewaan::class,
            'model_id' => $penyewaan->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => "Penyewaan #{$penyewaan->id} dihapus.",
        ]);
    }
}
