<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Inertia\Inertia;

class AlatController extends Controller
{
    // Render marketplace index (presentation only)
    public function index()
    {
        $alats = Alat::with('kategori')
            ->paginate(12)
            ->through(fn ($alat) => $alat->append('gambar_url'));

        return Inertia::render('Marketplace/Index', [
            'alats' => $alats,
        ]);
    }

    // Render individual product
    public function show(Alat $alat)
    {
        $alat->load('kategori')->append('gambar_url');

        return Inertia::render('Marketplace/Show', [
            'alat' => $alat,
        ]);
    }
}
