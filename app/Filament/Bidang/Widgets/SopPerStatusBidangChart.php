<?php

namespace App\Filament\Bidang\Widgets;

use App\Models\Sop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SopPerStatusBidangChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status SOP (Bidang)';

    protected static ?int $sort = 1;

    // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $user = Auth::user();
        $bidang = $user?->bidang_bagian;

        $statuses = ['draft', 'aktif', 'revisi', 'nonaktif'];

        $counts = Sop::query()
            ->when($bidang, fn ($q) => $q->where('bidang_bagian', $bidang))
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $data = [];
        foreach ($statuses as $status) {
            $data[] = (int) ($counts[$status] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah SOP',
                    'data' => $data,
                    'backgroundColor' => [
                        '#9CA3AF', // Draft - Gray
                        '#10B981', // Aktif - Green
                        '#F59E0B', // Revisi - Yellow
                        '#EF4444', // Nonaktif - Red
                    ],
                ],
            ],
            'labels' => [
                'Draft',
                'Aktif',
                'Revisi',
                'Non-Aktif',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}

