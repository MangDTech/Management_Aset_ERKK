<?php

namespace App\Filament\Resources\KbarangResource\Pages;

use App\Filament\Resources\KbarangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKbarang extends EditRecord
{
    protected static string $resource = KbarangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
