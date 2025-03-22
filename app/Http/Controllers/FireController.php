<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fire;

class FireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getFire()
    {
        $fire = Fire::all();
        return response()->json($fire);
    }
}