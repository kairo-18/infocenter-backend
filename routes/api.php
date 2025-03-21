<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Sms;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\FloodController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\GarbageController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\TsunamiController;
use App\Http\Controllers\ShelterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/send-sms-all',[SmsController::class, 'sendSmsToAll']);

Route::get('/get-shelters',[ShelterController::class, 'getShelters']);
Route::get('/get-power-outages', [PowerController::class, 'getPowerOutages']);
Route::get('/get-tsunamis', [TsunamiController::class, 'getTsunamis']);
Route::get('/get-floods', [FloodController::class, 'getFloods']);
Route::get('/get-garbage-collection', [GarbageController::class, 'getGarbage']);
Route::get('/get-pharmacies', [PharmacyController::class, 'getPharmacies']);



