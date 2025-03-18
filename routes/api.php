<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sms;
use App\Http\Controllers\SmsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/send-sms-all',[SmsController::class, 'sendSmsToAll']);
