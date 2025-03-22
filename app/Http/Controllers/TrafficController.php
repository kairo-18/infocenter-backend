<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Traffic;

class TrafficController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getTraffic()
    {
        $traffic = Traffic::all();
        return response()->json($traffic);
    }

}