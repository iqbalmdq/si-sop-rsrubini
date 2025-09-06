<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\Sop;
use App\Models\KategoriSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SopStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSop = Sop::count();
        $sopAktif = Sop::where('status', 'aktif')->count();
        $sopDraft = Sop::where('status', 'draft')->count();
        $sopRevisi = Sop::where('status', 'revisi')->count();
        
        // Hitung persentase SOP aktif
        $persentaseAktif = $totalSop > 0 ? round(($sopAktif / $totalSop) * 100, 1) : 0;
        
        // SOP yang perlu review (lebih dari 1 tahun)
        $sopPerluReview = Sop::where('tanggal_berlaku', '<', now()->subYear())
            ->where('status', 'aktif')
            ->count();
            
        return [
            Stat::make('Total SOP', $totalSop)
                ->description('Total dokumen SOP')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
                
            Stat::make('SOP Aktif', $sopAktif)
                ->description($persentaseAktif . '% dari total SOP')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('SOP Draft', $sopDraft)
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Perlu Review', $sopPerluReview)
                ->description('SOP lebih dari 1 tahun')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}