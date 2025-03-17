<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garbage extends Model
{
    //
    protected $fillable = ['title', 'description', 'day', 'time'];

    // Enum values for days
    public static function days()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}
