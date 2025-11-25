<?php

namespace App\Filament\Direktur\Resources\NotifikasiResource\Pages;

use App\Filament\Direktur\Resources\NotifikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotifikasi extends ViewRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_read')
                ->label('Tandai Dibaca')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn ($record) => ! $record->is_read)
                ->action(fn ($record) => $record->update(['is_read' => true])),
        ];
    }
}
