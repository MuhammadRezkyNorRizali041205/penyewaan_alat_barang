<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AlatController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rental (Penyewaan) Routes - ALL require auth
    Route::resource('penyewaan', \App\Http\Controllers\Web\PenyewaanController::class);
    Route::post('/penyewaan/{penyewaan}/approve', [\App\Http\Controllers\Web\PenyewaanController::class, 'approve'])
        ->name('penyewaan.approve');
    Route::post('/penyewaan/{penyewaan}/reject', [\App\Http\Controllers\Web\PenyewaanController::class, 'reject'])
        ->name('penyewaan.reject');
    Route::get('/penyewaan/{penyewaan}/invoice', [\App\Http\Controllers\Web\PenyewaanController::class, 'invoice'])
        ->name('penyewaan.invoice');

    // Returns (Pengembalian)
    Route::get('/pengembalian', [\App\Http\Controllers\Web\PengembalianController::class, 'index'])
        ->name('pengembalian.index');
    Route::get('/pengembalian/{penyewaan}', [\App\Http\Controllers\Web\PengembalianController::class, 'show'])
        ->name('pengembalian.show');
    Route::post('/pengembalian/{penyewaan}/process', [\App\Http\Controllers\Web\PengembalianController::class, 'process'])
        ->name('pengembalian.process');

    // Reports (Laporan)
    Route::get('/laporan', [\App\Http\Controllers\Web\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/penyewaan', [\App\Http\Controllers\Web\LaporanController::class, 'penyewaan'])->name('laporan.penyewaan');
    Route::get('/laporan/denda', [\App\Http\Controllers\Web\LaporanController::class, 'denda'])->name('laporan.denda');
});

// Marketplace (public)
Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
Route::get('/alat/{alat}', [AlatController::class, 'show'])->name('alat.show');

require __DIR__.'/auth.php';
