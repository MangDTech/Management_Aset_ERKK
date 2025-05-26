<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\LineChartWidget;

class ActivityLogChart extends LineChartWidget
{
    protected static ?string $heading = 'Aktivitas Harian';

    protected function getData(): array
    {
        // Ambil data dari 30 hari terakhir
        $rawData = DB::table('activity_log')
            ->selectRaw("DATE(created_at) as tanggal, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray();

        // Siapkan array lengkap tanggal 30 hari ke belakang
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[$date] = 0;
        }

        // Masukkan data ke tanggal yang sesuai
        foreach ($rawData as $tanggal => $total) {
            if (isset($dates[$tanggal])) {
                $dates[$tanggal] = $total;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aktivitas',
                    'data' => array_values($dates),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34,197,94,0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 8,
                    'pointBackgroundColor' => '#22c55e',
                ],
            ],
            'labels' => array_map(fn($d) => Carbon::parse($d)->translatedFormat('d M'), array_keys($dates)),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100, // Tetapkan batas atas Y ke 20000
                    'ticks' => [
                        'stepSize' => 20, // Misalnya setiap 4000
                        'color' => '#6b7280',
                    ],
                    'grid' => ['color' => 'rgba(0,0,0,0.05)'],
                ],
                'x' => [
                    'ticks' => ['color' => '#6b7280'],
                    'grid' => ['display' => false],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'labels' => ['color' => '#111827'],
                ],
            ],
        ];
    }
}
