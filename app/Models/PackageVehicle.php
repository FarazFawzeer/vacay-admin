<?php

// app/Models/PackageVehicle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'name',
        'make',
        'model',
        'condition',
        'seats',
        'max_seating_capacity',
        'luggage_space',
        'air_conditioned',
        'availability',
        'vehicle_image',
        'sub_image',
    ];

    protected $casts = [
        'sub_image' => 'array',
    ];
}
