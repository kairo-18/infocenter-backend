<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'image',
        'latitude',
        'longitude',
        'locationLink'
    ];
}
