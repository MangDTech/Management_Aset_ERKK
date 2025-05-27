<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMaintenanceResource\Pages;
use App\Models\BarangMaintenance;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class BarangMaintenanceResource extends Resource
{
    protected static ?string $model = BarangMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Maintenance Barang';
    protected static ?string $navigationGroup = 'Maintenance Info';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByRaw("CASE 
                WHEN status = 'Proses' THEN 1 
                WHEN status = 'Selesai' THEN 2 
                ELSE 3 END")
            ->latest(); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('barang_id')
                    ->relationship('barang', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Kerusakan')
                    ->rows(3)
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->visible(fn ($get) => $get('status') === 'selesai'),

                Forms\Components\Select::make('status')
                    ->options([
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('proses')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.name')
                    ->label('Barang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(30),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai'),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'proses',
                        'success' => 'selesai',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('Selesaikan')
                    ->label('Selesai Maintenance')
                    ->visible(fn ($record) => $record->status === 'proses')
                    ->action(function ($record) {
                        $record->status = 'selesai';
                        $record->tanggal_selesai = now();
                        $record->save();

                        $barang = $record->barang;
                        if ($barang) {
                            $barang->jumlah_barang += $record->jumlah;
                            $barang->save();
                        }
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangMaintenances::route('/'),
            'create' => Pages\CreateBarangMaintenance::route('/create'),
            'edit' => Pages\EditBarangMaintenance::route('/{record}/edit'),
        ];
    }
}
