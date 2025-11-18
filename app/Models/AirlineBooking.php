<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AirlineBooking extends Model
{
    //
 use HasFactory;

    protected $table = 'airline_bookings';

    protected $fillable = [
    'full_name',
    'email',
    'phone',
    'whatsapp',
    'country',
    'trip_type',
    'airline',
    'from',
    'to',
    'departure_date',
    'return_date',
    'passengers',
    'message',
    'status',
];
}
