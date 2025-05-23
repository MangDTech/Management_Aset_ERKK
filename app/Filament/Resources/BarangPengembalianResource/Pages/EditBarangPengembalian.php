<?php

namespace App\Filament\Resources\BarangPengembalianResource\Pages;

use App\Filament\Resources\BarangPengembalianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangPengembalian extends EditRecord
{
    protected static string $resource = BarangPengembalianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
