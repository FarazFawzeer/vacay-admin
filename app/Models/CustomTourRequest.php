<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTourRequest extends Model
{
    //

        protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'preferred_dates',
        'travelers',
        'status',
    ];
}
