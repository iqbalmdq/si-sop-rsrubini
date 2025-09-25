<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\SurveyQuestion;
use Filament\Widgets\ChartWidget;

class SurveyQuestionTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Tipe Pertanyaan Survei';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $questionTypes = SurveyQuestion::selectRaw('tipe_pertanyaan, COUNT(*) as count')
            ->groupBy('tipe_pertanyaan')
            ->pluck('count', 'tipe_pertanyaan')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Yellow
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#F97316', // Orange
        ];

        $colorIndex = 0;
        $backgroundColors = [];

        foreach ($questionTypes as $type => $count) {
            $label = match ($type) {
                'pilihan_ganda' => 'Pilihan Ganda',
                'dropdown' => 'Dropdown',
                'kotak_centang' => 'Kotak Centang',
                'rating' => 'Rating',
                'teks' => 'Teks Pendek',
                'teks_panjang' => 'Teks Panjang',
                default => $type,
            };
            
            $labels[] = $label;
            $data[] = $count;
            $backgroundColors[] = $colors[$colorIndex % count($colors)];
            $colorIndex++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pertanyaan',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed + " pertanyaan";
                        }'
                    ]
                ]
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}