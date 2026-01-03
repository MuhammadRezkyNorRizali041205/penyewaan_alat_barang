<?php

namespace App\Exports;

use App\Models\Penyewaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenyewaanExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Penyewaan::with(['penyewa', 'alats'])
            ->get()
            ->map(function ($penyewaan) {
                return [
                    $penyewaan->id,
                    $penyewaan->penyewa->name,
                    $penyewaan->alats->pluck('nama_alat')->join(', '),
                    $penyewaan->tanggal_mulai?->format('d/m/Y'),
                    $penyewaan->tanggal_selesai?->format('d/m/Y'),
                    $penyewaan->status,
                    'Rp '.number_format((float) $penyewaan->total_harga, 0, ',', '.'),
                    $penyewaan->created_at?->format('d/m/Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Penyewa',
            'Alat',
            'Mulai',
            'Selesai',
            'Status',
            'Total Harga',
            'Tanggal Pengajuan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'fgColor' => ['argb' => 'FFD3D3D3']]],
        ];
    }
}
