<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Aktivitas Log';
    protected static ?string $navigationGroup = 'Activity Log';
     protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form schema kalau perlu
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')->label('Log')->sortable()->searchable(),
                TextColumn::make('causer.name')
                ->label('User yang Merubah')
                ->sortable()
                ->searchable()
                ->default(fn () => 'System / Guest'),
                TextColumn::make('description')->label('Deskripsi')->limit(50)->sortable()->searchable(),
                TextColumn::make('event')->label('Aksi')->sortable(),
                TextColumn::make('causer_id')->label('User ID')->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Waktu')->sortable(),
                
            ])
            ->actions([
                // Jika tidak ingin tombol view bisa dihapus atau dikomen
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                // Tambahkan filter jika perlu
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
    
}
