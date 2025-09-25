<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('status', 'aktif')
            ->where(function ($query) {
                $query->whereNull('tanggal_mulai')
                      ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('tanggal_berakhir')
                      ->orWhere('tanggal_berakhir', '>=', now());
            })
            ->with(['creator', 'questions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('public.survey.index', compact('surveys'));
    }

    public function show(Survey $survey)
    {
        // Cek apakah survei aktif dan dalam periode yang tepat
        if ($survey->status !== 'aktif') {
            abort(404, 'Survei tidak tersedia.');
        }

        if ($survey->tanggal_mulai && $survey->tanggal_mulai > now()) {
            abort(404, 'Survei belum dimulai.');
        }

        if ($survey->tanggal_berakhir && $survey->tanggal_berakhir < now()) {
            abort(404, 'Survei sudah berakhir.');
        }

        // Cek apakah user sudah mengisi survei (jika tidak anonymous dan tidak allow multiple)
        if (!$survey->anonim && !$survey->izin_respon_ganda && Auth::check()) {
            $existingResponse = SurveyResponse::where('survey_id', $survey->id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($existingResponse) {
                return redirect()->route('survey.thankyou')
                    ->with('message', 'Anda sudah mengisi survei ini sebelumnya.');
            }
        }

        $survey->load(['questions' => function ($query) {
            $query->orderBy('urutan');
        }]);

        return view('public.survey.show', compact('survey'));
    }

    public function submit(Request $request, Survey $survey)
    {
        // Validasi survei masih aktif
        if ($survey->status !== 'aktif') {
            return back()->withErrors(['error' => 'Survei tidak tersedia.']);
        }

        if ($survey->tanggal_berakhir && $survey->tanggal_berakhir < now()) {
            return back()->withErrors(['error' => 'Survei sudah berakhir.']);
        }

        // Cek duplikasi respons
        if (!$survey->anonim && !$survey->izin_respon_ganda && Auth::check()) {
            $existingResponse = SurveyResponse::where('survey_id', $survey->id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($existingResponse) {
                return back()->withErrors(['error' => 'Anda sudah mengisi survei ini sebelumnya.']);
            }
        }

        $survey->load(['questions' => function ($query) {
            $query->orderBy('urutan');
        }]);

        // Validasi jawaban wajib
        $rules = [];
        foreach ($survey->questions as $question) {
            if ($question->wajib_diisi) {
                $rules["answers.{$question->id}"] = 'required';
            }
        }

        $messages = [];
        foreach ($survey->questions as $question) {
            $messages["answers.{$question->id}.required"] = "Pertanyaan '{$question->teks_pertanyaan}' wajib diisi.";
        }

        $request->validate($rules, $messages);

        // Simpan respons
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::id(),
            'session_id' => $survey->anonim ? session()->getId() : null,
            'waktu_submit' => now(),
        ]);

        // Simpan jawaban
        foreach ($survey->questions as $question) {
            $answer = $request->input("answers.{$question->id}");
            
            if ($answer !== null) {
                $answerText = null;
                $answerData = null;

                // Proses berdasarkan tipe pertanyaan
                switch ($question->tipe_pertanyaan) {
                    case 'teks':
                    case 'textarea':
                        $answerText = $answer;
                        break;
                    
                    case 'radio':
                    case 'select':
                        $answerText = $answer;
                        $answerData = ['selected' => $answer];
                        break;
                    
                    case 'checkbox':
                        $answerText = is_array($answer) ? implode(', ', $answer) : $answer;
                        $answerData = ['selected' => is_array($answer) ? $answer : [$answer]];
                        break;
                    
                    case 'rating':
                        $answerText = $answer;
                        $answerData = ['rating' => (int) $answer];
                        break;
                    
                    case 'tanggal':
                        $answerText = $answer;
                        $answerData = ['date' => $answer];
                        break;
                    
                    case 'angka':
                        $answerText = $answer;
                        $answerData = ['number' => (float) $answer];
                        break;
                }

                SurveyAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $question->id,
                    'teks_jawaban' => $answerText,
                    'data_jawaban' => $answerData,
                ]);
            }
        }

        return redirect()->route('survey.thankyou')
            ->with('message', 'Terima kasih! Jawaban Anda telah berhasil disimpan.');
    }

    public function thankyou()
    {
        return view('public.survey.thankyou');
    }
}