<?php

namespace App\Filament\Widgets;

use App\Models\Rating;
use Filament\Widgets\BarChartWidget;

class BlogPostsChart extends BarChartWidget
{
    protected static ?string $heading = 'Rating Pengguna';

    protected function getData(): array
    {
        // Ambil jumlah rating berdasarkan nilai rating (1–5)
        $ratings = Rating::selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('total', 'rating');

        // Siapkan data dan warna berdasarkan rating 1–5
        $data = [];
        $colors = [];

        $colorMap = [
            1 => '#ef4444', // Merah
            2 => '#f97316', // Oranye
            3 => '#eab308', // Kuning
            4 => '#3b82f6', // Biru
            5 => '#10b981', // Hijau
        ];

        for ($i = 1; $i <= 5; $i++) {
            $data[] = $ratings[$i] ?? 0;
            $colors[] = $colorMap[$i];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Rating',
                    'data' => $data,
                    'backgroundColor' => $colors, // pakai warna berbeda tiap bar
                ],
            ],
            'labels' => ['Bintang 1', 'Bintang 2', 'Bintang 3', 'Bintang 4', 'Bintang 5'],
        ];
    }
}
