<?php

namespace App\Filament\Resources\BarangMaintenanceResource\Pages;

use App\Filament\Resources\BarangMaintenanceResource;
use Filament\Resources\Pages\EditRecord;

class EditBarangMaintenance extends EditRecord
{
    protected static string $resource = BarangMaintenanceResource::class;

    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->status === 'selesai' && $record->tanggal_selesai === null) {
            $record->tanggal_selesai = now();
            $record->save();

            $barang = $record->barang;
            if ($barang) {
                $barang->jumlah_barang += $record->jumlah;
                $barang->save();
            }
        }
    }
}
