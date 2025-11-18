<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportationBooking extends Model
{
    // Table name (optional if it follows Laravel naming conventions)
    protected $table = 'transportation_bookings';

    // The attributes that are mass assignable.
    protected $fillable = [
        'vehicle_id',
        'full_name',
        'email',
        'phone',
        'whatsapp',
        'country',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'pickup_location',
        'drop_location',
        'service_type',
        'hour_count',
        'message',
        'status',
    ];

    // Cast date/time fields to appropriate types
    protected $casts = [
        'start_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_date' => 'date',
        'end_time' => 'datetime:H:i:s',
    ];

    // Optional: Relationship with Vehicle model
    public function vehicle()
    {
        return $this->belongsTo(VehicleDetail::class, 'vehicle_id');
    }
}
