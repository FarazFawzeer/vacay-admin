<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineInvBooking extends Model
{
    use HasFactory;

    protected $table = 'airline_inv_bookings';

    protected $fillable = [
        'invoice_id',
        'business_type',
        'company_name',
        'ticket_type',
        'return_type',
        'status',
        'payment_status',
        'currency',
        'base_price',
        'additional_price',
        'discount',
        'total_amount',
        'advanced_paid',
        'balance',
        'created_by',
        'note',
        'published_at',
    ];

    protected $casts = [

        'published_at'     => 'date',

    ];
    /**
     * One booking has many flight trips
     */
    public function trips()
    {
        return $this->hasMany(AirlineInvBookingTrip::class, 'airline_booking_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // 'created_by' is FK to users table
    }

    public function customer()
    {
        return $this->hasManyThrough(
            \App\Models\Customer::class,  // final model
            \App\Models\Passport::class,  // intermediate model
            'id',                          // passport.id (local key in passports)
            'id',                          // customer.id (local key in customers)
            'id',                          // booking.id (local key in bookings)
            'customer_id'                  // passport.customer_id
        )->join('airline_booking_trips', 'airline_booking_trips.passport_id', '=', 'passports.id')
            ->where('airline_booking_trips.airline_booking_id', $this->id)
            ->select('customers.*');
    }
}
