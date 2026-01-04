<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AlatController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

// Define custom rate limiters
RateLimiter::for('penyewaan', function ($request) {
    return $request->user()
        ? Limit::perMinute(10)->by($request->user()->id)
        : Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('payment', function ($request) {
    return $request->user()
        ? Limit::perMinute(5)->by($request->user()->id)
        : Limit::perMinute(3)->by($request->ip());
});

RateLimiter::for('invoice', function ($request) {
    return $request->user()
        ? Limit::perMinute(20)->by($request->user()->id)
        : Limit::perMinute(10)->by($request->ip());
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }

    return redirect('/login');
});

Route::get('/dashboard', \App\Http\Controllers\Web\DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rental (Penyewaan) Routes - ALL require auth
    Route::middleware('throttle:penyewaan')->group(function () {
        Route::resource('penyewaan', \App\Http\Controllers\Web\PenyewaanController::class);
        Route::post('/penyewaan/{penyewaan}/approve', [\App\Http\Controllers\Web\PenyewaanController::class, 'approve'])
            ->name('penyewaan.approve');
        Route::post('/penyewaan/{penyewaan}/reject', [\App\Http\Controllers\Web\PenyewaanController::class, 'reject'])
            ->name('penyewaan.reject');
    });
    Route::get('/penyewaan/{penyewaan}/invoice', [\App\Http\Controllers\Web\PenyewaanController::class, 'invoice'])
        ->name('penyewaan.invoice');

    // Payment Routes with rate limiting
    Route::middleware('throttle:payment')->group(function () {
        Route::get('/pembayaran/{penyewaan}', [\App\Http\Controllers\Web\PaymentController::class, 'show'])
            ->name('payment.show');
        Route::post('/pembayaran/{penyewaan}/process', [\App\Http\Controllers\Web\PaymentController::class, 'process'])
            ->name('payment.process');
    });
    Route::get('/riwayat-pembayaran', [\App\Http\Controllers\Web\PaymentController::class, 'history'])
        ->name('payment.history');

    // Invoice Routes with rate limiting
    Route::middleware('throttle:invoice')->group(function () {
        Route::get('/invoice/{penyewaan}/preview', [\App\Http\Controllers\Web\InvoiceController::class, 'preview'])
            ->name('invoice.preview');
        Route::get('/invoice/{penyewaan}/download', [\App\Http\Controllers\Web\InvoiceController::class, 'download'])
            ->name('invoice.download');
    });

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
