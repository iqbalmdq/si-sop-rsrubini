<?php

namespace App\Filament\Bidang\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Bidang\Widgets\SopStatsOverview::class,
            \App\Filament\Bidang\Widgets\SopPerStatusBidangChart::class,
            \App\Filament\Bidang\Widgets\SopPerKategoriBidangChart::class,
        ];
    }
}
