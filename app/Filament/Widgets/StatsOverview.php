<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Barang;
use App\Models\Rating;
use App\Models\BarangPengembalian;


class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalBarang = Barang::sum('jumlah_barang');
        $totalRating = Rating::avg('rating');
        $totalPengembalian = BarangPengembalian::sum('jumlah');
        return [
    Card::make('Jumlah Barang', $totalBarang)
        ->extraAttributes(['class' => 'text-center'])
        ->description('Total Barang Tersedia')
        ->descriptionIcon('heroicon-s-trending-up')
        ->color('success'),
        

    Card::make('History Pengembalian', $totalPengembalian)
        ->extraAttributes(['class' => 'text-center'])
        ->description('Barang Dikembalikan')
        ->descriptionIcon('heroicon-s-trending-down')
        ->color('danger'),
    

    Card::make('Kepuasan', $totalRating ? number_format($totalRating, 2) : '0.00')
        ->extraAttributes(['class' => 'text-center'])
        ->description('Rata-rata rating pengguna (skala 1–5)')
        ->descriptionIcon('heroicon-s-star')
        ->color('success'),
];

    }
}