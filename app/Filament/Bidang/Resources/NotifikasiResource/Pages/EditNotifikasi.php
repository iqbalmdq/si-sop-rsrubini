<?php

namespace App\Filament\Bidang\Resources\NotifikasiResource\Pages;

use App\Filament\Bidang\Resources\NotifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotifikasi extends EditRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
