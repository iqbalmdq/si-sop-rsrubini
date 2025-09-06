<?php

namespace App\Filament\Bidang\Resources\KategoriSopResource\Pages;

use App\Filament\Bidang\Resources\KategoriSopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriSops extends ListRecords
{
    protected static string $resource = KategoriSopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
