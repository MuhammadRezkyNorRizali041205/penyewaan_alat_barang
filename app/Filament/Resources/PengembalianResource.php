<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengembalianResource\Pages;
use App\Models\Pengembalian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PengembalianResource extends Resource
{
    protected static ?string $model = Pengembalian::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Pengembalian';

    protected static ?string $modelLabel = 'Pengembalian';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengembalian')
                    ->schema([
                        Forms\Components\Select::make('penyewaan_id')
                            ->relationship('penyewaan', 'id')
                            ->label('Penyewaan')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DatePicker::make('tanggal_pengembalian')
                            ->label('Tanggal Pengembalian')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('hari_keterlambatan')
                            ->label('Hari Keterlambatan')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('denda')
                            ->label('Denda')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->prefix('Rp '),

                        Forms\Components\Select::make('status_pengembalian')
                            ->options([
                                'lengkap' => 'Lengkap',
                                'rusak' => 'Rusak',
                                'hilang' => 'Hilang',
                            ])
                            ->label('Status Pengembalian')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('penyewaan.penyewa.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pengembalian')
                    ->label('Tanggal Kembali')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hari_keterlambatan')
                    ->label('Terlambat (hari)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('denda')
                    ->label('Denda')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_pengembalian')
                    ->label('Status')
                    ->colors([
                        'success' => 'lengkap',
                        'warning' => 'rusak',
                        'danger' => 'hilang',
                    ])
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pengembalian')
                    ->options([
                        'lengkap' => 'Lengkap',
                        'rusak' => 'Rusak',
                        'hilang' => 'Hilang',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ])
            ->paginated([25, 50, 100])
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalians::route('/'),
            'view' => Pages\ViewPengembalian::route('/{record}'),
        ];
    }
}
