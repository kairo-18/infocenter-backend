<?php

use App\Http\Controllers\FireController;
use App\Http\Controllers\FirstAidController;
use App\Http\Controllers\FloodController;
use App\Http\Controllers\GarbageController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\SmsAlertRegistrationController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\TsunamiController;
use App\Http\Controllers\UtilityController;
use App\Models\Fire;
use App\Models\Flood;
use App\Models\Garbage;
use App\Models\Traffic;
use App\Models\Tsunami;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/send-sms-all', [SmsController::class, 'sendSmsToAll']);

Route::get('/get-shelters', [ShelterController::class, 'getShelters']);
Route::get('/get-power-outages', [PowerController::class, 'getPowerOutages']);
Route::get('/get-tsunamis', [TsunamiController::class, 'getTsunamis']);
Route::get('/get-floods', [FloodController::class, 'getFloods']);
Route::get('/get-garbage-collection', [GarbageController::class, 'getGarbage']);
Route::get('/get-pharmacies', [PharmacyController::class, 'getPharmacies']);
Route::get('/get-traffic', [TrafficController::class, 'getTraffic']);
Route::get('/get-fire', [FireController::class, 'getFire']);
Route::get('/get-first-aid', [FirstAidController::class, 'getFirstAid']);
Route::get('/get-utility', [UtilityController::class, 'getUtility']);
Route::get('/get-sms-alerts', [SmsController::class, 'getSmsAlerts']);

Route::get('/get-sms-registration', [SmsAlertRegistrationController::class, 'getSmsRegistration']);
Route::post('/register-sms', [SmsAlertRegistrationController::class, 'create']);

Route::get('/get-recent-announcements', function () {
    try {
        // Helper function to format each item
        $formatItem = function ($model, $tag) {
            $record = $model::latest()->first();
            if (! $record) {
                return ['tag' => $tag, 'message' => 'No announcement yet'];
            }

            $data = $record->toArray();

            // Convert status from 1/0 to Active/Inactive
            if (isset($data['status'])) {
                $data['status'] = ($data['status'] == 1 || $data['status'] === true || $data['status'] === '1')
                    ? 'Active'
                    : 'Inactive';
            }

            return array_merge($data, ['tag' => $tag]);
        };

        $allData = [
            $formatItem(Fire::class, 'fire'),
            $formatItem(Flood::class, 'flood'),
            $formatItem(Tsunami::class, 'tsunami'),
            $formatItem(Garbage::class, 'garbage'),
            $formatItem(Traffic::class, 'traffic'),
            $formatItem(Utility::class, 'utility'),
        ];

        // Filter out inactive records from the response
        $recentData = array_filter($allData, function ($item) {
            // If it's a "No announcement yet" message, exclude it
            if (isset($item['message'])) {
                return false;
            }

            $status = $item['status'] ?? null;

            return $status === 'Active';
        });

        // Re-index the array
        $recentData = array_values($recentData);

        return response()->json([
            'success' => true,
            'message' => 'Recent active data retrieved successfully',
            'data' => $recentData,
            'timestamp' => now()->toISOString(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve recent active data',
            'error' => $e->getMessage(),
        ], 500);
    }
});
