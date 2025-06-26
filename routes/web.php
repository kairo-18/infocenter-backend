<?php

use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/download-chart-pdf', [ChartController::class, 'downloadPdf']
)->name('chart.download.pdf');

// Custom date range PDF download route
Route::get('/chart/download/custom', [ChartController::class, 'downloadCustomDatePdf'])
    ->name('chart.download.custom')
    ->middleware(['auth']); // Add appropriate middleware as needed

// Optional: Add a route for AJAX preview (if you want to show preview data)
Route::post('/chart/preview/custom', [ChartController::class, 'getCustomDatePreview'])
    ->name('chart.preview.custom')
    ->middleware(['auth']);
