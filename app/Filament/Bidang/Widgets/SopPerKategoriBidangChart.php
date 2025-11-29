<?php

namespace App\Filament\Bidang\Widgets;

use App\Models\KategoriSop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SopPerKategoriBidangChart extends ChartWidget
{
    protected static ?string $heading = 'SOP per Kategori (Bidang)';

    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $user = Auth::user();
        $bidang = $user?->bidang_bagian;

        $data = KategoriSop::withCount(['sops' => function ($query) use ($bidang) {
                if ($bidang) {
                    $query->where('bidang_bagian', $bidang);
                }
            }])
            ->where('is_active', true)
            ->get();

        // Optional: Filter categories with 0 SOPs for this bidang if needed.
        // For now, let's keep them or maybe filter if count > 0 to reduce clutter.
        // $data = $data->filter(fn ($kategori) => $kategori->sops_count > 0);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah SOP',
                    'data' => $data->pluck('sops_count')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', // Blue
                        '#10B981', // Green
                        '#F59E0B', // Yellow
                        '#EF4444', // Red
                        '#8B5CF6', // Purple
                        '#F97316', // Orange
                        '#6366F1', // Indigo
                        '#EC4899', // Pink
                    ],
                ],
            ],
            'labels' => $data->pluck('nama_kategori')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false, // Hide legend for bar chart usually
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
