<?php

namespace App\Filament\Direktur\Widgets;

use App\Models\SopHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AktivitasTerbaruWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas SOP Terbaru';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SopHistory::with(['sop', 'user'])
                    ->latest('tanggal_aksi')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('sop.nomor_sop')
                    ->label('Nomor SOP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sop.judul_sop')
                    ->label('Judul SOP')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'status_changed' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                        'status_changed' => 'Status Diubah',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Oleh')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_aksi')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}