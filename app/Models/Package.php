<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'options',
        'tour_ref_no',
        'description',
        'location',
        'picture',
        'summary_description',
        'country_name',
        'place',    
        'type',
        'tour_category',
        'price',
        'days',
        'nights',
        'ratings',
        'status',
        'map_image',

    ];

    public function tourSummaries()
    {
        return $this->hasMany(TourSummary::class);
    }

    public function detailItineraries()
    {
        return $this->hasMany(DetailItinerary::class);
    }

    
}
