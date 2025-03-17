<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flood extends Model
{
    protected $fillable =
    [
        'name',
        'description',
        'severity',
        'date',
    ];
    //
}
