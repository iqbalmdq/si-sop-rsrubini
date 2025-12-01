<?php

namespace App\Filament\Bidang\Resources\SurveyResource\Pages;

use App\Filament\Bidang\Resources\SurveyResource;
use App\Models\Survey;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;

class ViewSurveyResults extends Page implements \Filament\Infolists\Contracts\HasInfolists
{
    use InteractsWithInfolists;

    protected static string $resource = SurveyResource::class;

    protected static string $view = 'filament.bidang.pages.survey-results';

    public Survey $record;

    public function mount(Survey $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return 'Hasil Survei: '.$this->record->judul;
    }

    public function getHeading(): string
    {
        return 'Hasil Jawaban Responden';
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

    public function getResultsData(): array
    {
        $survey = $this->record;
        $responses = $survey->responses()->with(['answers.question', 'user'])->get();
        $questions = $survey->questions()->with('answers')->get();

        $resultsData = [];

        foreach ($questions as $question) {
            $questionData = [
                'question' => $question->teks_pertanyaan,
                'type' => $question->tipe_pertanyaan,
                'total_responses' => $question->answers()->count(),
                'data' => [],
            ];

            switch ($question->tipe_pertanyaan) {
                case 'radio':
                case 'select':
                    $answers = $question->answers()
                        ->selectRaw('teks_jawaban, COUNT(*) as count')
                        ->groupBy('teks_jawaban')
                        ->pluck('count', 'teks_jawaban')
                        ->toArray();

                    $questionData['data'] = [
                        'labels' => array_keys($answers),
                        'values' => array_values($answers),
                        'chart_type' => 'pie',
                    ];
                    break;

                case 'checkbox':
                    $allAnswers = $question->answers()->pluck('data_jawaban')->filter()->flatten()->toArray();
                    $answerCounts = array_count_values($allAnswers);

                    $questionData['data'] = [
                        'labels' => array_keys($answerCounts),
                        'values' => array_values($answerCounts),
                        'chart_type' => 'bar',
                    ];
                    break;

                case 'rating':
                    $ratings = $question->answers()
                        ->selectRaw('teks_jawaban, COUNT(*) as count')
                        ->groupBy('teks_jawaban')
                        ->orderBy('teks_jawaban')
                        ->pluck('count', 'teks_jawaban')
                        ->toArray();

                    $average = $question->answers()->avg('teks_jawaban');

                    $questionData['data'] = [
                        'labels' => array_keys($ratings),
                        'values' => array_values($ratings),
                        'average' => round($average, 2),
                        'chart_type' => 'bar',
                    ];
                    break;

                case 'teks':
                case 'textarea':
                    $textAnswers = $question->answers()
                        ->with('response.user')
                        ->whereNotNull('teks_jawaban')
                        ->get()
                        ->map(function ($answer) {
                            return [
                                'text' => $answer->teks_jawaban,
                                'user' => $answer->response->user ? $answer->response->user->name : 'Anonim',
                                'date' => $answer->response->waktu_submit ? $answer->response->waktu_submit->format('d M Y, H:i') : 'Tidak diketahui',
                            ];
                        })
                        ->toArray();

                    $questionData['data'] = [
                        'answers' => $textAnswers,
                        'chart_type' => 'text',
                    ];
                    break;

                case 'angka':
                    $numbers = $question->answers()
                        ->whereNotNull('teks_jawaban')
                        ->pluck('teks_jawaban')
                        ->map(fn ($val) => (float) $val)
                        ->toArray();

                    $questionData['data'] = [
                        'values' => $numbers,
                        'average' => count($numbers) > 0 ? round(array_sum($numbers) / count($numbers), 2) : 0,
                        'min' => count($numbers) > 0 ? min($numbers) : 0,
                        'max' => count($numbers) > 0 ? max($numbers) : 0,
                        'chart_type' => 'number',
                    ];
                    break;
            }

            $resultsData[] = $questionData;
        }

        return $resultsData;
    }

    public function getDetailedResponses(): array
    {
        $survey = $this->record;
        $responses = $survey->responses()
            ->with(['answers.question', 'user'])
            ->orderBy('waktu_submit', 'desc')
            ->get();

        $detailedData = [];

        foreach ($responses as $response) {
            $responseData = [
                'id' => $response->id,
                'user' => $response->user ? $response->user->name : 'Anonim',
                'user_bidang' => $response->user ? $response->user->bidang_bagian : 'Tidak diketahui',
                'submit_time' => $response->waktu_submit ? $response->waktu_submit->format('d M Y, H:i') : 'Tidak diketahui',
                'answers' => [],
            ];

            foreach ($response->answers as $answer) {
                $responseData['answers'][] = [
                    'question' => $answer->question->teks_pertanyaan,
                    'answer' => $answer->teks_jawaban ?: ($answer->data_jawaban ? implode(', ', $answer->data_jawaban) : 'Tidak dijawab'),
                ];
            }

            $detailedData[] = $responseData;
        }

        return $detailedData;
    }

    public function exportToExcel()
    {
        // Implementation for Excel export
        // You can use Laravel Excel package for this
        return response()->download(storage_path('app/survey_results.xlsx'));
    }

    public function exportToPdf()
    {
        // Implementation for PDF export
        // You can use DomPDF or similar package for this
        return response()->download(storage_path('app/survey_results.pdf'));
    }
}
