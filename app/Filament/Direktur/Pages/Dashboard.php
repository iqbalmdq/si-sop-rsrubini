<?php

namespace App\Filament\Direktur\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Direktur\Widgets\SopStatsWidget::class,
            \App\Filament\Direktur\Widgets\SopPerKategoriChart::class,
            \App\Filament\Direktur\Widgets\SopPerBidangChart::class,
            \App\Filament\Direktur\Widgets\AktivitasTerbaruWidget::class,
        ];
    }
}