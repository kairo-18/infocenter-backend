<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShelterController extends Controller
{
    public function getShelters(Request $request)
    {
        $shelters = Shelter::all()->map(function ($shelters) {
            return [
                'id' => $shelters->id,
                'name' => $shelters->name,
                'address' => $shelters->address,
                'description' => $shelters->description,
                'locationLink' => $shelters->locationLink,
                'latitude' => $shelters->latitude,
                'longitude' => $shelters->longitude,
                // Generate full image URL:
                'image' => $shelters->image 
                    ? Storage::disk('public')->url($shelters->image) 
                    : null,
                'created_at' => $shelters->created_at,
                'updated_at' => $shelters->updated_at,
            ];
        });
        return response()->json($shelters); 
    }
}
