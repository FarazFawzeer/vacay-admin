<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInvBooking extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'vehicle_inv_bookings';

    // Mass-assignable fields
    protected $fillable = [
        'inv_no',
        'customer_id',
        'vehicle_id',
        'pickup_location',
        'pickup_datetime',
        'dropoff_location',
        'dropoff_datetime',
        'mileage',
        'total_km',
        'price',
        'additional_charges',
        'discount',
        'total_price',
        'advance_paid',
        'auth_id',
        'note',
        'status',
        'payment_status',
        'payment_method',
        'currency',
    ];

    protected $casts = [
        'pickup_datetime' => 'datetime',
        'dropoff_datetime' => 'datetime',
        'price' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(VehicleDetail::class, 'vehicle_id');
    }
}
