<?php

namespace App\Filament\Resources\BarangPeminjamanResource\Pages;

use App\Filament\Resources\BarangPeminjamanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangPeminjamen extends ListRecords
{
    protected static string $resource = BarangPeminjamanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
