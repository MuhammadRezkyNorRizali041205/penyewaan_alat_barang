<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengembalianExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Pengembalian::with(['penyewaan.penyewa', 'penyewaan.alats', 'petugas'])
            ->get()
            ->map(function ($pengembalian) {
                return [
                    $pengembalian->id,
                    $pengembalian->penyewaan->penyewa->name,
                    $pengembalian->penyewaan->alats->pluck('nama_alat')->join(', '),
                    $pengembalian->tanggal_pengembalian?->format('d/m/Y'),
                    $pengembalian->petugas->name ?? '-',
                    'Rp '.number_format((float) ($pengembalian->denda ?? 0), 0, ',', '.'),
                    $pengembalian->hari_keterlambatan ?? 0,
                    $pengembalian->created_at?->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Penyewa',
            'Alat',
            'Tanggal Pengembalian',
            'Petugas',
            'Denda',
            'Hari Terlambat',
            'Tanggal Input',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'fgColor' => ['argb' => 'FFD3D3D3']]],
        ];
    }
}
