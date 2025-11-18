<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inclusion extends Model
{
    use HasFactory;

    protected $table = 'inclusions';

    protected $fillable = [
        'heading',
        'points',
        'note',
        'type',

    ];

    protected $casts = [
        'points' => 'array', // automatically convert JSON to array and back
    ];

 
}
