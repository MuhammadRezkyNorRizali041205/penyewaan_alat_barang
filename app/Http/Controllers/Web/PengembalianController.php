<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Services\PengembalianService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengembalianController extends Controller
{
    public function __construct(private PengembalianService $service)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $penyewaans = Penyewaan::with(['penyewa', 'alats', 'pengembalian'])
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Pengembalian/Index', [
            'penyewaans' => $penyewaans,
        ]);
    }

    public function show(Penyewaan $penyewaan)
    {
        $penyewaan->load(['penyewa', 'alats', 'pengembalian']);

        return Inertia::render('Pengembalian/Show', [
            'penyewaan' => $penyewaan,
        ]);
    }

    public function process(Request $request, Penyewaan $penyewaan)
    {
        // Basic authorization: allow admin (user id 1) or assigned petugas
        $user = $request->user();

        // If user not authenticated or not allowed, deny
        if (! $user || ($user->id !== 1 && $user->id !== $penyewaan->petugas_id)) {
            abort(403);
        }

        $data = $request->validate([
            'tanggal_pengembalian' => ['required', 'date'],
            'status_pengembalian' => ['required', 'in:lengkap,rusak,hilang'],
        ]);

        $pengembalian = $this->service->processReturn(
            $penyewaan,
            $data['tanggal_pengembalian'],
            $data['status_pengembalian'],
            $user->id
        );

        return redirect()->route('pengembalian.show', $penyewaan->id)->with('success', 'Pengembalian diproses');
    }
}
