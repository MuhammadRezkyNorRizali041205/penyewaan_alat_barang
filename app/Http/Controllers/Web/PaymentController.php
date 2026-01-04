<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Show payment page for a rental.
     */
    public function show(Penyewaan $penyewaan)
    {
        // Authorize user can view this rental
        $this->authorize('view', $penyewaan);

        // Only show payment if rental is approved but not paid
        abort_unless(
            ($penyewaan->status === 'approved' && $penyewaan->payment_status === 'unpaid') ||
            $penyewaan->status === 'approved_unpaid',
            404
        );

        return Inertia::render('Payment/Show', [
            'penyewaan' => $penyewaan->load('alats', 'penyewa'),
            'payment' => $penyewaan->latestPayment(),
        ]);
    }

    /**
     * Process payment (simulate).
     */
    public function process(Request $request, Penyewaan $penyewaan)
    {
        // Authorize user owns this rental
        $this->authorize('view', $penyewaan);
        abort_unless(auth()->id() === $penyewaan->penyewa_id, 403);

        // Validate rental status for payment
        abort_unless(
            ($penyewaan->status === 'approved' && $penyewaan->payment_status === 'unpaid') ||
            $penyewaan->status === 'approved_unpaid',
            404
        );

        // Ensure total_harga is available
        if (!$penyewaan->total_harga) {
            // Calculate from pivot if somehow still NULL
            $penyewaan->total_harga = $penyewaan->alats()->sum('alat_penyewaan.subtotal');
            $penyewaan->save();
        }

        abort_unless($penyewaan->total_harga > 0, 422, 'Total pembayaran harus lebih dari 0');

        $validated = $request->validate([
            'payment_method' => 'required|in:transfer,card,e-wallet|string',
        ]);

        $payment = $penyewaan->payments()->firstOrCreate(
            [],
            [
                'user_id' => auth()->id(),
                'amount' => $penyewaan->total_harga,
                'status' => 'pending',
            ]
        );

        // Authorize payment processing
        $this->authorize('process', $payment);

        // Simulate payment - mark as paid
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => 'TXN-'.uniqid(),
            'payment_method' => $validated['payment_method'],
        ]);

        // Update penyewaan status to 'paid' (rental is now active)
        $penyewaan->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        // Return success page with redirect instruction
        return Inertia::render('Payment/Success', [
            'penyewaan' => $penyewaan->load('alats', 'penyewa'),
            'payment' => $payment,
            'invoiceNumber' => $this->generateInvoiceNumber($penyewaan),
        ]);
    }

    /**
     * Generate invoice number.
     */
    private function generateInvoiceNumber(Penyewaan $penyewaan): string
    {
        $year = $penyewaan->paid_at->format('Y');
        $month = $penyewaan->paid_at->format('m');
        $id = str_pad($penyewaan->id, 4, '0', STR_PAD_LEFT);

        return "INV/{$id}/{$year}{$month}";
    }

    /**
     * Show payment history for logged in user.
     */
    public function history()
    {
        $payments = auth()->user()
            ->payments()
            ->with('penyewaan')
            ->latest()
            ->paginate(10);

        return Inertia::render('Payment/History', [
            'payments' => $payments,
        ]);
    }
}
