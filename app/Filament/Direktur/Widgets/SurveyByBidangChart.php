<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Survey;
use Filament\Widgets\ChartWidget;

class SurveyByBidangChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Survei per Bidang';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $surveys = Survey::selectRaw('creator.bidang_bagian, COUNT(*) as count')
            ->join('users as creator', 'surveys.created_by', '=', 'creator.id')
            ->groupBy('creator.bidang_bagian')
            ->pluck('count', 'creator.bidang_bagian')
            ->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($surveys),
                    'backgroundColor' => [
                        'rgb(239, 68, 68)',
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                    ],
                ],
            ],
            'labels' => array_keys($surveys),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}