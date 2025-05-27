<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangPengembalianResource\Pages;
use App\Filament\Resources\BarangPengembalianResource\RelationManagers;
use App\Models\BarangPengembalian;
use App\Models\BarangPeminjaman;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions;


class BarangPengembalianResource extends Resource
{
    protected static ?string $model = BarangPengembalian::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right';
    protected static ?string $navigationGroup = 'Peminjaman dan Pengembalian';
    protected static ?string $navigationLabel = 'Daftar Pengembalian Barang';
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByRaw("CASE 
                WHEN status != 'dikembalikan' THEN 1 
                WHEN status = 'dikembalikan' THEN 2 
                ELSE 3 END")
            ->latest(); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peminjam.name')->label('Nama Peminjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barang.name')->label('Nama Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kbarang.name')->label('Kualitas Barang'),
                Tables\Columns\TextColumn::make('jumlah'),
                Tables\Columns\TextColumn::make('tanggal_pinjam'),
                Tables\Columns\TextColumn::make('tanggal_pengembalian'),
                Tables\Columns\BadgeColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                \Filament\Tables\Actions\Action::make('setujui_pengembalian')
                    ->label('Setujui Pengembalian')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status !== 'dikembalikan')
                    ->action(function ($record) {
                        $record->status = 'dikembalikan';
                        $record->save();

                        \App\Models\BarangPeminjaman::where('barang_id', $record->barang_id)
                            ->where('peminjam_id', $record->peminjam_id)
                            ->delete();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pengembalian?')
                    ->modalSubheading('Data peminjaman terkait akan dihapus.'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListBarangPengembalians::route('/'),
            'create' => Pages\CreateBarangPengembalian::route('/create'),
            'edit' => Pages\EditBarangPengembalian::route('/{record}/edit'),
        ];
    }    
}
