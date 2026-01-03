<?php

namespace App\Domains\Penyewaan;

use App\Models\Penyewaan;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    /**
     * Generate PDF invoice for a rental.
     *
     * @throws \Exception
     */
    public function generatePdf(Penyewaan $penyewaan): string
    {
        if ($penyewaan->status !== 'approved') {
            throw new \Exception('Invoice hanya dapat dibuat untuk penyewaan yang approved.');
        }

        $penyewaan->load('penyewa', 'alats');

        $invoiceNumber = $this->generateInvoiceNumber($penyewaan);
        $totalPrice = $this->calculateTotalPrice($penyewaan);
        $rentalDays = $this->calculateRentalDays($penyewaan);

        $pdf = Pdf::loadView('pdf.invoice', [
            'penyewaan' => $penyewaan,
            'invoiceNumber' => $invoiceNumber,
            'totalPrice' => $totalPrice,
            'rentalDays' => $rentalDays,
        ]);

        return $pdf->output();
    }

    /**
     * Download PDF invoice for a rental.
     */
    public function downloadPdf(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'approved') {
            throw new \Exception('Invoice hanya dapat diunduh untuk penyewaan yang approved.');
        }

        $penyewaan->load('penyewa', 'alats');

        $invoiceNumber = $this->generateInvoiceNumber($penyewaan);
        $totalPrice = $this->calculateTotalPrice($penyewaan);
        $rentalDays = $this->calculateRentalDays($penyewaan);

        $pdf = Pdf::loadView('pdf.invoice', [
            'penyewaan' => $penyewaan,
            'invoiceNumber' => $invoiceNumber,
            'totalPrice' => $totalPrice,
            'rentalDays' => $rentalDays,
        ]);

        return $pdf->download("invoice-{$invoiceNumber}.pdf");
    }

    /**
     * Generate invoice number based on penyewaan ID and date.
     */
    private function generateInvoiceNumber(Penyewaan $penyewaan): string
    {
        return sprintf(
            'INV-%s-%06d',
            $penyewaan->created_at->format('YmdHi'),
            $penyewaan->id
        );
    }

    /**
     * Calculate total rental price.
     */
    private function calculateTotalPrice(Penyewaan $penyewaan): int
    {
        return (int) $penyewaan->alats->sum(function ($alat) {
            return $alat->pivot->subtotal;
        });
    }

    /**
     * Calculate number of rental days.
     */
    private function calculateRentalDays(Penyewaan $penyewaan): int
    {
        return (int) $penyewaan->tanggal_selesai->diffInDays($penyewaan->tanggal_mulai) + 1;
    }
}
