<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PharmacyController extends Controller
{
    public function getPharmacies()
    {
        $pharmacies = Pharmacy::all()->map(function ($pharmacy) {
            return [
                'id' => $pharmacy->id,
                'name' => $pharmacy->name,
                'address' => $pharmacy->address,
                'description' => $pharmacy->description,
                'locationLink' => $pharmacy->locationLink,
                'latitude' => $pharmacy->latitude,
                'longitude' => $pharmacy->longitude,
                // Generate full image URL:
                'image' => $pharmacy->image 
                    ? Storage::disk('public')->url($pharmacy->image) 
                    : null,
                'created_at' => $pharmacy->created_at,
                'updated_at' => $pharmacy->updated_at,
            ];
        });
        return response()->json($pharmacies); 
    }
    //
}
