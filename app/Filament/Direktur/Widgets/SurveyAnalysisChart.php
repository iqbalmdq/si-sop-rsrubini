<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Survey;
use Filament\Widgets\ChartWidget;

class SurveyAnalysisChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Survei';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $statusCounts = Survey::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Ensure all statuses are represented
        $allStatuses = ['konsep', 'aktif', 'tutup'];
        $data = [];
        $labels = [];
        
        foreach ($allStatuses as $status) {
            $count = $statusCounts[$status] ?? 0;
            $data[] = $count;
            
            $label = match ($status) {
                'konsep' => 'Konsep',
                'aktif' => 'Aktif',
                'tutup' => 'Ditutup',
                default => $status,
            };
            $labels[] = $label;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Survei',
                    'data' => $data,
                    'backgroundColor' => [
                        '#6B7280', // Gray for Konsep
                        '#10B981', // Green for Aktif
                        '#EF4444', // Red for Ditutup
                    ],
                    'borderColor' => [
                        '#374151',
                        '#059669',
                        '#DC2626',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
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
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed + " survei";
                        }'
                    ]
                ]
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}