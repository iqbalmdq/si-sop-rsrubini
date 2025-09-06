<?php

namespace App\Filament\Direktur\Resources\SopHistoryResource\Pages;

use App\Filament\Direktur\Resources\SopHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSopHistories extends ListRecords
{
    protected static string $resource = SopHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
