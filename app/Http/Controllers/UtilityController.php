<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;

class UtilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getUtility()
    {
        $$utility = Utiity::all();
        return response()->json($utility);
    }

}
