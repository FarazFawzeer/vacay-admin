<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailItinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'place_name',
        'day',
        'pictures',
        'description',
        'program_points',
        'overnight_stay',
        'meal_plan',
        'approximate_travel_time',
        'map_image'
    ];

    

   

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function highlights()
    {
        return $this->hasMany(Highlight::class, 'itinerary_id', 'id');
    }


    public function destination()
{
    return $this->belongsTo(Destination::class, 'place_id');
}
    
}
