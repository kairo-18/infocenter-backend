<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function getShelters(Request $request)
    {
        $shelters = Shelter::all();
        return response()->json($shelters);
    }
}
