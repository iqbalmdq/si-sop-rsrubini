<?php

namespace App\Filament\Direktur\Resources\SurveyAnalysisResource\Pages;

use App\Filament\Direktur\Resources\SurveyAnalysisResource;
use App\Models\Survey;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ViewSurveyAnalysis extends Page implements \Filament\Infolists\Contracts\HasInfolists
{
    use InteractsWithInfolists;

    protected static string $resource = SurveyAnalysisResource::class;

    protected static string $view = 'filament.direktur.pages.survey-analysis';

    public Survey $record;

    public function mount(Survey $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return 'Analisis Survei: ' . $this->record->judul;
    }

    public function getHeading(): string
    {
        return 'Analisis Hasil Survei';
    }

    public function surveyInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Section::make('Informasi Survei')
                    ->schema([
                        TextEntry::make('judul')
                            ->label('Judul Survei'),
                        TextEntry::make('deskripsi')
                            ->label('Deskripsi Survei')
                            ->placeholder('Tidak ada deskripsi'),
                        TextEntry::make('status')
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
                        TextEntry::make('creator.name')
                            ->label('Pembuat Survei'),
                        TextEntry::make('creator.bidang_bagian')
                            ->label('Bidang Pembuat'),
                        TextEntry::make('total_responses')
                            ->label('Total Respons')
                            ->getStateUsing(fn (): int => $this->record->responses()->count())
                            ->badge()
                            ->color('success'),
                        TextEntry::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Tidak diatur'),
                        TextEntry::make('tanggal_berakhir')
                            ->label('Tanggal Berakhir')
                            ->dateTime('d F Y, H:i')
                            ->placeholder('Tidak diatur'),
                    ])
                    ->columns(2),
            ]);
    }

    public function getAnalysisData(): array
    {
        $survey = $this->record;
        $responses = $survey->responses()->with('answers.question')->get();
        $questions = $survey->questions()->with('answers')->get();
        
        $analysisData = [];
        
        foreach ($questions as $question) {
            $questionData = [
                'question' => $question->teks_pertanyaan,
                'type' => $question->tipe_pertanyaan,
                'total_responses' => $question->answers()->count(),
                'data' => [],
            ];
            
            switch ($question->tipe_pertanyaan) {
                case 'pilihan_ganda':
                case 'dropdown':
                    $answers = $question->answers()
                        ->selectRaw('answer_text, COUNT(*) as count')
                        ->groupBy('answer_text')
                        ->pluck('count', 'answer_text')
                        ->toArray();
                    
                    $questionData['data'] = [
                        'labels' => array_keys($answers),
                        'values' => array_values($answers),
                        'chart_type' => 'pie',
                    ];
                    break;
                
                case 'kotak_centang':
                    $allAnswers = $question->answers()->pluck('answer_data')->flatten()->toArray();
                    $answerCounts = array_count_values($allAnswers);
                    
                    $questionData['data'] = [
                        'labels' => array_keys($answerCounts),
                        'values' => array_values($answerCounts),
                        'chart_type' => 'bar',
                    ];
                    break;
                
                case 'rating':
                    $ratings = $question->answers()
                        ->selectRaw('answer_text, COUNT(*) as count')
                        ->groupBy('answer_text')
                        ->orderBy('answer_text')
                        ->pluck('count', 'answer_text')
                        ->toArray();
                    
                    $average = $question->answers()->avg('answer_text');
                    
                    $questionData['data'] = [
                        'labels' => array_keys($ratings),
                        'values' => array_values($ratings),
                        'average' => round($average, 2),
                        'chart_type' => 'bar',
                    ];
                    break;
                
                case 'teks':
                case 'teks_panjang':
                    $textAnswers = $question->answers()
                        ->whereNotNull('answer_text')
                        ->pluck('answer_text')
                        ->toArray();
                    
                    $questionData['data'] = [
                        'answers' => $textAnswers,
                        'chart_type' => 'text',
                    ];
                    break;
            }
            
            $analysisData[] = $questionData;
        }
        
        return $analysisData;
    }
}