<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'star',
        'status',
        'room_type',
        'meal_plan',
        'description',
        'facilities',
        'entertainment',
        'pictures',
        'city',
        'address',
        'hotel_category',
    ];

    protected $casts = [
        'pictures' => 'array',
    ];
}
