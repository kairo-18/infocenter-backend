<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sms;

class SmsController extends Controller
{
    public function sendSmsToAll(Request $request){

        Sms::create([
            'type' => $request->type,
            'message' => $request->message,
        ]);
        return response()->json(['message' => 'SMS sent!']);
    }
}
