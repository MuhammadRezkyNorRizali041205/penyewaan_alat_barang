<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DendaExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Pengembalian::with(['penyewaan.penyewa', 'penyewaan.alats', 'petugas'])
            ->whereNotNull('denda')
            ->where('denda', '>', 0)
            ->get()
            ->map(function ($pengembalian) {
                return [
                    $pengembalian->id,
                    $pengembalian->penyewaan->penyewa->name,
                    $pengembalian->penyewaan->alats->pluck('nama_alat')->join(', '),
                    'Rp '.number_format((float) $pengembalian->denda, 0, ',', '.'),
                    $pengembalian->hari_keterlambatan,
                    $pengembalian->tanggal_pengembalian?->format('d/m/Y'),
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
            'Denda',
            'Hari Terlambat',
            'Tanggal Pengembalian',
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
