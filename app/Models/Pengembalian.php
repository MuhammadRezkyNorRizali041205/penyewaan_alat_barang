<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'penyewaan_id',
        'petugas_id',
        'tanggal_pengembalian',
        'hari_keterlambatan',
        'denda',
        'status_pengembalian',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'denda' => 'decimal:2',
    ];

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
