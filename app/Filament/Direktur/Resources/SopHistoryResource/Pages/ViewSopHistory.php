<?php

namespace App\Filament\Direktur\Resources\SopHistoryResource\Pages;

use App\Filament\Direktur\Resources\SopHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSopHistory extends ViewRecord
{
    protected static string $resource = SopHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit action since SopHistoryResource::canEdit() returns false
        ];
    }
}