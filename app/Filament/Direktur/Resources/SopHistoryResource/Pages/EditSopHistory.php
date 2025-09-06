<?php

namespace App\Filament\Direktur\Resources\SopHistoryResource\Pages;

use App\Filament\Direktur\Resources\SopHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSopHistory extends EditRecord
{
    protected static string $resource = SopHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
