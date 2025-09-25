<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Survey;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SurveyResponseChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Respons Survei per Bulan';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            $count = Survey::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->withCount('responses')
                ->get()
                ->sum('responses_count');
                
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Respons',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}