<?php

namespace App\Filament\Bidang\Resources\SurveyResource\Pages;

use App\Filament\Bidang\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Survei';
    }

    public function getHeading(): string
    {
        return 'Edit Survei: ' . $this->record->judul;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Survei berhasil diperbarui';
    }
}