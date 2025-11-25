<?php

namespace App\Filament\Direktur\Resources\UserBidangResource\Pages;

use App\Filament\Direktur\Resources\UserBidangResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserBidang extends CreateRecord
{
    protected static string $resource = UserBidangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan role selalu 'bidang' saat membuat akun baru
        $data['role'] = 'bidang';

        return $data;
    }
}
