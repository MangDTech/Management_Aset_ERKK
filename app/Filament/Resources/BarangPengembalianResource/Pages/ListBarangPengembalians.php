<?php

namespace App\Filament\Resources\BarangPengembalianResource\Pages;

use App\Filament\Resources\BarangPengembalianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangPengembalians extends ListRecords
{
    protected static string $resource = BarangPengembalianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
