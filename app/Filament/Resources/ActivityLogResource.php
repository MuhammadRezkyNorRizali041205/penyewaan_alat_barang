<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Log Aktivitas';

    protected static ?string $modelLabel = 'Log Aktivitas';

    protected static ?string $navigationGroup = 'Monitoring';

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Aktivitas')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Pegawai')
                            ->disabled(),

                        Forms\Components\TextInput::make('action')
                            ->label('Action')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->rows(3),

                        Forms\Components\TextInput::make('subject_type')
                            ->label('Tipe Subjek')
                            ->disabled(),

                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal & Waktu')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'penyewaan_approved' => 'success',
                        'penyewaan_rejected' => 'danger',
                        'penyewaan_returned' => 'info',
                        'reset_password' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Tipe')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'penyewaan_approved' => 'Approval Penyewaan',
                        'penyewaan_rejected' => 'Penolakan Penyewaan',
                        'penyewaan_returned' => 'Pengembalian',
                        'reset_password' => 'Reset Password',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pegawai'),
            ])
            ->paginated(false)
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
