<?php

namespace App\Http\Controllers;

use App\Models\Tsunami;
use Illuminate\Http\Request;

class TsunamiController extends Controller
{
    public function getTsunamis()
    {
        $tsunamis = Tsunami::all();
        return response()->json($tsunamis);
    }
    //
}
