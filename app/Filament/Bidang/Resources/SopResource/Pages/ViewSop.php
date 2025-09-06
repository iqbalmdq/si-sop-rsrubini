<?php

namespace App\Filament\Bidang\Resources\SopResource\Pages;

use App\Filament\Bidang\Resources\SopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSop extends ViewRecord
{
    protected static string $resource = SopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}