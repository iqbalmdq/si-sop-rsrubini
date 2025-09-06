<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSopController;

// ... existing routes ...

// Public SOP Routes (tanpa authentication)
Route::prefix('sop')->name('sop.')->group(function () {
    Route::get('/', [PublicSopController::class, 'index'])->name('index');
    Route::get('/search', [PublicSopController::class, 'search'])->name('search');
    Route::get('/bidang', [PublicSopController::class, 'getBidangList'])->name('bidang');
    Route::get('/{sop:nomor_sop}', [PublicSopController::class, 'show'])->name('show');
    Route::get('/{sop:nomor_sop}/download', [PublicSopController::class, 'download'])->name('download');
    Route::get('/{type}/{category}/{number}/{year}', [PublicSopController::class, 'showByParts'])->name('show.parts');
    Route::get('/{type}/{category}/{number}/{year}/download', [PublicSopController::class, 'downloadByParts'])->name('download.parts');
});

// Redirect root ke halaman pencarian SOP
Route::get('/', function () {
    return redirect()->route('sop.index');
});