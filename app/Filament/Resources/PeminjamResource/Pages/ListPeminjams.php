<?php

namespace App\Filament\Resources\PeminjamResource\Pages;

use App\Filament\Resources\PeminjamResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjams extends ListRecords
{
    protected static string $resource = PeminjamResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
