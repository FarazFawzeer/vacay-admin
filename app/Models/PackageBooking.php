<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    use HasFactory;

    protected $table = 'package_bookings';

    protected $fillable = [
        'full_name',
        'last_name',
        'street',
        'city',
        'country',
        'email',
        'phone',
        'whatsapp',
        'adults',
        'children',
        'infants',
        'package_id',
        'start_date',
        'end_date',
        'pickup',
        'hotel_type',
        'travelling_from',
        'travel_reason',
        'theme',
        'message',
        'status',
        'invoice_id',
        'payment_status',
        'transaction_id',
        'order_id',
        'card_holder_name',
        'card_number',
        'payment_method',
        'payment_scheme',
        'payable_amount',
        'payable_currency',
        'status_message'
    ];


       protected $casts = [
        'theme' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    /**
     * Get the package associated with this booking.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
