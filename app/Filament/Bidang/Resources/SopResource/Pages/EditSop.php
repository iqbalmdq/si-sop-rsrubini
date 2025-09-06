<?php

namespace App\Filament\Bidang\Resources\SopResource\Pages;

use App\Filament\Bidang\Resources\SopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSop extends EditRecord
{
    protected static string $resource = SopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
