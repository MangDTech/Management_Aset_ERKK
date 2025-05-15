<?php

namespace App\Filament\Resources\AtasanResource\Pages;

use App\Filament\Resources\AtasanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAtasans extends ListRecords
{
    protected static string $resource = AtasanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
