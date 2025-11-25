<?php

namespace App\Filament\Bidang\Resources;

use App\Filament\Bidang\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Survei';

    protected static ?string $pluralModelLabel = 'Survei';

    protected static ?string $modelLabel = 'Survei';

    protected static ?string $navigationGroup = 'Manajemen Survei';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Survei')
                    ->description('Masukkan informasi dasar tentang survei yang akan dibuat')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul Survei')
                            ->placeholder('Contoh: Survei Kepuasan Layanan Rumah Sakit')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Survei')
                            ->placeholder('Jelaskan tujuan dan konteks survei ini...')
                            ->rows(3),

                        Select::make('status')
                            ->label('Status Survei')
                            ->options([
                                'konsep' => 'Konsep',
                                'aktif' => 'Aktif',
                                'tutup' => 'Ditutup', // Ubah dari 'ditutup' ke 'tutup'
                            ])
                            ->default('konsep')
                            ->required()
                            ->helperText('Survei hanya dapat diisi jika statusnya Aktif'),

                        Select::make('target_bidang')
                            ->label('Pemilik Survei')
                            ->options([
                                'Bagian Tata Usaha' => 'Bagian Tata Usaha',
                                'Bidang Pelayanan' => 'Bidang Pelayanan',
                                'Bidang Pengendalian' => 'Bidang Pengendalian',
                                'Bidang Penunjang' => 'Bidang Penunjang',
                            ])
                            ->placeholder('Semua Bidang')
                            ->helperText('Kosongkan jika survei ditujukan untuk semua bidang'),
                    ])
                    ->columns(2),

                Section::make('Pengaturan Survei')
                    ->description('Atur cara survei akan dijalankan')
                    ->schema([
                        Toggle::make('anonim')
                            ->label('Survei Anonim')
                            ->helperText('Jika diaktifkan, responden tidak perlu login untuk mengisi survei'),

                        Toggle::make('izin_respon_ganda')
                            ->label('Izinkan Respons Berulang')
                            ->helperText('Jika diaktifkan, satu orang dapat mengisi survei lebih dari sekali'),

                        DateTimePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->helperText('Survei dapat diisi mulai tanggal ini')
                            ->native(false),

                        DateTimePicker::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->helperText('Survei tidak dapat diisi setelah tanggal ini')
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Daftar Pertanyaan')
                    ->description('Buat pertanyaan-pertanyaan untuk survei Anda')
                    ->schema([
                        Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                TextInput::make('teks_pertanyaan')
                                    ->label('Teks Pertanyaan')
                                    ->placeholder('Tulis pertanyaan Anda di sini...')
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('tipe_pertanyaan')
                                    ->label('Jenis Pertanyaan')
                                    ->options([
                                        'teks' => 'Teks Pendek',
                                        'textarea' => 'Teks Panjang (Paragraf)',
                                        'radio' => 'Pilihan Ganda (Satu Jawaban)',
                                        'checkbox' => 'Kotak Centang (Beberapa Jawaban)',
                                        'select' => 'Menu Dropdown',
                                        'rating' => 'Penilaian Bintang (1-5)',
                                        'tanggal' => 'Tanggal',
                                        'angka' => 'Angka',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->helperText('Pilih jenis pertanyaan yang sesuai'),

                                Toggle::make('wajib_diisi')
                                    ->label('Wajib Diisi')
                                    ->helperText('Responden harus menjawab pertanyaan ini'),

                                Repeater::make('pilihan')
                                    ->label('Pilihan Jawaban')
                                    ->schema([
                                        TextInput::make('value')
                                            ->label('Opsi Jawaban')
                                            ->placeholder('Masukkan pilihan jawaban...')
                                            ->required(),
                                    ])
                                    ->visible(fn ($get) => in_array($get('tipe_pertanyaan'), ['radio', 'checkbox', 'select']))
                                    ->columnSpanFull()
                                    ->addActionLabel('Tambah Pilihan')
                                    ->minItems(2)
                                    ->helperText('Minimal 2 pilihan jawaban diperlukan'),
                            ])
                            ->orderColumn('urutan')
                            ->addActionLabel('Tambah Pertanyaan Baru')
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['teks_pertanyaan'] ?? 'Pertanyaan Baru')
                            ->minItems(1)
                            ->helperText('Minimal 1 pertanyaan diperlukan untuk survei'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Survei')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->colors([
                        'secondary' => 'konsep',
                        'success' => 'aktif',
                        'danger' => 'tutup', // Ubah dari 'ditutup' ke 'tutup'
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'konsep' => 'Konsep',
                        'aktif' => 'Aktif',
                        'tutup' => 'Ditutup', // Display tetap 'Ditutup'
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('target_bidang')
                    ->label('Pemilik Survei')
                    ->placeholder('Semua Bidang')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('total_responses')
                    ->label('Jumlah Respons')
                    ->getStateUsing(fn (Survey $record): int => $record->responses()->count())
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Tidak diatur'),

                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->label('Berakhir')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Tidak diatur'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'konsep' => 'Konsep',
                        'aktif' => 'Aktif',
                        'ditutup' => 'Ditutup',
                    ]),

                Tables\Filters\SelectFilter::make('target_bidang')
                    ->label('Pemilik Survei')
                    ->options([
                        'Bagian Tata Usaha' => 'Bagian Tata Usaha',
                        'Bidang Pelayanan' => 'Bidang Pelayanan',
                        'Bidang Pengendalian' => 'Bidang Pengendalian',
                        'Bidang Penunjang' => 'Bidang Penunjang',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\Action::make('view_results')
                    ->label('Lihat Hasil')
                    ->icon('heroicon-o-chart-bar')
                    ->color('success')
                    ->url(fn (Survey $record): string => static::getUrl('results', ['record' => $record]))
                    ->visible(fn (Survey $record): bool => $record->responses()->count() > 0),
                Tables\Actions\Action::make('duplicate')
                    ->label('Salin Survei')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->action(function (Survey $record) {
                        $newSurvey = $record->replicate();
                        $newSurvey->judul = $record->judul.' (Salinan)';
                        $newSurvey->status = 'konsep'; // Pastikan menggunakan 'konsep' bukan 'draft'
                        $newSurvey->dibuat_oleh = Auth::id();
                        $newSurvey->save();

                        foreach ($record->questions as $question) {
                            $newQuestion = $question->replicate();
                            $newQuestion->survey_id = $newSurvey->id;
                            $newQuestion->save();
                        }

                        return redirect()->route('filament.bidang.resources.surveys.edit', $newSurvey);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Salin Survei')
                    ->modalDescription('Apakah Anda yakin ingin menyalin survei ini? Salinan akan dibuat dengan status Konsep.')
                    ->modalSubmitActionLabel('Ya, Salin'),
                Tables\Actions\Action::make('share_link')
                    ->label('Bagikan Link')
                    ->icon('heroicon-o-link')
                    ->color('primary')
                    ->url(fn (Survey $record): string => route('survey.show', $record))
                    ->openUrlInNewTab()
                    ->tooltip('Buka tautan survei publik'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Survei')
            ->emptyStateDescription('Mulai buat survei pertama Anda untuk mengumpulkan feedback dan data.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('dibuat_oleh', Auth::id());
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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'view' => Pages\ViewSurvey::route('/{record}'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
            'results' => Pages\ViewSurveyResults::route('/{record}/results'),
        ];
    }
}
