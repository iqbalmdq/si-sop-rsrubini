<?php

namespace App\Filament\Bidang\Resources\NotifikasiResource\Pages;

use App\Filament\Bidang\Resources\NotifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotifikasi extends ViewRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}