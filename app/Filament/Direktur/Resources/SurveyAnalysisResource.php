<?php

namespace App\Filament\Direktur\Resources;

use App\Filament\Direktur\Resources\SurveyAnalysisResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyAnalysisResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analisis Survei';

    protected static ?string $modelLabel = 'Analisis Survei';

    protected static ?string $pluralModelLabel = 'Analisis Survei';

    protected static ?string $navigationGroup = 'Manajemen Survei';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Survei')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.bidang_bagian')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'konsep' => 'secondary',
                        'aktif' => 'success',
                        'tutup' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'konsep' => 'Konsep',
                        'aktif' => 'Aktif',
                        'tutup' => 'Ditutup',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('total_responses')
                    ->label('Total Respons')
                    ->getStateUsing(fn (Survey $record): int => $record->responses()->count())
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Tidak diatur'),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Tanggal Berakhir')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Tidak diatur'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'konsep' => 'Konsep',
                        'aktif' => 'Aktif',
                        'tutup' => 'Ditutup',
                    ]),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label('Pembuat')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Analisis')
                    ->icon('heroicon-m-chart-bar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateHeading('Belum Ada Survei untuk Dianalisis')
            ->emptyStateDescription('Survei yang dibuat oleh bidang akan muncul di sini untuk dianalisis.')
            ->emptyStateActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyAnalyses::route('/'),
            'view' => Pages\ViewSurveyAnalysis::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}