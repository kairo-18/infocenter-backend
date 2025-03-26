<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsAlertRegistration;

class SmsAlertRegistrationController extends Controller
{
    public function getSmsRegistration()
    {
        $smsRegistrations = SmsAlertRegistration::all();
        return response()->json($smsRegistrations);
    }

    public function create(Request $request)
    {
        
        $validatedData = $request->validate([
            'LastName' => 'required|string|max:50',
            'FirstName' => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:sms_alert_registrations,email',
            'ContactNumber' => 'required|string|max:12',
        ]);

        $smsRegistration = SmsAlertRegistration::create($validatedData);

        return response()->json([
            'message' => 'SMS Alert Registration created successfully!',
            'data' => $smsRegistration
        ], 201);
    }
}
