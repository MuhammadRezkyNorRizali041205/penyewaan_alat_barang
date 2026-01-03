<?php

namespace App\Http\Controllers\Api;

use App\Actions\Penyewaan\ApprovePenyewaanAction;
use App\Actions\Penyewaan\RejectPenyewaanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePenyewaanRequest;
use App\Http\Requests\RejectPenyewaanRequest;
use App\Http\Requests\StorePenyewaanRequest;
use App\Models\Penyewaan;
use App\Services\CreatePenyewaanService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PenyewaanController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CreatePenyewaanService $service) {}

    /**
     * Store a new rental request.
     */
    public function store(StorePenyewaanRequest $request): JsonResponse
    {
        $this->authorize('create', Penyewaan::class);

        try {
            $penyewaan = $this->service->execute(
                $request->user(),
                $request->tanggal_mulai,
                $request->tanggal_selesai,
                $request->items,
                $request->catatan
            );

            return response()->json($penyewaan->load('alats'), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Approve a rental request.
     */
    public function approve(ApprovePenyewaanRequest $request, Penyewaan $penyewaan): JsonResponse
    {
        $this->authorize('approve', $penyewaan);

        $action = new ApprovePenyewaanAction;
        $penyewaan = $action->execute($penyewaan, $request->user());

        return response()->json($penyewaan->load('alats'), Response::HTTP_OK);
    }

    /**
     * Reject a rental request.
     */
    public function reject(RejectPenyewaanRequest $request, Penyewaan $penyewaan): JsonResponse
    {
        $this->authorize('reject', $penyewaan);

        $action = new RejectPenyewaanAction;
        $penyewaan = $action->execute($penyewaan, $request->user(), $request->alasan_penolakan);

        return response()->json($penyewaan, Response::HTTP_OK);
    }
}
