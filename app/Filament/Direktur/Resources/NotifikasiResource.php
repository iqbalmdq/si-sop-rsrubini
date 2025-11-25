<?php

namespace App\Filament\Direktur\Resources;

use App\Filament\Direktur\Resources\NotifikasiResource\Pages;
use App\Models\Notifikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NotifikasiResource extends Resource
{
    protected static ?string $model = Notifikasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Notifikasi';

    protected static ?string $navigationLabel = 'Notifikasi';

    protected static ?string $modelLabel = 'Notifikasi';

    protected static ?string $pluralModelLabel = 'Notifikasi';

    public static function getNavigationBadge(): ?string
    {
        $unreadCount = static::getEloquentQuery()
            ->where('is_read', false)
            ->count();

        return $unreadCount > 0 ? (string) $unreadCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Notifikasi')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul')
                            ->disabled(),
                        Forms\Components\Textarea::make('pesan')
                            ->label('Pesan')
                            ->disabled()
                            ->rows(3),
                        Forms\Components\TextInput::make('tipe')
                            ->label('Tipe')
                            ->disabled(),
                        Forms\Components\Placeholder::make('nomor_sop_display')
                            ->label('Nomor SOP')
                            ->content(function (Notifikasi $record): string {
                                if ($record->sop && $record->sop->nomor_sop) {
                                    return $record->sop->nomor_sop;
                                }
                                $text = $record->pesan ?? '';
                                if (preg_match('/\(([^)]+)\)/', $text, $m)) {
                                    return $m[1];
                                }

                                return '';
                            }),
                        Forms\Components\Placeholder::make('creator_display')
                            ->label('Dibuat Oleh')
                            ->content(function (Notifikasi $record): string {
                                if ($record->creator && $record->creator->name) {
                                    return $record->creator->name;
                                }
                                $text = $record->pesan ?? '';
                                if (preg_match('/oleh\s+([^\n]+)$/', $text, $m)) {
                                    return trim($m[1]);
                                }

                                return '';
                            }),
                        Forms\Components\Placeholder::make('target_bidang_display')
                            ->label('Target Bidang')
                            ->content(function (Notifikasi $record): string {
                                if (! empty($record->target_bidang)) {
                                    return $record->target_bidang;
                                }
                                if ($record->sop && $record->sop->bidang_bagian) {
                                    return $record->sop->bidang_bagian;
                                }
                                $text = $record->pesan ?? '';
                                if (preg_match('/Bagian\s+([^\)\n]+)/', $text, $m)) {
                                    return trim($m[0]);
                                }

                                return '';
                            }),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Tanggal')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_read')
                            ->label('Sudah Dibaca')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->searchable()
                    ->weight(fn (Notifikasi $record) => $record->is_read ? 'normal' : 'bold'),
                Tables\Columns\TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(60)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 60 ? $state : null;
                    }),
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
                        'sop_baru' => 'SOP Baru',
                        'sop_update' => 'Update SOP',
                        'sop_delete' => 'Hapus SOP',
                        'sop_review' => 'Review SOP',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('nomor_sop_display')
                    ->label('Nomor SOP')
                    ->getStateUsing(function (Notifikasi $record): ?string {
                        if ($record->sop && $record->sop->nomor_sop) {
                            return $record->sop->nomor_sop;
                        }
                        $text = $record->pesan ?? '';
                        if (preg_match('/\(([^)]+)\)/', $text, $m)) {
                            return $m[1];
                        }

                        return null;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_bidang_display')
                    ->label('Target Bidang')
                    ->getStateUsing(function (Notifikasi $record): ?string {
                        if (! empty($record->target_bidang)) {
                            return $record->target_bidang;
                        }
                        if ($record->sop && $record->sop->bidang_bagian) {
                            return $record->sop->bidang_bagian;
                        }
                        $text = $record->pesan ?? '';
                        if (preg_match('/oleh\s+([^\n]+)$/', $text, $m)) {
                            return trim($m[1]);
                        }

                        return null;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dari')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->headerActions([])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->boolean()
                    ->trueLabel('Sudah Dibaca')
                    ->falseLabel('Belum Dibaca')
                    ->native(false),
                SelectFilter::make('tipe')
                    ->label('Tipe Notifikasi')
                    ->options([
                        'sop_baru' => 'SOP Baru',
                        'sop_update' => 'Update SOP',
                        'sop_delete' => 'Hapus SOP',
                        'sop_review' => 'Review SOP',
                    ]),
                SelectFilter::make('target_bidang')
                    ->label('Target Bidang')
                    ->options(fn () => \App\Models\Sop::distinct()->pluck('bidang_bagian', 'bidang_bagian')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(function (Notifikasi $record) {
                        if (! $record->is_read) {
                            $record->update(['is_read' => true]);
                        }
                    }),
                Tables\Actions\Action::make('mark_read')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Notifikasi $record) => ! $record->is_read)
                    ->action(fn (Notifikasi $record) => $record->update(['is_read' => true])),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_all_read')
                        ->label('Tandai Semua Dibaca')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(fn (Notifikasi $record) => $record->update(['is_read' => true]));
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Semua')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifikasis::route('/'),
            'view' => Pages\ViewNotifikasi::route('/{record}'),
        ];
    }
}
