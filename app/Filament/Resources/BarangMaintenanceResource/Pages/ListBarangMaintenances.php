<?php

namespace App\Filament\Resources\BarangMaintenanceResource\Pages;

use App\Filament\Resources\BarangMaintenanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangMaintenances extends ListRecords
{
    protected static string $resource = BarangMaintenanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
