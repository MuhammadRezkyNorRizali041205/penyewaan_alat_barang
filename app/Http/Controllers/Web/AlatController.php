<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Inertia\Inertia;

class AlatController extends Controller
{
    /**
     * Display marketplace index with proper data selection.
     */
    public function index()
    {
        $query = Alat::query()
            ->with('kategori')
            ->select([
                'id',
                'kategori_id',
                'nama_alat',
                'deskripsi',
                'harga_sewa_per_hari',
                'stok_tersedia',
                'stok_total',
                'gambar',
                'created_at',
            ]);

        // Add search filter if provided
        if (request('search')) {
            $query->where('nama_alat', 'like', '%'.request('search').'%')
                ->orWhere('deskripsi', 'like', '%'.request('search').'%');
        }

        // Add category filter if provided
        if (request('kategori_id')) {
            $query->where('kategori_id', request('kategori_id'));
        }

        $alats = $query->paginate(12)
            ->through(fn ($alat) => $alat->append('gambar_url'));

        return Inertia::render('Marketplace/Index', [
            'alats' => $alats,
            'categories' => \App\Models\Kategori::select('id', 'nama_kategori')->get(),
            'filters' => [
                'search' => request('search'),
                'kategori_id' => request('kategori_id'),
            ],
        ]);
    }

    /**
     * Display individual product with full details.
     */
    public function show(Alat $alat)
    {
        $alat->load('kategori')->append('gambar_url');

        return Inertia::render('Marketplace/Show', [
            'alat' => $alat,
        ]);
    }
}
