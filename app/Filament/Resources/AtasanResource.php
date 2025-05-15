<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AtasanResource\Pages;
use App\Filament\Resources\AtasanResource\RelationManagers;
use App\Models\Atasan;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AtasanResource extends Resource
{
    protected static ?string $model = Atasan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-add';
    protected static ?string $navigationLabel = 'Akun Atasan Aktif';
    protected static ?string $navigationGroup = 'informasi akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Atasan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Atasan')
                    ->searchable(),
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
            'index' => Pages\ListAtasans::route('/'),
            'create' => Pages\CreateAtasan::route('/create'),
            'edit' => Pages\EditAtasan::route('/{record}/edit'),
        ];
    }    
}
