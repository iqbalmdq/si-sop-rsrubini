<?php

namespace App\Filament\Bidang\Resources;

use App\Filament\Bidang\Resources\SopResource\Pages;
use App\Models\KategoriSop;
use App\Models\Sop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SopResource extends Resource
{
    protected static ?string $model = Sop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen SOP';

    protected static ?string $navigationLabel = 'Dokumen SOP';

    protected static ?string $modelLabel = 'SOP';

    protected static ?string $pluralModelLabel = 'SOP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar SOP')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_sop')
                            ->label('Nomor SOP')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),
                        Forms\Components\Select::make('kategori_id')
                            ->label('Kategori SOP')
                            ->options(KategoriSop::where('is_active', true)->pluck('nama_kategori', 'id'))
                            ->required()
                            ->searchable()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('judul_sop')
                            ->label('Judul SOP')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Detail SOP')
                    ->schema([
                        Forms\Components\RichEditor::make('isi_sop')
                            ->label('Isi SOP')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'link',
                                'table',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Lampiran (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->directory('sop-files')
                            ->disk('public')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pengaturan SOP')
                    ->schema([
                        Forms\Components\Hidden::make('bidang_bagian')
                            ->default(fn () => Auth::user()->bidang_bagian),
                        Forms\Components\Select::make('status')
                            ->label('Status SOP')
                            ->options([
                                'draft' => 'Draft',
                                'aktif' => 'Aktif',
                                'revisi' => 'Revisi',
                                'nonaktif' => 'Non-Aktif',
                            ])
                            ->default('draft')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('versi')
                            ->label('Versi')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('tanggal_berlaku')
                            ->label('Tanggal Berlaku')
                            ->required()
                            ->default(now())
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('tanggal_review')
                            ->label('Tanggal Review')
                            ->columnSpan(1),
                        Forms\Components\Hidden::make('created_by')
                            ->default(fn () => Auth::id()),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_sop')
                    ->label('Nomor SOP')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul SOP')
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
                    ->sortable()
                    ->toggleable(),
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
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan SOP dari bidang/bagian user yang login
        return parent::getEloquentQuery()->where('bidang_bagian', Auth::user()->bidang_bagian);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSops::route('/'),
            'create' => Pages\CreateSop::route('/create'),
            'view' => Pages\ViewSop::route('/{record}'),
            'edit' => Pages\EditSop::route('/{record}/edit'),
        ];
    }
}
