<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Power;

class PowerController extends Controller
{
    public function getPowerOutages()
    {
        $powerOutages = Power::all();
        return response()->json($powerOutages);
    }
}
