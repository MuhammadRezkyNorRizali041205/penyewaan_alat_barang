<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';

    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'stok_total',
        'stok_tersedia',
        'stok_disewa',
        'harga_sewa_per_hari',
        'gambar',
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'decimal:2',
    ];

    // Provide frontend-friendly derived attributes to keep UI decoupled from DB column names
    protected $appends = ['nama', 'harga_sewa', 'stok', 'gambar', 'gambar_url'];

    // Readable attribute mappings
    public function getNamaAttribute(): ?string
    {
        return $this->attributes['nama_alat'] ?? null;
    }

    public function getHargaSewaAttribute(): float
    {
        return isset($this->attributes['harga_sewa_per_hari']) ? (float) $this->attributes['harga_sewa_per_hari'] : 0.0;
    }

    public function getStokAttribute(): int
    {
        if (isset($this->attributes['stok_tersedia'])) {
            return (int) $this->attributes['stok_tersedia'];
        }

        return (int) ($this->attributes['stok_total'] ?? 0);
    }

    public function getGambarAttribute(): ?string
    {
        return $this->attributes['gambar'] ?? null;
    }

    public function getGambarUrlAttribute(): string
    {
        if ($this->attributes['gambar'] ?? null) {
            return '/storage/'.$this->attributes['gambar'];
        }

        return '/storage/alat-images/placeholder.png';
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function penyewaans(): BelongsToMany
    {
        return $this->belongsToMany(Penyewaan::class, 'alat_penyewaan')
            ->withPivot('jumlah', 'harga_satuan', 'subtotal')
            ->withTimestamps();
    }
}
