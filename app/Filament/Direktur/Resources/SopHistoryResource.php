<?php

namespace App\Filament\Direktur\Resources;

use App\Filament\Direktur\Resources\SopHistoryResource\Pages;
use App\Models\SopHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;

class SopHistoryResource extends Resource
{
    protected static ?string $model = SopHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'History & Audit';

    protected static ?string $navigationLabel = 'History SOP';

    protected static ?string $modelLabel = 'History SOP';

    protected static ?string $pluralModelLabel = 'History SOP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail History')
                    ->schema([
                        Forms\Components\TextInput::make('sop.nomor_sop')
                            ->label('Nomor SOP')
                            ->disabled(),
                        Forms\Components\TextInput::make('sop.judul_sop')
                            ->label('Judul SOP')
                            ->disabled(),
                        Forms\Components\TextInput::make('aksi')
                            ->label('Aksi')
                            ->disabled(),
                        Forms\Components\TextInput::make('user.name')
                            ->label('Dilakukan Oleh')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('tanggal_aksi')
                            ->label('Tanggal Aksi')
                            ->disabled(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->disabled()
                            ->rows(3),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Data Perubahan')
                    ->schema([
                        Forms\Components\KeyValue::make('data_lama')
                            ->label('Data Lama')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('data_baru')
                            ->label('Data Baru')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sop.nomor_sop')
                    ->label('Nomor SOP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sop.judul_sop')
                    ->label('Judul SOP')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),
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
                    ->label('Dilakukan Oleh')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sop.bidang_bagian')
                    ->label('Bidang/Bagian')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_aksi')
                    ->label('Tanggal Aksi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('aksi')
                    ->label('Jenis Aksi')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                        'status_changed' => 'Status Diubah',
                    ]),
                SelectFilter::make('user_id')
                    ->label('Dilakukan Oleh')
                    ->relationship('user', 'name')
                    ->searchable(),
                Filter::make('tanggal_aksi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_aksi', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_aksi', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('tanggal_aksi', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopHistories::route('/'),
            'view' => Pages\ViewSopHistory::route('/{record}'),
        ];
    }
}