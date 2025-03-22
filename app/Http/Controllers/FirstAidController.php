<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FirstAid;

class FirstAidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getFirstAid()
    {
        $firstAid = FirstAid::all();
        return response()->json($firstAid);
    }
   
}
