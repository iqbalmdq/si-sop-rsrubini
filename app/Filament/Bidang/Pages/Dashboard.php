<?php

namespace App\Filament\Bidang\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \Filament\Widgets\AccountWidget::class,
            \App\Filament\Bidang\Widgets\NotifikasiTerbaruWidget::class,
        ];
    }
}