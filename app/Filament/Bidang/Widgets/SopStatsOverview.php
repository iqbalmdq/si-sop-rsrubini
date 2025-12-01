<?php

namespace App\Filament\Bidang\Widgets;

use App\Models\Sop;
use App\Models\Survey;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SopStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        $bidang = $user?->bidang_bagian;

        $totalSop = Sop::query()
            ->when($bidang, fn ($q) => $q->where('bidang_bagian', $bidang))
            ->count();

        $activeSop = Sop::query()
            ->when($bidang, fn ($q) => $q->where('bidang_bagian', $bidang))
            ->where('status', 'aktif')
            ->count();

        $draftSop = Sop::query()
            ->when($bidang, fn ($q) => $q->where('bidang_bagian', $bidang))
            ->where('status', 'draft')
            ->count();

        $totalSurvey = Survey::query()
            ->where('dibuat_oleh', $user->id)
            ->count();

        return [
            Stat::make('Total Dokumen', $totalSop)
                ->description('Semua dokumen di bidang ini')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),

            Stat::make('Dokumen Aktif', $activeSop)
                ->description('Dokumen yang sedang berlaku')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Dokumen Draft', $draftSop)
                ->description('Dokumen dalam penyusunan')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning'),

            Stat::make('Survey Dibuat', $totalSurvey)
                ->description('Survey yang dibuat anda')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
        ];
    }
}
