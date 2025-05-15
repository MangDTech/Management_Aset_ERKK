<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarang extends EditRecord
{
    protected static string $resource = BarangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
