<?php

namespace App\Http\Controllers;

use App\Models\Flood;
use Illuminate\Http\Request;

class FloodController extends Controller
{
    public function getFloods()
    {
        $floods = Flood::all();
        return response()->json($floods);
    }
}
