<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KbarangResource\Pages;
use App\Filament\Resources\KbarangResource\RelationManagers;
use App\Models\Kbarang;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KbarangResource extends Resource
{
    protected static ?string $model = Kbarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Kualitas Barang';
    protected static ?string $navigationGroup = 'Detail Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Quality Barang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('Keterangan')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('Keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKbarangs::route('/'),
            'create' => Pages\CreateKbarang::route('/create'),
            'edit' => Pages\EditKbarang::route('/{record}/edit'),
        ];
    }    
}
