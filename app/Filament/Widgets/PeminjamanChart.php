<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanChart extends BarChartWidget
{
    protected static ?string $heading = 'Grafik History Peminjaman per Bulan';

    protected function getData(): array
    {
        // Ambil data 12 bulan terakhir (bulan berjalan paling depan)
       $rawData = DB::table('barang_pengembalian')
            ->selectRaw("DATE_FORMAT(tanggal_pengembalian, '%Y-%m') as bulan, SUM(jumlah) as total_barang")
            ->where('tanggal_pengembalian', '>=', Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total_barang', 'bulan')
            ->toArray();

        // Siapkan array bulan 12 bulan terakhir, bulan terbaru di depan
        $months = [];
        $startMonth = Carbon::now()->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $month = $startMonth->copy()->subMonths($i);
            $months[$month->format('Y-m')] = 0;
        }

        $months = array_reverse($months, true);

        foreach ($rawData as $bulan => $total) {
            if (isset($months[$bulan])) {
                $months[$bulan] = (int)$total;
            }
        }

        // Labels sederhana, bisa pakai Y-m juga kalau mau
        $labels = array_map(fn($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y'), array_keys($months));

        return [
            'datasets' => [[
                'label' => 'Jumlah Peminjaman',
                'data' => array_values($months),
                'backgroundColor' => 'rgba(243, 139, 49, 0.7)',
            ]],
            'labels' => $labels,
        ];
    }
}
