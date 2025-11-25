<?php

use App\Http\Controllers\PublicSopController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

// Public SOP Routes (tanpa authentication)
Route::prefix('sop')->name('sop.')->group(function () {
    Route::get('/', [PublicSopController::class, 'index'])->name('index');
    Route::get('/search', [PublicSopController::class, 'search'])->name('search');
    Route::get('/bidang', [PublicSopController::class, 'getBidangList'])->name('bidang');
    Route::get('/{type}/{category}/{number}/{year}', [PublicSopController::class, 'showByParts'])->name('show.parts');
    Route::get('/{type}/{category}/{number}/{year}/download', [PublicSopController::class, 'downloadByParts'])->name('download.parts');
    Route::get('/{sop}/download', [PublicSopController::class, 'download'])->where('sop', '.*')->name('download');
    Route::get('/{sop}/preview', [PublicSopController::class, 'preview'])->where('sop', '.*')->name('preview');
    Route::get('/{nomor_sop}', [PublicSopController::class, 'show'])->where('nomor_sop', '.*')->name('show');
});

// Public Survey Routes (tanpa authentication)
Route::prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/thankyou', [SurveyController::class, 'thankyou'])->name('thankyou');
    Route::get('/{survey}', [SurveyController::class, 'show'])->name('show');
    Route::post('/{survey}/submit', [SurveyController::class, 'submit'])->name('submit');
});

// Redirect root ke halaman pencarian SOP
Route::get('/', function () {
    return redirect()->route('sop.index');
});
