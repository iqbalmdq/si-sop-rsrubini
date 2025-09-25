<?php

namespace App\Filament\Direktur\Resources\SurveyAnalysisResource\Pages;

use App\Filament\Direktur\Resources\SurveyAnalysisResource;
use Filament\Resources\Pages\ListRecords;

class ListSurveyAnalyses extends ListRecords
{
    protected static string $resource = SurveyAnalysisResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Direktur\Widgets\SurveyStatsWidget::class,
            \App\Filament\Direktur\Widgets\SurveyAnalysisChart::class,
            \App\Filament\Direktur\Widgets\SurveyResponseRateChart::class,
            \App\Filament\Direktur\Widgets\SurveyQuestionTypeChart::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Analisis Survei';
    }

    public function getHeading(): string
    {
        return 'Analisis Hasil Survei';
    }

    public function getSubheading(): ?string
    {
        return 'Lihat dan analisis hasil survei yang telah dibuat oleh berbagai bidang.';
    }
}