<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanChart extends BarChartWidget
{
    protected static ?string $heading = 'Grafik Barang yang Dikembalikan per Bulan';

    protected function getData(): array
    {
        // Ambil data dari tabel barang_pengembalian untuk 12 bulan terakhir
        $data = DB::table('barang_pengembalian')
            ->select('tanggal_pengembalian', 'jumlah')
            ->whereNotNull('tanggal_pengembalian')
            ->where('tanggal_pengembalian', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->get();

        // Siapkan array bulan dari 12 bulan terakhir dengan nilai default 0
        $months = [];
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $month = $startMonth->copy()->addMonths($i)->format('Y-m');
            $months[$month] = 0;
        }

        // Hitung total jumlah barang yang dikembalikan per bulan
        foreach ($data as $item) {
            $bulan = Carbon::parse($item->tanggal_pengembalian)->format('Y-m');
            if (isset($months[$bulan])) {
                $months[$bulan] += (int) $item->jumlah;
            }
        }

        // Buat label bulan untuk grafik dalam format "Mei 2025" (bisa diterjemahkan)
        $labels = array_map(
            fn($month) => Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y'),
            array_keys($months)
        );

        return [
            'datasets' => [[
                'label' => 'Jumlah Barang Dikembalikan',
                'data' => array_values($months),
                'backgroundColor' => 'rgba(56, 189, 248, 0.7)', // Warna biru muda
            ]],
            'labels' => $labels,
        ];
    }
}
