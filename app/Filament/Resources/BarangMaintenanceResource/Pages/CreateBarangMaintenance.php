<?php

namespace App\Filament\Resources\BarangMaintenanceResource\Pages;

use App\Filament\Resources\BarangMaintenanceResource;
use App\Models\Barang;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangMaintenance extends CreateRecord
{
    protected static string $resource = BarangMaintenanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $barang = Barang::find($data['barang_id']);
        if ($barang && $barang->jumlah_barang < $data['jumlah']) {
            throw new \Exception('Stok tidak mencukupi! Tersedia: ' . $barang->jumlah_barang);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->status === 'proses') {
            $barang = $record->barang;
            if ($barang) {
                $barang->jumlah_barang -= $record->jumlah;
                $barang->save();
            }
        }
    }
}
