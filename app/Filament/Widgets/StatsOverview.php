<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Barang;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalBarang = Barang::sum('jumlah_barang');
        return [
            Card::make('Jumlah Barang', $totalBarang),
            Card::make('Barang Pengembalian', '21%'),
            Card::make('kepuasan', '3:12'),
        ];
    }
}