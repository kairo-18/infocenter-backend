<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function getPharmacies()
    {
        $pharmacies = Pharmacy::all();
        return response()->json($pharmacies);
    }
    //
}
