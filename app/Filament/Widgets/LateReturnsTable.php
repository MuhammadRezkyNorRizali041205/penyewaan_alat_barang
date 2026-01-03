<?php

namespace App\Filament\Widgets;

use App\Models\Penyewaan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LateReturnsTable extends BaseWidget
{
    protected static ?string $heading = 'Pengembalian Terlambat Hari Ini';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penyewaan::query()
                    ->where('status', 'approved')
                    ->whereDate('tanggal_selesai', '<', today())
                    ->doesntHave('pengembalian')
                    ->with(['penyewa', 'alats'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('penyewa.name')
                    ->label('Penyewa')
                    ->sortable(),

                Tables\Columns\TextColumn::make('alats.nama_alat')
                    ->label('Alat')
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    }),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Seharusnya Kembali')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('days_late')
                    ->label('Hari Terlambat')
                    ->formatStateUsing(function (Penyewaan $record): string {
                        $daysLate = today()->diffInDays($record->tanggal_selesai);

                        return $daysLate.' hari';
                    })
                    ->color('danger'),
            ])
            ->paginated(false)
            ->striped();
    }
}
