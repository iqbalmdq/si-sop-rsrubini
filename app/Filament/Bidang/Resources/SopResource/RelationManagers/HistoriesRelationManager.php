<?php

namespace App\Filament\Bidang\Resources\SopResource\RelationManagers;

use App\Models\SopHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    protected static ?string $title = 'Riwayat Perubahan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('keterangan')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_aksi')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        'status_changed' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No create action
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Perubahan')
                    ->modalHeading('Detail Perubahan')
                    ->modalWidth('7xl')
                    ->form(function (SopHistory $record) {
                        $schema = [];
                        $oldData = $record->data_lama ?? [];
                        $newData = $record->data_baru ?? [];
                        
                        // Identify keys that changed
                        $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                        $ignoredKeys = ['updated_at', 'created_at', 'id', 'updated_by', 'created_by', 'notifikasis_count', 'histories_count'];

                        $hasChanges = false;

                        foreach ($allKeys as $key) {
                            if (in_array($key, $ignoredKeys)) continue;

                            $old = $oldData[$key] ?? null;
                            $new = $newData[$key] ?? null;

                            if ($old != $new) {
                                $hasChanges = true;
                                $componentClass = Forms\Components\Textarea::class;
                                $extraProps = [];
                                
                                if ($key === 'isi_sop') {
                                    $componentClass = Forms\Components\RichEditor::class;
                                    $extraProps['toolbarButtons'] = []; // Read only view usually doesn't need toolbar
                                }

                                $fieldSchema = [
                                    $componentClass::make("old_{$key}")
                                        ->label("Lama: " . ucfirst(str_replace('_', ' ', $key)))
                                        ->default($old)
                                        ->disabled()
                                        ->columnSpan(1),
                                    $componentClass::make("new_{$key}")
                                        ->label("Baru: " . ucfirst(str_replace('_', ' ', $key)))
                                        ->default($new)
                                        ->disabled()
                                        ->columnSpan(1),
                                ];
                                
                                // Apply extra properties if any
                                if ($key === 'isi_sop') {
                                     $fieldSchema[0]->toolbarButtons([]);
                                     $fieldSchema[1]->toolbarButtons([]);
                                }

                                $schema[] = Forms\Components\Section::make(ucfirst(str_replace('_', ' ', $key)))
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema($fieldSchema)
                                    ])
                                    ->collapsible();
                            }
                        }
                        
                        if (!$hasChanges) {
                            if ($record->aksi === 'created') {
                                $schema[] = Forms\Components\Placeholder::make('created_info')
                                    ->label('Info')
                                    ->content('Ini adalah pembuatan awal SOP.');
                            } else {
                                $schema[] = Forms\Components\Placeholder::make('no_changes')
                                    ->label('Info')
                                    ->content('Tidak ada perubahan data konten (mungkin hanya status atau metadata).');
                            }
                        }

                        return $schema;
                    }),
            ])
            ->bulkActions([
                // No bulk actions
            ])
            ->defaultSort('tanggal_aksi', 'desc');
    }
}
