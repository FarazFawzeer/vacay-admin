<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Package extends Model
{
    use HasFactory, SoftDeletes;

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


    protected static function booted()
    {
        static::deleting(function ($package) {

            // ================= Tour Summaries =================
            $package->tourSummaries()->delete();

            // ================= Detail Itineraries + Highlights =================
            foreach ($package->detailItineraries as $itinerary) {

                // Delete itinerary picture
                if ($itinerary->pictures) {
                    Storage::disk('public')->delete($itinerary->pictures);
                }

                // Delete highlights
                foreach ($itinerary->highlights as $highlight) {
                    if ($highlight->images) {
                        Storage::disk('public')->delete($highlight->images);
                    }
                    $highlight->delete();
                }

                $itinerary->delete();
            }

            // ================= Package Vehicles =================
            $package->packageVehicles()->delete();

            // ================= Package Inclusions =================
            $package->packageInclusions()->delete();

            // ================= Package Images =================
            if ($package->picture) {
                Storage::disk('public')->delete($package->picture);
            }

            if ($package->map_image) {
                Storage::disk('public')->delete($package->map_image);
            }
        });
    }
}
