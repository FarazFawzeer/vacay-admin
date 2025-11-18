<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationPoint extends Model
{
    use HasFactory;

    protected $table = 'cancellation_points';

    protected $fillable = [
        'heading',
        'points',
        'note',
    ];

    protected $casts = [
        'points' => 'array',
    ];
}
