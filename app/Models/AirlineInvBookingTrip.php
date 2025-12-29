<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineInvBookingTrip extends Model
{
    use HasFactory;

    protected $table = 'airline_booking_trips';

    protected $fillable = [
        'airline_booking_id',
        'trip_type',
        'passport_id',
        'passport_no',
        'agent_id',
        'airline',
        'airline_no',
        'from_country',
        'to_country',
        'pnr',
        'departure_datetime',
        'arrival_datetime',
        'baggage_qty',
        'handluggage_qty',
    ];

    /**
     * Trip belongs to a booking
     */
    public function booking()
    {
        return $this->belongsTo(AirlineInvBooking::class, 'airline_booking_id');
    }

    /**
     * Trip belongs to a passport
     */
    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }

    /**
     * Trip belongs to an agent
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
