<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DendaResource\Pages;
use App\Models\Denda;
use App\Models\Peminjam;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class DendaResource extends Resource
{
    protected static ?string $model = Denda::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Peminjaman dan Pengembalian';
    protected static ?string $navigationLabel = 'Pembuatan Denda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peminjam_id')
                    ->label('Nama Peminjam')
                    ->options(Peminjam::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('jumlah_denda')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('peminjam.name')
                    ->label('Nama Peminjam')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_denda')
                    ->label('Jumlah Denda')
                    ->money('idr', true),
                TextColumn::make('keterangan')->label('Keterangan')->limit(50),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // Eager load 'peminjam' supaya relasi di table bisa dipanggil tanpa masalah N+1
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('peminjam');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDendas::route('/'),
            'create' => Pages\CreateDenda::route('/create'),
            'edit' => Pages\EditDenda::route('/{record}/edit'),
        ];
    }
}
