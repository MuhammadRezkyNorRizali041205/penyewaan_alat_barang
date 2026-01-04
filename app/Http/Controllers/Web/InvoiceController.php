<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Generate and download invoice PDF for a payment.
     */
    public function download(Penyewaan $penyewaan): Response
    {
        // Authorize user can view this rental
        $this->authorize('view', $penyewaan);

        // Invoice should exist for paid rentals only
        abort_unless($penyewaan->payment_status === 'paid' && $penyewaan->paid_at, 404);

        // Eager load alats with kategori relationship for PDF
        $penyewaan->load(['alats.kategori']);

        $payment = $penyewaan->latestPayment();
        $this->authorize('downloadInvoice', $payment);

        $data = [
            'penyewaan' => $penyewaan,
            'payment' => $payment,
            'invoiceNumber' => $this->generateInvoiceNumber($penyewaan),
            'invoiceDate' => now(),
        ];

        $pdf = Pdf::loadView('invoices.payment', $data);
        $filename = str_replace('/', '-', $data['invoiceNumber']);

        return $pdf->download("Invoice-{$filename}.pdf");
    }

    /**
     * Show invoice PDF preview in browser.
     */
    public function preview(Penyewaan $penyewaan): Response
    {
        // Authorize user can view this rental
        $this->authorize('view', $penyewaan);

        // Invoice should exist for paid rentals only
        abort_unless($penyewaan->payment_status === 'paid' && $penyewaan->paid_at, 404);

        // Eager load alats with kategori relationship for PDF
        $penyewaan->load(['alats.kategori']);

        $payment = $penyewaan->latestPayment();
        $this->authorize('downloadInvoice', $payment);

        $data = [
            'penyewaan' => $penyewaan,
            'payment' => $payment,
            'invoiceNumber' => $this->generateInvoiceNumber($penyewaan),
            'invoiceDate' => now(),
        ];

        $pdf = Pdf::loadView('invoices.payment', $data);
        $filename = str_replace('/', '-', $data['invoiceNumber']);

        return $pdf->stream("Invoice-{$filename}.pdf");
    }

    /**
     * Generate unique invoice number.
     */
    private function generateInvoiceNumber(Penyewaan $penyewaan): string
    {
        // Format: INV/PENYEWAAN-ID/YEAR-MONTH/SEQUENCE
        $year = $penyewaan->paid_at->format('Y');
        $month = $penyewaan->paid_at->format('m');
        $id = str_pad($penyewaan->id, 4, '0', STR_PAD_LEFT);

        return "INV/{$id}/{$year}{$month}";
    }
}
