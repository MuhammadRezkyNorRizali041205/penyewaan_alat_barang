<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlatResource\Pages;
use App\Models\Alat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AlatResource extends Resource
{
    protected static ?string $model = Alat::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Alat';

    protected static ?string $modelLabel = 'Alat';

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()?->isStaff();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Alat')
                    ->schema([
                        Forms\Components\Select::make('kategori_id')
                            ->relationship('kategori', 'nama_kategori')
                            ->required()
                            ->label('Kategori'),

                        Forms\Components\TextInput::make('nama_alat')
                            ->required()
                            ->label('Nama Alat')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3),

                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar')
                            ->image()
                            ->maxSize(5120)
                            ->directory('alat-images')
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Stok & Harga')
                    ->schema([
                        Forms\Components\TextInput::make('stok_total')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Total Stok'),

                        Forms\Components\TextInput::make('stok_tersedia')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Stok Tersedia'),

                        Forms\Components\TextInput::make('stok_disewa')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Stok Disewa'),

                        Forms\Components\TextInput::make('harga_sewa_per_hari')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->label('Harga Sewa Per Hari (Rp)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar'),

                Tables\Columns\TextColumn::make('nama_alat')
                    ->label('Nama Alat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stok_tersedia')
                    ->label('Stok Tersedia')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_sewa_per_hari')
                    ->label('Harga/Hari')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlats::route('/'),
            'create' => Pages\CreateAlat::route('/create'),
            'edit' => Pages\EditAlat::route('/{record}/edit'),
        ];
    }
}
