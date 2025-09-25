<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Survey;
use Filament\Widgets\ChartWidget;

class SurveyResponseRateChart extends ChartWidget
{
    protected static ?string $heading = 'Tingkat Respons Survei Aktif';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $surveys = Survey::where('status', 'aktif')
            ->withCount('responses')
            ->orderBy('responses_count', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $data = [];

        foreach ($surveys as $survey) {
            $labels[] = strlen($survey->judul) > 30 
                ? substr($survey->judul, 0, 30) . '...' 
                : $survey->judul;
            $data[] = $survey->responses_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Respons',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
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
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return "Respons: " + context.parsed.y;
                        }'
                    ]
                ]
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}