<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentVehicleBooking extends Model
{
    use HasFactory;

    protected $table = 'rent_vehicle_bookings';

    protected $fillable = [
        'inv_no',
        'customer_id',
        'vehicle_id',
        'status',
        'payment_status',
        'payment_method',
        'currency',
        'price',
        'additional_price',
        'discount',
        'advance_paid',   // ✅ added
        'auth_id',        // ✅ added
        'tax',
        'total_price',
        'start_datetime',
        'end_datetime',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_datetime'   => 'datetime',
        'end_datetime'     => 'datetime',
        'price'            => 'decimal:2',
        'additional_price' => 'decimal:2',
        'discount'         => 'decimal:2',
        'advance_paid'     => 'decimal:2', // ✅ added
        'tax'              => 'decimal:2',
        'total_price'      => 'decimal:2',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
