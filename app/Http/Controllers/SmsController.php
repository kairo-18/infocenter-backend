<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Import Http Facade
use Illuminate\Support\Facades\Log;
use App\Models\Sms;
use App\Models\SmsAlertRegistration;

class SmsController extends Controller
{
    public function sendSmsToAll(Request $request)
    {

        $contacts = SmsAlertRegistration::pluck('ContactNumber')->implode(",");

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PHILSMS_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://app.philsms.com/api/v3/sms/send', [
            'recipient' => $contacts,
            'sender_id' => 'PhilSMS',
            'type' => 'plain',
            'message' => $request->type . ': ' . $request->message,
        ]);

        // Check the response
        if ($response->successful()) {
            Log::info('SMS sent successfully');
        } else {
            Log::error('Failed to send SMS');
        }
    }
}
