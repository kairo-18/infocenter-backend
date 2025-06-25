<?php

use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/download-chart-pdf', [ChartController::class, 'downloadPdf']
)->name('chart.download.pdf');
