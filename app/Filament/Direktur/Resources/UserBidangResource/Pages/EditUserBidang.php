<?php

namespace App\Filament\Direktur\Resources\UserBidangResource\Pages;

use App\Filament\Direktur\Resources\UserBidangResource;
use Filament\Resources\Pages\EditRecord;

class EditUserBidang extends EditRecord
{
    protected static string $resource = UserBidangResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Perubahan akun tersimpan';
    }
}
