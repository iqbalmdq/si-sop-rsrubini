<?php

namespace App\Filament\Direktur\Resources;

use App\Filament\Direktur\Resources\SopResource\Pages;
use App\Models\KategoriSop;
use App\Models\Sop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SopResource extends Resource
{
    protected static ?string $model = Sop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Dasbor & Analisis';

    protected static ?string $navigationLabel = 'Semua Dokumen';

    protected static ?string $modelLabel = 'Dokumen';

    protected static ?string $pluralModelLabel = 'Dokumen';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi SOP')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_sop')
                            ->label('Nomor Dokumen')
                            ->disabled(),
                        Forms\Components\TextInput::make('judul_sop')
                            ->label('Judul Dokumen')
                            ->disabled(),
                        Forms\Components\Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama_kategori')
                            ->disabled(),
                        Forms\Components\TextInput::make('bidang_bagian')
                            ->label('Bidang/Bagian')
                            ->disabled(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('isi_sop')
                            ->label('Isi Dokumen')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Versi')
                    ->schema([
                        Forms\Components\TextInput::make('status')
                            ->label('Status')
                            ->disabled(),
                        Forms\Components\TextInput::make('versi')
                            ->label('Versi')
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_berlaku')
                            ->label('Tanggal Berlaku')
                            ->disabled(),
                        Forms\Components\DatePicker::make('tanggal_review')
                            ->label('Tanggal Review')
                            ->disabled(),
                        Forms\Components\TextInput::make('creator.name')
                            ->label('Dibuat Oleh')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Tanggal Dibuat')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_sop')
                    ->label('Nomor Dokumen')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 40 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('bidang_bagian')
                    ->label('Bidang/Bagian')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'aktif' => 'success',
                        'revisi' => 'warning',
                        'nonaktif' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'aktif' => 'Aktif',
                        'revisi' => 'Revisi',
                        'nonaktif' => 'Non-Aktif',
                    }),
                Tables\Columns\TextColumn::make('versi')
                    ->label('Versi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_berlaku')
                    ->label('Tanggal Berlaku')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->options(KategoriSop::where('is_active', true)->pluck('nama_kategori', 'id'))
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'aktif' => 'Aktif',
                        'revisi' => 'Revisi',
                        'nonaktif' => 'Non-Aktif',
                    ]),
                SelectFilter::make('bidang_bagian')
                    ->label('Bidang/Bagian')
                    ->options(function () {
                        return Sop::distinct()->pluck('bidang_bagian', 'bidang_bagian');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListSops::route('/'),
            'view' => Pages\ViewSop::route('/{record}'),
        ];
    }
}
