<?php

namespace App\Filament\Resources\PeminjamResource\Pages;

use App\Filament\Resources\PeminjamResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeminjam extends EditRecord
{
    protected static string $resource = PeminjamResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
