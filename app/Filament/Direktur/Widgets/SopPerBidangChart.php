<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Sop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SopPerBidangChart extends ChartWidget
{
    protected static ?string $heading = 'Dokumen per Bidang/Bagian';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Sop::select('bidang_bagian', DB::raw('count(*) as total'))
            ->groupBy('bidang_bagian')
            ->orderBy('total', 'desc')
            ->get();
            
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dokumen',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#1D4ED8',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('bidang_bagian')->toArray(),
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
                    'display' => false,
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