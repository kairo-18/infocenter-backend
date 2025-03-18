<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sms;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/send-sms-all', function (Request $request) {
    Sms::create([
        'type' => $request->type,
        'message' => $request->message,
    ]);
    return response()->json(['message' => 'SMS sent!']);
})->name('send-sms-all');
