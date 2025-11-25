<?php

namespace App\Filament\Direktur\Resources\UserBidangResource\Pages;

use App\Filament\Direktur\Resources\UserBidangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserBidangs extends ListRecords
{
    protected static string $resource = UserBidangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
