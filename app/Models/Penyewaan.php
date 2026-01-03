<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaans';

    protected $fillable = [
        'penyewa_id',
        'petugas_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'total_harga',
        'catatan',
        'tanggal_approval',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_approval' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function alats(): BelongsToMany
    {
        return $this->belongsToMany(Alat::class, 'alat_penyewaan')
            ->withPivot('jumlah', 'harga_satuan', 'subtotal')
            ->withTimestamps();
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Check if rental is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if rental is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
