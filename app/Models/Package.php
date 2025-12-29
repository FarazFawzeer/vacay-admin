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
        'hilight_show_hide',
    ];

    public function tourSummaries()
    {
        return $this->hasMany(TourSummary::class);
    }

    public function detailItineraries()
    {
        return $this->hasMany(DetailItinerary::class);
    }

    public function itineraries()
    {
        return $this->detailItineraries();
    }

    public function summaries()
    {
        return $this->tourSummaries();
    }


    // App/Models/Package.php

    public function packageVehicle()
    {
        return $this->belongsTo(VehicleDetail::class, 'vehicle_id');
        // Assuming 'vehicle_id' is stored in packages table
    }

    public function vehicles()
    {
        return $this->belongsToMany(VehicleDetail::class, 'package_vehicles', 'package_id', 'id');
    }


    public function packageVehicles()
    {
        return $this->hasMany(PackageVehicle::class, 'package_id');
    }

    public function inclusions()
    {
        return $this->belongsToMany(Inclusion::class, 'package_inclusions', 'package_id', 'inclusion_id');
    }


    public function packageInclusions()
{
    return $this->hasMany(PackageInclusion::class, 'package_id');
}


}
