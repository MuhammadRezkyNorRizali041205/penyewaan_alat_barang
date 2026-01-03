<?php

namespace App\Http\Controllers\Web;

use App\Actions\Penyewaan\ApprovePenyewaanAction;
use App\Actions\Penyewaan\RejectPenyewaanAction;
use App\Domains\Penyewaan\InvoiceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePenyewaanRequest;
use App\Http\Requests\RejectPenyewaanRequest;
use App\Models\Alat;
use App\Models\Penyewaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PenyewaanController extends Controller
{

    public function create(Request $request): Response
    {
        $alatId = $request->query('alat_id');

        $alat = $alatId ? Alat::with('kategori')->find($alatId) : null;

        return Inertia::render('Penyewaan/Create', [
            'selectedAlat' => $alat,
        ]);
    }

    public function store(Request $request): Response
    {
        $this->authorize('create', Penyewaan::class);

        $validated = $request->validate([
            'alat_id' => 'required|integer|exists:alats,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'alat_id.required' => 'Alat wajib dipilih.',
            'alat_id.exists' => 'Alat tidak ditemukan.',
            'tanggal_mulai.required' => 'Tanggal mulai sewa wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai sewa wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'jumlah.required' => 'Jumlah alat wajib diisi.',
            'jumlah.min' => 'Jumlah alat minimal 1.',
        ]);

        // Get the alat to check stock
        $alat = Alat::findOrFail($validated['alat_id']);
        if ($validated['jumlah'] > $alat->stok_tersedia) {
            return Inertia::render('Penyewaan/Create', [
                'selectedAlat' => $alat->load('kategori'),
                'errors' => ['jumlah' => 'Jumlah melebihi stok tersedia.'],
            ]);
        }

        // Create penyewaan
        $penyewaan = Penyewaan::create([
            'penyewa_id' => Auth::id(),
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status' => 'pending',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Attach alat with quantity
        $penyewaan->alats()->attach($validated['alat_id'], [
            'jumlah' => $validated['jumlah'],
            'harga_satuan' => $alat->harga_sewa_per_hari,
            'subtotal' => $alat->harga_sewa_per_hari * $validated['jumlah'],
        ]);

        return Inertia::render('Penyewaan/Create', [
            'penyewaan' => $penyewaan->load(['penyewa', 'alats', 'pengembalian']),
        ]);
    }

    public function index(): Response
    {
        $penyewaans = Penyewaan::with(['penyewa', 'alats'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Penyewaan/Index', [
            'penyewaans' => $penyewaans,
        ]);
    }

    public function show(Penyewaan $penyewaan): Response
    {
        $penyewaan->load(['penyewa', 'alats', 'pengembalian']);

        return Inertia::render('Penyewaan/Show', [
            'penyewaan' => $penyewaan,
            'canApprove' => Auth::user() ? Auth::user()->can('approve', $penyewaan) : false,
            'canReject' => Auth::user() ? Auth::user()->can('reject', $penyewaan) : false,
        ]);
    }

    public function approve(Penyewaan $penyewaan, ApprovePenyewaanRequest $request, ApprovePenyewaanAction $action): RedirectResponse
    {
        $this->authorize('approve', $penyewaan);

        $action->execute($penyewaan, Auth::user());

        return redirect()
            ->route('penyewaan.show', $penyewaan)
            ->with('success', 'Penyewaan berhasil disetujui');
    }

    public function reject(Penyewaan $penyewaan, RejectPenyewaanRequest $request, RejectPenyewaanAction $action): RedirectResponse
    {
        $this->authorize('reject', $penyewaan);

        $action->execute($penyewaan, Auth::user(), $request->validated('alasan_penolakan'));

        return redirect()
            ->route('penyewaan.show', $penyewaan)
            ->with('success', 'Penyewaan berhasil ditolak');
    }

    public function invoice(Penyewaan $penyewaan, InvoiceService $invoiceService)
    {
        $this->authorize('view', $penyewaan);

        try {
            return $invoiceService->downloadPdf($penyewaan);
        } catch (\Exception $e) {
            return redirect()
                ->route('penyewaan.show', $penyewaan)
                ->with('error', $e->getMessage());
        }
    }
}
