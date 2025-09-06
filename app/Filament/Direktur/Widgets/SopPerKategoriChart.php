<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\KategoriSop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SopPerKategoriChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi SOP per Kategori';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = KategoriSop::withCount('sops')
            ->where('is_active', true)
            ->get();
            
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
                    ],
                ],
            ],
            'labels' => $data->pluck('nama_kategori')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}