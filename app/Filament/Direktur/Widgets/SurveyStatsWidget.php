<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Survey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Survei', Survey::count())
                ->description('Jumlah keseluruhan survei yang telah dibuat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success'),
            
            Stat::make('Survei Aktif', Survey::where('status', 'aktif')->count())
                ->description('Survei yang sedang berjalan dan dapat diisi')
                ->descriptionIcon('heroicon-m-play')
                ->color('info'),
            
            Stat::make('Total Respons', function () {
                return Survey::withCount('responses')->get()->sum('responses_count');
            })
                ->description('Jumlah keseluruhan respons dari semua survei')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
            
            Stat::make('Rata-rata Respons', function () {
                $surveys = Survey::withCount('responses')->get();
                return $surveys->count() > 0 ? round($surveys->avg('responses_count'), 1) : 0;
            })
                ->description('Rata-rata jumlah respons per survei')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}