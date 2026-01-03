<?php

use App\Http\Controllers\Api\PenyewaanController;
use Illuminate\Support\Facades\Route;

// Rental endpoints - use auth:sanctum for API or web for session
Route::middleware('auth')->group(function () {
    Route::post('/penyewaan', [PenyewaanController::class, 'store'])->name('penyewaan.store');
    Route::post('/penyewaan/{penyewaan}/approve', [PenyewaanController::class, 'approve'])->name('penyewaan.approve');
    Route::post('/penyewaan/{penyewaan}/reject', [PenyewaanController::class, 'reject'])->name('penyewaan.reject');
});
