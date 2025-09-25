<?php

namespace App\Filament\Bidang\Resources\SurveyResource\Pages;

use App\Filament\Bidang\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Survei Baru')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'Manajemen Survei';
    }

    public function getHeading(): string
    {
        return 'Daftar Survei';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola survei yang Anda buat untuk mengumpulkan feedback dan data dari responden.';
    }
}