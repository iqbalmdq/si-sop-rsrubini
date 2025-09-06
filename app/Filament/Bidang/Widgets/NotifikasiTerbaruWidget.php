<?php

namespace App\Filament\Bidang\Widgets;

use App\Models\Notifikasi;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class NotifikasiTerbaruWidget extends BaseWidget
{
    protected static ?string $heading = 'Notifikasi Terbaru';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userBidang = Auth::user()->bidang_bagian;
        
        return $table
            ->query(
                Notifikasi::with(['sop', 'creator'])
                    ->where(function ($query) use ($userBidang) {
                        $query->whereNull('target_bidang')
                              ->orWhere('target_bidang', $userBidang);
                    })
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-s-envelope')
                    ->trueColor('success')
                    ->falseColor('primary')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->weight(fn (Notifikasi $record) => $record->is_read ? 'normal' : 'bold')
                    ->limit(30),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sop_baru' => 'success',
                        'sop_update' => 'warning',
                        'sop_delete' => 'danger',
                        'sop_review' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sop_baru' => 'Baru',
                        'sop_update' => 'Update',
                        'sop_delete' => 'Hapus',
                        'sop_review' => 'Review',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Notifikasi $record): string => 
                        route('filament.bidang.resources.notifikasis.view', $record)
                    )
                    ->after(function (Notifikasi $record) {
                        if (!$record->is_read) {
                            $record->update(['is_read' => true]);
                        }
                    }),
            ])
            ->paginated(false);
    }
}
