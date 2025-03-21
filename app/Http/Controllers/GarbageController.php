<?php

namespace App\Http\Controllers;

use App\Models\Garbage;
use Illuminate\Http\Request;

class GarbageController extends Controller
{
    public function getGarbage()
    {
        $garbage = Garbage::all();
        return response()->json($garbage);
    }
    //
}
