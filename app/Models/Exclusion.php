<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exclusion extends Model
{
    use HasFactory;

    protected $table = 'exclusions';

    protected $fillable = [
        'heading',
        'points',
        'note',
    ];

    protected $casts = [
        'points' => 'array', // handle JSON as array
    ];
}
