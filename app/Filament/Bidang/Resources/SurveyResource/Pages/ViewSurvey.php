<?php

namespace App\Filament\Bidang\Resources\SurveyResource\Pages;

use App\Filament\Bidang\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSurvey extends ViewRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Detail Survei: ' . $this->record->judul;
    }
}