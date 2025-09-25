<?php

namespace App\Filament\Bidang\Resources\SurveyResource\Pages;

use App\Filament\Bidang\Resources\SurveyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dibuat_oleh'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Buat Survei Baru';
    }

    public function getHeading(): string
    {
        return 'Buat Survei Baru';
    }

    public function getSubheading(): ?string
    {
        return 'Buat survei untuk mengumpulkan feedback, evaluasi, atau data lainnya dari responden.';
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Survei berhasil dibuat!';
    }
}