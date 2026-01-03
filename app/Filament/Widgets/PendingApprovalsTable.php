<?php

namespace App\Filament\Widgets;

use App\Actions\Penyewaan\ApprovePenyewaanAction;
use App\Actions\Penyewaan\RejectPenyewaanAction;
use App\Models\Penyewaan;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class PendingApprovalsTable extends TableWidget
{
    protected static ?string $heading = 'Approval Penyewaan Menunggu';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        $user = Auth::user();

        return Auth::check()
            && $user instanceof User
            && $user->isStaff();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penyewaan::query()
                    ->where('status', 'pending')
                    ->with(['penyewa', 'alats'])
                    ->latest()
            )
            ->columns([
                TextColumn::make('penyewa.name')
                    ->label('Penyewa')
                    ->sortable(),

                TextColumn::make('alats.nama_alat')
                    ->label('Alat')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state
                    ),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setuju')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->visible(fn (Penyewaan $record) => Auth::user()?->can('approve', $record)
                    )
                    ->action(function (Penyewaan $record) {
                        app(ApprovePenyewaanAction::class)
                            ->execute($record, Auth::user());
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-m-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn (Penyewaan $record) => Auth::user()?->can('reject', $record)
                    )
                    ->action(function (Penyewaan $record) {
                        app(RejectPenyewaanAction::class)->execute(
                            $record,
                            Auth::user(),
                            'Ditolak melalui dashboard widget'
                        );
                    }),
            ])
            ->paginated(false)
            ->striped();
    }
}
